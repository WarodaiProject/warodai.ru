<?php
// Пример конфигурационного файла
// Необходимо создать копию этого файла с именем config.php в этой же папке
// и заполнить поля с токенами и секретами

$_CONF = [
    'mongo_url' => "mongodb://localhost:27017",
    
    'gitlab_private_token' => '',
    'gitlab_api_root' => 'https://gitlab.warodai.ru/api/v4',
    'gitlab_bjrd_source' => 'warodai/warodai-source',
    'gitlab_access_token_endpoint' => 'https://gitlab.warodai.ru/oauth/token',
    'gitlab_client_secret' => '',
    
    'github_private_token' => '',
    'github_api_root' => 'https://api.github.com',
    'github_bjrd_source' => 'warodai/bjrd-source',
    'github_access_token_endpoint' => 'https://github.com/login/oauth/access_token',
    'github_client_secret' => '',
    
    'corpus_local_repos' => [
        'warodai'=>dirname(__FILE__).'/../repos/bjrd-source.git',
        'zrjiten'=>dirname(__FILE__).'/../repos/zrjiten-source.git'
    ]
];