<?php
    require '../../../../../etc/config.php';

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Max-Age: 1000');

    $ch = curl_init($_CONF['github_access_token_endpoint']);

    $query = $_POST;

    if($_GET['app']){
        $app = $_GET['app'];
    }
    else{
        $app = 'production';
    }

    $query['client_secret'] = $_CONF['github_client_secrets'][$app];

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');  
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));

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

    curl_close($ch);
    print($body);
?>