<?php
require '../../../../../etc/config.php';

//Установка локали - иначе неверно обрабатываются символы с регулярках в grep
$locale='C.UTF-8';
setlocale(LC_ALL,$locale);
putenv('LC_ALL='.$locale);

$repos = $_CONF['corpus_local_repos'];

$vowels = ['а', 'о', 'и', 'е', 'ё', 'э', 'ы', 'у', 'ю', 'я'];

$response = [];
$responseStatus = '200 Ok';

$keyword = $_GET['keyword'];
$keywordReg = str_replace($vowels,array_map(function($a){return "(<b>)?$a(</b>)?";},$vowels),$keyword);
$keywordReg = '(^|[ ,()･【】])'.$keywordReg.'($|[ ,()･【】])';

if (!$keyword) {
    $response = ['message'=>'Вы не указали параметр keyword'];
    $responseStatus = '400 Bad Request';
}
else{
    if($_GET['corpus']){
        if($repos[$_GET['corpus']]){
            $response = searchRepo($_GET['corpus'], $repos[$_GET['corpus']], $keyword, $keywordReg);
        }
        else{
            $response = ['message'=>'Корпус '.$_GET['corpus'].' не найден.'];
            $responseStatus = '400 Bad Request';
        }
    }
    else{
        foreach($repos as $corpus => $repo){
            $response = array_merge($response, searchRepo($corpus, $repo, $keyword, $keywordReg));
        }
    }
}

function searchRepo($corpus, $repo, $keyword, $keywordReg){
    $results = [];
    $repoPath = $repo['git'];
    if(preg_match('/^[A0-9-]+$/',$keyword)){
        $gitCommand = "git --git-dir {$repoPath}/.git grep -n '' HEAD -- */{$keyword}.txt | grep txt:1:";
    }
    else{            
        $gitCommand = "git --git-dir {$repoPath}/.git grep -En '{$keywordReg}' HEAD | grep txt:1:";
    }

    $rawResults = [];
    exec($gitCommand,$rawResults);
    
    foreach($rawResults as $rawResult){
        if(preg_match('/HEAD:[A0-9\/-]+\/([A0-9-]+).txt:1:(.+)/',$rawResult,$matches)){
            $results[] = [
                'code'=>$matches[1],
                'header'=>strip_tags($matches[2]),
                'corpus'=>$corpus
            ];
        }
    }

    return $results;
}

header('Status: '.$responseStatus);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
header('Content-Type: application/json');
print(json_encode($response,JSON_UNESCAPED_UNICODE));