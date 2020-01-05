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

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.min.css' rel='stylesheet' type='text/css'>

    <link href='/_asset/css/style.css?v=3' rel='stylesheet' type='text/css'>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script src="/_asset/js/lookup.js?v=3" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top">            
            <a class="navbar-brand" href="/"><img src="/_asset/img/site_logo.gif" style="height:38px"></a>
            
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
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
            
            <div data-toggle="collapse" class="d-md-none menu-toggler"
                data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
            >
            <i class="fas fa-bars"></i>
        </div>
        </nav>
    </header>

    <div class="content">
        <div class="container">