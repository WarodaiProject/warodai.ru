<?php
require_once(dirname(__FILE__).'/../../etc/config.php');
require_once(dirname(__FILE__).'/../../vendor/autoload.php');


$repos = $_CONF['corpus_local_repos'];

date_default_timezone_set('UTC');
//Установка локали - иначе неверно обрабатываются символы с регулярках в grep
$locale='C.UTF-8';
setlocale(LC_ALL,$locale);
putenv('LC_ALL='.$locale);

$m = new MongoDB\Client($_CONF['mongo_url']);
$coll = $m->warodai->corpus;

foreach($repos as $corpus=>$conf){
    if(!$conf['build']){
        continue;
    }
    print("Start process ".$corpus.".\n");

    $sort = 'code';
    if($conf['sort']){
        $sort = $conf['sort'];
    }
    $repoPath = $conf['git'];
    $lastRepoDate = '1970-01-01 00:00:01';
    $lastArchDate = '1970-01-01 00:00:01';
    $entries = [];
    $output = '';
    $e = [];

    exec("git -C {$repoPath} pull origin master");
      
    exec("git -C {$repoPath} log -n 1", $e);
    foreach($e as $str){
        if(preg_match('/Date:\s+(.+)/',$str,$matches)){
            $lastRepoDate = (new DateTime($matches[1]))->format('Y-m-d H:i:s');
        }
    }

    if(file_exists($conf['output_arch'])){
        $lastArchDate = date('Y-m-d H:i:s',filemtime($conf['output_arch']));
    }
    
    if($lastRepoDate <= $lastArchDate){
        print("Archive file for {$corpus} is ahead of or equal to repo. Skipping DB and file update.\n");
        continue;
    }

    print("Start building.\n");
    scanCorpDir($repoPath,$entries);
    
    if($sort == 'code'){
        ksort($entries);
    }
    else{
        try{
            if($sort == 'kana'){
                uasort($entries, 'sortByKana');
            }
            if($sort == 'kiriji'){
                uasort($entries, 'sortByKiriji');
            }
            if($sort == 'header'){
                uasort($entries, 'sortByHeader');
            }
        }
        catch (Exception $e){
            die("Ошибка при сортировке: ".$e."\n");
        }     
    }

    $output = <<<EOD
*******************************************************************************************************************
This file is licensed under a Creative Commons Attribution-Noncommercial-No Derivative Works 3.0 Unported License *
License URL: http://creativecommons.org/licenses/by-nc-nd/3.0/                                                    *
*******************************************************************************************************************
EOD;

    $coll->deleteMany(['corpus'=>$corpus]);
    foreach ($entries as $id=>$entry){
        $article = $entry;
        $entry = preg_replace('/&lt;&lt;([^|]+)\|([^&]+)&gt;&gt;/u','<a href="#$1">$2</a>',$entry);
        $entry = explode('※',$entry)[0];
        $entry = preg_replace('/ *\[\[[^]]+\]\]/','',$entry);

        $entry = trim($entry);
        if(preg_match("/〔{$id}〕/u",$entry)){
            $output .= "\n\n{$entry}";
        }
        else{
            $output .= "\n\n{$id}\n{$entry}";
        }

        $coll->insertOne([
            'corpus'=>$corpus,
            'code'=>$id,
            'article'=>$entry,
            'tokens'=>extractTokens($article,$corpus)
        ]);
        print("$id inserted \n");
    }

    file_put_contents($conf['output_file'], "\xFF\xFE".iconv('UTF-8', 'UTF-16LE',$output));
    print ("File {$conf['output_file']} created\n");

    $zip = new ZipArchive();
    if ($zip->open($conf['output_arch'],ZipArchive::CREATE) === TRUE) {
        $zip->addFile($conf['output_file'], pathinfo($conf['output_file'])['basename']);
        $zip->close();
        print ("Archive file {$conf['output_arch']} created\n");

        unlink($conf['output_file']);
        print ("File {$conf['output_file']} removed.\n");
    } else {
        print ("Cannot create archive file {$conf['output_arch']}\n");
    }

    print("Done.\n");
}

//------------------Functions-------------------//

function scanCorpDir($path,&$entries){
    if ($handle = opendir($path)) {
        while (false !== ($entry = readdir($handle))) {            
            if(is_file("{$path}/{$entry}") && preg_match('/^[0-9A-]+\.txt$/', $entry)){
                $code = explode('.',$entry)[0];
                $entries[$code] =  trim(file_get_contents("{$path}/{$entry}"));
            }
            elseif(preg_match('/^[0-9A-]$/', $entry)) {
                scanCorpDir("{$path}/{$entry}",$entries);
            }
        }
        closedir($handle);
    }
    else{
        print('Невозможно открыть '.$path.'. Процедура генерации прервана.');
        exit(0);
    }
}

function sortByKana($a, $b){
    return sortCard($a, $b, 'kana');
}

function sortByKiriji($a, $b){
    return sortCard($a, $b, 'kiriji');
}

function sortByHeader($a, $b){
    $a = explode("\n", $a)[0];
    $b = explode("\n", $b)[0];

    return ($a < $b) ? -1 : 1;
}

function sortCard($a, $b, $field='kana'){
    $a = parseHeader(explode("\n", $a)[0])[$field][0];        
    $b = parseHeader(explode("\n", $b)[0])[$field][0];

    return ($a < $b) ? -1 : 1;
}

function extractTokens($article, $corpus){
    $func = "extractTokens_$corpus";
    return $func($article);
}

function extractTokens_bjrd($article){
    return extractTokens_warodai($article);
}

