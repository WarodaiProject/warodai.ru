<?php

require_once(dirname(__FILE__).'/../../vendor/autoload.php');


$repos = [
    'warodai'=>[
        'git'=>'/var/opt/gitlab/git-data/repositories/warodai/warodai-source.git',
        'output_file'=>dirname(__FILE__).'/../../public/download/ewarodai.txt',
        'output_arch'=>dirname(__FILE__).'/../../public/download/warodai_txt.zip'
    ]
];

//Установка локали - иначе неверно обрабатываются символы с регулярках в grep
$locale='C.UTF-8';
setlocale(LC_ALL,$locale);
putenv('LC_ALL='.$locale);

$m = new MongoDB\Client("mongodb://localhost:27017");
$coll = $m->warodai->corpus;

foreach($repos as $corpus=>$conf){
    $repoPath = $conf['git'];
    $lastRepoDate = '1970-01-01 00:00:01';
    $lastArchDate = '1970-01-01 00:00:01';
    $entries = [];
    $output = '';
    $e = [];
    exec("git --git-dir {$repoPath} log -n 1", $e);
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
        exit;
    }

    scanGitDir($repoPath,$entries,'HEAD');
    ksort($entries);

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

    print("Done.");
}

//------------------Functions-------------------//

function scanGitDir($repoPath,&$entries,$ref){
    if(empty($ref)){
        $ref = 'HEAD';
    }

    $e = [];
    exec("git --git-dir {$repoPath} ls-tree --name-only -r {$ref}", $e);

    foreach($e as $s){
        $_t = [];
        //print("Extracting {$s} - done\n");
        exec("git --git-dir {$repoPath} show {$ref}:{$s}",$_t);
        $code = explode('.',explode('/',$s)[1])[0];
        $entries[$code] =  join("\n",$_t);
    }
}

function extractTokens($article, $corpus){
    $func = "extractTokens_$corpus";
    return $func($article);
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

function extractWarodaiHeaderTokens($headerString){
    $tokens = [];

    //Разбираем заголовок с помощью вот такого регулярного выражения
    $headerReg = '/^ *(([\x{3040}-\x{309F}\x{30A0}-\x{30FF}\x{31f0}-\x{31ff}…A-Z.,･！ ]+)(【([^】]+)】)? ?\(([а-яА-ЯЁёйў*,…:\[\] \x{0306}-]+)\)) *(\[([^]]+)\])?(〔([^〕]+)〕)?/u';

    if(preg_match($headerReg,$headerString,$match)){
        $tokens = array_merge($tokens,normalizeKana(explode(",", $match[2])));
        $tokens = array_merge($tokens,normalizeKiriji(explode(",",$match[5])));

        if(!empty($match[4])){
            $tokens = array_merge($tokens, normalizeHyouki(explode(",", $match[4])));
        }
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
