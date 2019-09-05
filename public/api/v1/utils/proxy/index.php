<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');

$url = $_GET['url'];

if (!$url) {  
  // Passed url not specified.
  print('ERROR: url not specified');
} 
else {
  $ch = curl_init( $url );
  
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST,$_SERVER['REQUEST_METHOD']);  
  
  if(strtolower($_SERVER['REQUEST_METHOD'])=='post' || strtolower($_SERVER['REQUEST_METHOD'])=='put'){
    curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($_POST) );
  }
    
  if ( $_GET['send_cookies'] ) {
    $cookie = array();
    foreach ( $_COOKIE as $key => $value ) {
      $cookie[] = $key . '=' . $value;
    }
    $cookie = implode( '; ', $cookie );
    
    curl_setopt( $ch, CURLOPT_COOKIE, $cookie );
  }
  
  curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
  curl_setopt( $ch, CURLOPT_HEADER, true );
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
  
  curl_setopt( $ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] );
  
  $response = curl_exec( $ch );
  $status = curl_getinfo( $ch ); 
  $header = trim(substr($response, 0, $status['header_size']));
  $body = substr($response, $status['header_size']);
  
  curl_close( $ch );

  // Split header text into an array.
  $header_text = explode("\r\n", $header);
    
  // Propagate headers to response.
  foreach ( $header_text as $header ) {
    if ( !preg_match('/^(Content-Length):/i', $header) ) {
      header( $header );
    }
  }
      
  if(isset($_GET['callback'])){
      header('Content-type: application/x-javascript');
      print "{$_GET['callback']}($body)";
  }
  else{
      print($body);
  }
}
?>