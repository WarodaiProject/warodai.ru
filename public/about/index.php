<?php 
    include('../_inc/header.php');
    
    $text = md2html('https://raw.githubusercontent.com/WarodaiProject/warodai-source/master/README.md');

    $matches = [];
    preg_match("/<h1>1\. [^<]+<\/h1>([\s\S]+)<h1>2\. /m", $text, $matches);
?>

    <?php include('../_inc/about_submenu.php') ?>

    <h1>О проекте</h1>
    
    <div class="alert alert-primary" role="alert">
        Полную версию описания см. в <a href="/about/readme/">README</a>.
    </div>
    
    <?=$matches[1]?>

<?php include('../_inc/footer.php') ?>
