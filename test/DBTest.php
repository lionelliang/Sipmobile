#!/usr/bin/php -q
<?php
set_time_limit(30);
require_once __DIR__.DIRECTORY_SEPARATOR.'../lib/DB.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'../lib/phpagi.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'../lib/Appel.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'../lib/phpagi-asmanager.php';

$start_time=time();
$agi = new AGI();
$agimanager = new AGI_AsteriskManager();
//$appel = new Appel();
$db = new DB($agi);
$rets = $db->user_Select();
while($row = mysql_fetch_array($rets)){
	$agi->conlog($row['id'] . " " . $row['fk_callerid']);
}
$agi->text2wav('Goodbye');
$end_time=time();
$agi->conlog($end_time-$start_time);
$agi->hangup();
?>