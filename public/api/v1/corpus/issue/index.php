<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 'Off');
ini_set("log_errors", 1);
require '../../../../../vendor/autoload.php';
require '../../../../../etc/config.php';

$response = [];
$responseStatus = '200 Ok';


if($_POST['range'] && $_POST['comment']){
    // Сохранение в базу данных
    $mongo = new MongoDB\Client($_CONF['mongo_url']);
    $db = $mongo->warodai;
    
    $article = '<b>Статья:</b><br/>'.str_replace('<b>','<b style="color:red">',str_replace('<B>','<b>',$_POST['range'])).'<hr/>';
    $comment = '<b>Комментарии:</b><br/>'.$_POST['comment'];
    $date = date('Y-m-d H:i:s');

    $db->issues->insertOne([
        'article'=>$article,
        'comment'=>$comment,
        'date'=>$date
    ]);
    
    // Отправка issue в Gitlab
    $title = explode("\n",trim($_POST['range']))[0];
    $title = str_replace('<b>','',$title);
    $title = str_replace('</b>','',$title);
    $issue = [        
        'title'=>$title,
        'body'=>$article.$comment,
        'labels'=>['Комментарий из warodai.ru']
    ];
    $ch = curl_init($_CONF['github_api_root'].'/repos/'.$_CONF['github_bjrd_source'].'/issues');    
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($issue) ); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: token '.$_CONF['github_private_token'],
        'User-Agent: warodai',
        'Content-Type: application/json'  
    )); 

    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_HEADER, true );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

    $response = curl_exec( $ch );
    $status = curl_getinfo( $ch ); 
    $header = trim(substr($response, 0, $status['header_size']));
    $body = substr($response, $status['header_size']);
    
    $response = json_decode($body);
    $responseStatus = $status;
    curl_close( $ch );
}
else{
    $response = ['message'=>'Отсутствует комментарий или выделенный фрагмент с ошибкой.'];
    $responseStatus = '400 Bad Request';  
}

header('Status: '.$responseStatus);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
header('Content-Type: application/json');
print(json_encode($response));