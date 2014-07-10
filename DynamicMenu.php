#!/usr/bin/php -q
<?php
set_time_limit(30);
require_once __DIR__.DIRECTORY_SEPARATOR.'lib/phpagi.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'lib/Appel.php';


$starttime = time();
//$starttime = date("Y-m-d H:i:s", time());
$agi = new AGI();
$appel = new Appel($agi);


$id = $appel->cdr_Insert();

$cid = $agi->parse_callerid();
$agi->conlog("GetInfo: {$cid['username']}");
$welcommessage = 'Bienvenue chez platforme de paiement de Sipcom ';
if(!empty($cid['username']))
	$welcommessage = ' Monsieur '.$cid['username'].$welcommessage;
$agi->text2wavFR($welcommessage, "#");

$agi->stream_file("enter-password","#");
/*
 $choices=array('1'=>'*press 1 for sales', // festival reads if prompt starts with *
 '2'=>'*press 2 for billing',
 '3'=>'*Press 3 for support');
 $keys=$agi->menu($choices,5000);
 $agi->conlog("Get choice: {$keys}");
 switch ($keys){
 case '1':
 $agi->text2wav('Welcom to sales');
 break;
 case '2':
 $agi->text2wav('Welcom to billing');
 break;
 case '3':
 $agi->text2wav('Welcom to support');
 break;
 default:
 $agi->text2wav('Do not get right choice, please try again');
 break;
 }
 */
$appel->startMenu();

$agi->text2wavFR('Au revoir');
$agi->hangup();

$endtime = time();
//$endtime = date("Y-m-d H:i:s", time());
$duration = $endtime-$starttime;
$agi->conlog("Temps de réponce:{$duration}");
$appel->cdr_Update($starttime, $endtime, $id);
?>
