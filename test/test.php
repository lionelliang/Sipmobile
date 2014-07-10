#!/usr/bin/php -q
<?php
set_time_limit(30);
require_once __DIR__.DIRECTORY_SEPARATOR.'../lib/phpagi.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'../lib/Appel.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'../lib/phpagi-asmanager.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'../lib/Variables.php';
$start_time=time();
$calldate = date("Y-m-d H:i:s", time());
$agi = new AGI();
$agi->conlog("start");
$db = new DB($agi);
$db->fax_Update($calldate, Variables::$strMyIp, '1', '9');
//$agi->text2wav("hello, welcom to sipcom");
//$agi->text2wavFR("Bonjour, Bienvenu chez sipcom", '#', true);
// $conmssql = mssql_connect(Variables::$strMicrosoftsqlIp, Variables::$strMicrosoftsqlUser, Variables::$strMicrosoftsqlPassword);
// if(!$conmssql) {
// 	die('Erreur de connexion à MSSQL');
// }
// mssql_select_db(Variables::$strSipcomweb, $conmssql);
// $query= "SELECT * FROM fax";
// $rs= mssql_query($query, $conmssql);
// $agi->conlog("The field number one is: ".mssql_result ($rs, 0, 0));
// mssql_close($conmssql);

$agi->conlog("end");
$agi->hangup();
?>