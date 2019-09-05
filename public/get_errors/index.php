<?php
header('Content-Type: text/xml');
mb_internal_encoding("UTF-8");
mysql_connect("localhost", "root", "r0nBKshnxu");
mysql_query("USE warodai");
mysql_query("set names 'utf8'");

$sql = 'SELECT * FROM warodai_errors';
if($_GET['start']){
    $sql .= ' WHERE date>=\''.$_GET['start'].'\'';
}

$rs = mysql_query($sql);


print("<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n<errors>");
while ($row = mysql_fetch_assoc($rs)) {
    print('<error date="'.$row['date'].'" id="'.$row['id'].'">');
    print('<article><![CDATA['.$row['article'].']]></article>');
    print('<comment><![CDATA['.$row['comment'].']]></comment>');
    print('</error>');
}
print('</errors>')
?>