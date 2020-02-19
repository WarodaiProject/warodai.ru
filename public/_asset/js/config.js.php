<?php
    // Это javascript-файл, в котором публикуются настройки для клиентской части
    
    header('Content-Type: application/javascript');

    include('../../../etc/config.php');

    $opts = [
        'github_access_token_proxy', 
        'github_oauth_point',
        'github_api_root',
        'github_bjrd_source',
        'github_access_token_proxy',
        'github_client_ids'
    ];

    $publish_opts = array_combine(
        $opts,
        array_map( 
            function($k){
                global $_CONF;
                return $_CONF[$k];
            },
            $opts
        )
    );
?>
var CONF = <?=json_encode($publish_opts)?>