<?php
// Пример конфигурационного файла
// Необходимо создать копию этого файла с именем config.php в этой же папке
// и заполнить поля с токенами и секретами

$_CONF = [
    'mongo_url' => "mongodb://localhost:27017",

    'github_oauth_point' => 'https://github.com/login/oauth/authorize',
    'github_private_token' => '',
    'github_api_root' => 'https://api.github.com',
    'github_bjrd_source' => 'warodai/warodai-source',
    'github_access_token_proxy'=> 'https://warodai.ru/api/v1/utils/github_auth_token/',
    'github_access_token_endpoint' => 'https://github.com/login/oauth/access_token',
    'github_client_ids' => [
        'warodai.ru' => ''
    ],
    'github_client_secrets' => [
        'warodai.ru' => ''
    ],

    'corpus_local_repos' => [
        'bjrd'=>[
            'git'=>dirname(__FILE__).'/../repos/bjrd-source',
            'build'=>true,
            'sort'=>'code',
            'output_file'=>dirname(__FILE__).'/../public/download/bjrd.txt',
            'output_arch'=>dirname(__FILE__).'/../public/download/bjrd_txt.zip'
        ],
        'warodai'=>[
            'git'=>dirname(__FILE__).'/../repos/warodai-source',
            'build'=>true,
            'sort'=>'kana',
            'output_file'=>dirname(__FILE__).'/../public/download/warodai.txt',
            'output_arch'=>dirname(__FILE__).'/../public/download/warodai_txt.zip'
        ],
        'zrjiten'=>[
            'git'=>dirname(__FILE__).'/../repos/zrjiten-source',
            'build'=>true,
            'sort'=>'code',
            'output_file'=>dirname(__FILE__).'/../public/download/zrjiten.txt',
            'output_arch'=>dirname(__FILE__).'/../public/download/zrjiten_txt.zip'
        ]
    ]
];