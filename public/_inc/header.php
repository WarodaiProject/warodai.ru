<!DOCTYPE html>
<?php
    include('common_lib.php');
    date_default_timezone_set('UTC');
?>
<html lang="ru">
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

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.min.css' rel='stylesheet' type='text/css'>

    <link href='/_asset/css/style.css?v=3' rel='stylesheet' type='text/css'>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    
    <script src="/_asset/js/auth.js?v=3" crossorigin="anonymous"></script>
    <script src="/_asset/js/lookup.js?v=3" crossorigin="anonymous"></script>
    <script src="/_asset/js/diff.js" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top">            
            <a class="navbar-brand" href="/"><img src="/_asset/img/site_logo.png" style="height:38px"></a>
            
            <div class="collapse navbar-collapse mr-auto" id="navbarCollapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-match="lookup" href="/lookup/">Японско-русский словарь</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-match="download" href="/download/">Скачать</a>
                    </li>                    
                    <li class="nav-item">
                        <a class="nav-link" data-match="help" href="/help/">Помощь</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-match="contrib" href="/contrib/">Участие</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-match="about" href="/about/">О словаре</a>
                    </li>
                </ul>
            </div>

            <div id="user-pane" class="ml-auto">
                <button class="btn btn-sm btn-secondary signin">Войти</button>
                <div class="btn-group user-spot">
                    <div class="avatar" data-toggle="dropdown" data-display="static"></div>
                    <div class="signout dropdown-menu dropdown-menu-right">
                        <button class="dropdown-item" type="button">Выйти</button>
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

    <div class="content">
        <div class="container">