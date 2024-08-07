<!DOCTYPE html>
<?php
    include('common_lib.php');
    date_default_timezone_set('UTC');
?>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="content-language" content="ru-ru"/>
    <meta http-equiv="imagetoolbar" content="no"/>
    <meta name="distribution" content="global"/>
    <meta name="copyright" content="2008-<?=date('Y')?> Warodai"/>
    <meta name="keywords" content="японско-русский электронный словарь"/>
    <meta name="description" content="японско-русский электронный словарь"/>
    <title>Японско-русский электронный словарь Warodai</title>

    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon.png">

    <link rel="stylesheet" href="/vendor/bootstrap-4.4.1-dist/css/bootstrap.min.css">
    <link href='/vendor/fontawesome-free-5.15.4-web/css/all.min.css' rel='stylesheet' type='text/css'>

    <link href='/_asset/css/style.css?v=3' rel='stylesheet' type='text/css'>

    <script src="/vendor/jquery/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="/vendor/popper/popper.min.js"></script>
    <script src="/vendor/bootstrap-4.4.1-dist/js/bootstrap.min.js"></script>
    
    <script src="/_asset/js/config.js.php?v=4" crossorigin="anonymous"></script>
    <script src="/_asset/js/auth.js?v=4" crossorigin="anonymous"></script>
    <script src="/_asset/js/lookup.js?v=4" crossorigin="anonymous"></script>
    <script src="/_asset/js/diff.js" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top">            
            <a class="navbar-brand" href="/"><img src="/_asset/img/warodai-logo-heiwa.png" style="height:38px"></a>
            
            <div class="collapse navbar-collapse mr-auto" id="navbarCollapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-match="lookup" href="/lookup/">Японско-русский словарь</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-match="download" href="/download/">Скачать</a>
                    </li>                    
                    <li class="nav-item">
                        <a class="nav-link" data-match="none" href="https://github.com/WarodaiProject/warodai-source/discussions">Форум</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" data-match="contrib">Участие</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="/contrib/">Условия участия</a>
                            <a class="dropdown-item" href="https://github.com/WarodaiProject/warodai-source">Репозиторий</a>
                            <a class="dropdown-item" href="https://github.com/WarodaiProject/warodai-source/issues">Тикеты</a>
                            <a class="dropdown-item auth-only" href="/editor/">Редактор WARODAI</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" data-match="(about|help)" href="#">О словаре</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="/about/">О проекте</a>
                            <a class="dropdown-item" href="/help/">Помощь</a>
                            <a class="dropdown-item" href="/about/readme/">README</a>
                        </div>
                    </li>
                </ul>
            </div>

            <div id="user-pane" class="ml-auto">
                <button class="btn btn-sm btn-secondary start-signin" onclick="startSignIn()">Войти</button>
                <div class="btn-group user-spot">
                    <div class="avatar" data-toggle="dropdown" data-display="static"></div>
                    <div class="signout dropdown-menu dropdown-menu-right">
                        <button class="dropdown-item" type="button" onclick="signout()">Выйти</button>
                    </div>
                </div>
            </div>
            
            <div data-toggle="collapse" class="d-md-none menu-toggler"
                data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
            >
                <i class="fas fa-bars"></i>
            </div>
        </nav>
    </header>

    <div class="modal fade" id="signinModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-body">
                    <div class="mt-3">
                        <div class="d-flex flex-column flex-md-row">
                            <div class="text-center mb-4">
                                <i class="fab fa-github" style="font-size: 6em"></i>
                            </div>
                            <div class="ml-3 small">
                                Работа над словарём ведётся в репозитории на платформе Github.
                                Для того, чтобы принять участие в проекте, вы должны иметь учётную запись на этой платформе.<br/>
                                После нажатия на кнопку «Войти» вы сможете зарегистрироваться в Github или, если у вас уже есть учётная запись,
                                авторизоваться на сайте с помощью неё.
                            </div>
                        </div>
                        <div class="mt-2 text-center">
                            <button class="btn btn-primary signin" onclick="signin()">Войти</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">