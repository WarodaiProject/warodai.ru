<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 'Off');
ini_set("log_errors", 1);
require '../../../../../vendor/autoload.php';
require '../../../../../etc/config.php';

$response = [];
$responseStatus = '200 Ok';


if(!empty($_POST['title']) && !empty($_POST['body'])){    
    // Отправка issue в Github

    $issue = [        
        'title'=>$_POST['title'],
        'body'=>$_POST['body']
    ];
    if(isset($_POST['labels'])){
        if(!is_array($_POST['labels'])){
            $_POST['labels'] = [$_POST['labels']];
        }
        $issue['labels'] = $_POST['labels'];
    }
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
    $response = ['message'=>'Отсутствует редакция или обоснования к ней.'];
    $responseStatus = '400 Bad Request';  
}

header('Status: '.$responseStatus);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
header('Content-Type: application/json');
print(json_encode($response));