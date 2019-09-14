<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');



$ch = curl_init( $_GET['url'] );

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');  
curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($_POST) );

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Accept: application/json'
));

curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
curl_setopt( $ch, CURLOPT_HEADER, true );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

$response = curl_exec( $ch );
$status = curl_getinfo( $ch ); 
$header = trim(substr($response, 0, $status['header_size']));
$body = substr($response, $status['header_size']);

header('');

curl_close( $ch );    
print($body);


?>