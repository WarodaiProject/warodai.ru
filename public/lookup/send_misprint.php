<?php
	
	mb_internal_encoding("UTF-8");
	mysql_connect("localhost", "root", "r0nBKshnxu");
	mysql_query("USE warodai");
	mysql_query("set names 'utf8'");
	
	
if($_POST['range'] && $_POST['comments']){
    $article =  '<b>Статья:</b><br/>'.str_replace('<b>','<b style="color:red">',str_replace('<B>','<b>',$_POST['range'])).'<hr/>';
	$comments = '<b>Комментарии:</b><br/>'.$_POST['comments'].'<BR>'.$_SERVER['REMOTE_ADDR'];
	$date = date('Y-m-d H:i:s');
	$ms_article = mysql_real_escape_string($article);
	$ms_comments = mysql_real_escape_string($comments);
	$rs = mysql_query('INSERT INTO warodai_errors SET article=\''.$ms_article.'\', comment=\''.$ms_comments.'\', date=\''.$date.'\'');
	
	if(!$rs){
		print('Произошла ошибка при отправке отчета. Попробуйте повторить отправку еще раз.');  
	}
	else{	
		$from 		 = 'Warodai Mail Robot'; 
		$subject	 = 'Отчет об опечатке в WARODAI'; 
		
		$headers	 = "MIME-Version: 1.0\r\n";
		$headers	.= "Content-type: text/html; charset=utf-8\r\n";
		$headers	.= "From:  ".$from." <nobody@warodai.ru>\n";
		
		$message  = $article;
		$message .= $comments;
		
		mail('nakendlom@gmail.com', $subject, $message, $headers);
		
		print('Ok');        
    }    
}
else{
   print('Вы отправили пустой отчет.');   
}

?>