<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 'Off');
ini_set("log_errors", 1);
require '../../../../../vendor/autoload.php';

$response = [];
$responseStatus = '200 Ok';


if($_POST['range'] && $_POST['comment']){
    // Сохранение в базу данных
    $mongo = new MongoDB\Client("mongodb://localhost:27017");
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
        'private_token'=>'fRuVjP86yCYmB5ntzpzA',
        'title'=>$title,
        'description'=>$article.$comment
    ];
    $ch = curl_init( 'https://gitlab.warodai.ru/api/v4/projects/warodai%2Fwarodai-source/issues' );    
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($issue) );  

    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_HEADER, true );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

    $response = curl_exec( $ch );
    $status = curl_getinfo( $ch ); 
    $header = trim(substr($response, 0, $status['header_size']));
    $body = substr($response, $status['header_size']);

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
print(json_encode($response,JSON_UNESCAPED_UNICODE));