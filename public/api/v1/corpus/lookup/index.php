<?php
require '../../../../../vendor/autoload.php';

$response = [];
$responseStatus = '200 Ok';

$keyword = $_REQUEST['keyword'];
$bareKeyword = str_replace('*','',$keyword);
if (!$keyword || empty($bareKeyword)) {
    $response = ['message'=>'Вы не указали параметр keyword'];
    $responseStatus = '400 Bad Request';
}
else{
    $mongo = new MongoDB\Client("mongodb://localhost:27017");
    $db = $mongo->warodai;

    $keywordQueries = [];
    if(preg_match('/^[A0-9-]+$/', $keyword)){
        $keywordQueries[] = ["code"=>$keyword];
    }
    else{
        if(preg_match('/\*/', $keyword)){
            $keywordQueries[] = ["tokens"=>new MongoDB\BSON\Regex('^'.str_replace('*','.*',$keyword).'$','i')];
        }
        else{
            $keywordQueries[] = ["tokens"=>$keyword];
        }
        $keywordQueries[] = ["article"=>new MongoDB\BSON\Regex('.*'.str_replace('*','.*',$keyword).'.*','i')];
    }
    $listedCards = [];

    foreach($keywordQueries as $keywordQuery){
        $cursor = $db->corpus->find(
            $keywordQuery,
            [
                'projection' => [
                    'code' => 1,
                    'corpus' => 1,
                    'article' => 1,
                    '_id' => 0
                ],
                'limit'=>100
            ]
        );
        foreach($cursor as $row){
            if(!isset($listedCards[$row['corpus']])){
                $listedCards[$row['corpus']] = [];
            }
            if(isset($listedCards[$row['corpus']][$row['code']])){
                continue;
            }
            $listedCards[$row['corpus']][$row['code']] = $row['code'];
            $response[] = $row;
        }
    }

    //Лог
    $h = fopen('../../../../../logs/' . date("Y-m-d") . '-query-log.txt', 'a');
    fwrite($h, date("Y-m-d\TH:i:s") . ' ' . $_SERVER['REMOTE_ADDR'] . ' ' . $keyword . "\n");
    fclose($h);
}

header('Status: '.$responseStatus);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
header('Content-Type: application/json');
print(json_encode($response,JSON_UNESCAPED_UNICODE));