function extractTokens_warodai($article){
    $strings = explode("\n",$article);
    $tokens = extractWarodaiHeaderTokens(array_shift($strings));

    foreach($strings as $string){
        $match = null;
        if(preg_match('/^• ?(Также|Др\. чтение|Редуц\.|Вариант слова)(.+)$/u',$string,$match)){
            $tokens = array_merge($tokens, extractWarodaiExtraHeaderTokens($match[2]));
        }
        else{
            $tokens = array_merge($tokens,extractWarodaiRussianTokens($string));
        }
    }
    return $tokens;
}

function extractTokens_zrjiten($article){
    $strings = explode("\n",$article);
    $tokens = explode(', ', $strings[0]);
    for($i=0; $i < count($tokens); $i++){
        $tokens[$i] = trim(
            strip_tags(
                preg_replace('/[IV]+/','',$tokens[$i])
            )
        );
    }
    return $tokens;
}

function extractWarodaiHeaderTokens($headerString){
    $tokens = [];

    $header = parseHeader($headerString);
    foreach(['kana', 'hyouki', 'kiriji'] as $section){
        $tokens = array_merge($tokens, $header[$section]);
    }

    return $tokens;
}

function extractWarodaiExtraHeaderTokens($addition){
    $tokens = [];

    //Парсим комментарий по дополнительному написанию
    $headerAdditionReg = '/([\x{3040}-\x{309F}\x{30A0}-\x{30FF}\x{31f0}-\x{31ff}…A-Z.,･！ ]+)?(【([^】]+)】)?/u';

    if(preg_match($headerAdditionReg,$addition,$match)){
        if(count($match)>1){
            $match[1] = trim($match[1]);
            if(!empty($match[1])){
                //Если есть кана
                $tokens = array_merge($tokens, normalizeKana(explode(",",$match[1])));
            }
            if(!empty($match[3])){
                $tokens = array_merge($tokens, normalizeHyouki(explode(",",$match[3])));
            }
        }
    }
    return $tokens;
}

function extractWarodaiRussianTokens($string){
    $tokens = [];
    $jpReg = '/^[•◇…\x{3041}-\x{309f}\x{309b}-\x{309c}\x{30a1}-\x{30ff}\x{30a0}\x{ffee}\x{3005}\x{3006}\x{ff01}\x{4e00}-\x{9fbf}\x{3400}-\x{4dbf}\x{f900}-\x{faff}]/u';

    if(!preg_match($jpReg,$string)){
        $string = trim($string);
        $string = preg_replace('/(\([^)]+\))/u','',$string);
        $string = preg_replace('/(\[[^]]+\])/u','',$string);
        $string = preg_replace('/<i>\s?ср.\s?<\/i>[^.;]+[.;]/u','',$string);
        $string = str_replace('<i></i>','',$string);
        $string = preg_replace('/<i>([^<]+)<\/i>/u','',$string);
        $string = str_replace('<i>','',$string);
        $string = preg_replace('/&lt;&lt;([^&]+)&gt;&gt;/u','',$string);
        $string = str_replace('<\/i>','',$string);
        $string = preg_replace('/[^ а-яА-ЯёЁ;,-]/u','',$string);
        $string = preg_replace('/\s+/u',' ',$string);
        $string = trim($string);

        if(!empty($string)){
            $rawTokens = preg_split('/[;,]/',$string);
            foreach($rawTokens as $token){
                $token = trim($token);
                if(!empty($token)){
                    $tokens[] = $token;
                }
            }
        }
    }
    return $tokens;
}

function parseHeader($headerString){
    //Структура заголовка статьи
    $header = [
        'kana'=>[],           //неразобранный массив написаний каной
        'hyouki'=>[],         //неразобранный массив написаний хё:ки
        'kiriji'=>[],         //неразобранный массив написаний киридзи
    ];

    //Разбираем заголовок с помощью вот такого регулярного выражения
    $headerReg = '/^ *(([\x{3040}-\x{309F}\x{30A0}-\x{30FF}\x{31f0}-\x{31ff}…A-Z.,･！ ]+)(【([^】]+)】)? ?\(([а-яА-ЯЁёйў*,…:\[\] \x{0306}-]+)\)) *(\[([^]]+)\])?(〔([^〕]+)〕)?/u';

    //Заполняем структуру данных заголовка статьи
    if(preg_match($headerReg,$headerString,$match)){
        $header['kana'] = normalizeKana(explode(",", $match[2]));
        $header['hyouki'] = (empty($match[4])) ? [] : normalizeHyouki(explode(",", $match[4]));
        $header['kiriji'] = normalizeKiriji(explode(",",$match[5]));    
    }
    else{
        //Заголовок не подошел под регулярное выражение.
        throw new Exception('Article has malformed header');
    }

    return $header;
}

function normalizeKana($kana){
    $rKana = [];

    foreach($kana as $k){
        $k = trim($k);
        $k = preg_replace('/([^A-Za-z])[IV]+$/','$1',$k);
        $k = str_replace(['…','!','.'],'',$k);

        $k = explode('･',$k);
        $rKana = array_merge($rKana,$k);
    }
    return $rKana;
}

function normalizeHyouki($hyouki){
    return normalizeKana($hyouki);
}

function normalizeKiriji($kiriji){
    $rKiriji = [];

    foreach($kiriji as $k){
        $k = trim($k);
        $k = str_replace(['-','…'], '',$k);
        $k = str_replace('ў','у',$k);

        $k = explode('･',$k);
        $rKiriji = array_merge($rKiriji,$k);
    }
    return $rKiriji;
}
