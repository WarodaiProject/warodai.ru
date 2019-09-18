<?php

//Установка локали - иначе неверно обрабатываются символы с регулярках в grep
$locale='C.UTF-8';
setlocale(LC_ALL,$locale);
putenv('LC_ALL='.$locale);

$repos = [
    'warodai'=>dirname(__FILE__).'/../../../../../repos/bjrd-source.git',
    'zrjiten'=>dirname(__FILE__).'/../../../../../repos/zrjiten-source.git'
];

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
    $results = [];

    foreach($repos as $corpus => $repoPath){
        if(preg_match('/^[A0-9-]+$/',$keyword)){
            $gitCommand = "git --git-dir {$repoPath} grep -n '' HEAD -- */{$keyword}.txt | grep txt:1:";
        }
        else{            
            $gitCommand = "git --git-dir {$repoPath} grep -En '{$keywordReg}' HEAD | grep txt:1:";
        }

        $rawResults = [];
        exec($gitCommand,$rawResults);
       
        foreach($rawResults as $rawResult){
            if(preg_match('/HEAD:[A0-9-]+\/([A0-9-]+).txt:1:(.+)/',$rawResult,$matches)){
                $results[] = [
                    'code'=>$matches[1],
                    'header'=>strip_tags($matches[2]),
                    'corpus'=>$corpus
                ];
            }
        }
    }

    $response = $results;
}

header('Status: '.$responseStatus);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
header('Content-Type: application/json');
print(json_encode($response,JSON_UNESCAPED_UNICODE));