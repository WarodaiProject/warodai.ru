<?php

    function md2html($path){
        require_once(__DIR__.'/../../vendor/parsedown/Parsedown.php');
        $text = file_get_contents($path);

        $text = preg_replace_callback(
            '/\n#+([^\n]+)\n/m',
            function($matches){
                $header = trim(mb_strtolower($matches[1]));
                $header = preg_replace('/[^\d\w ]/u', '' , $header);
                $header = preg_replace('/[ -]/u', '-' , $header);
                return "<a class=\"anchor\" name=\"{$header}\"></a>\n" . $matches[0];
            },
            $text
        );

        $Parsedown = new Parsedown();
        $text = $Parsedown->text($text);
        $text = str_replace('<table>', '<table class="table">', $text);
        $text = str_replace('https://github.com/warodai/warodai-source/blob/master/README.md','/about/readme', $text);
        return $text;
    }