#!/usr/bin/php -q
<?php
include("appel.agi");
set_time_limit(30);
$start_time=time();
$appel = new AGI();
$appel->verbose($start_time);
$callerid=$appel-> request['agi_callerid'];
$appel->say_time();
$cid = $appel->parse_callerid();
//$appel->text2wav("Sipcom bonjour");
$appel->text2wav("Hello, {$cid['name']}.");
do{
	$appel->text2wav('pound key to end a number 1 1 1 to quit.');
	$result = $appel->get_data('beep', 3000, 20);
	$keys = $result['result'];
	$appel->text2wav("You entered $keys");
} while($keys != '111');
$appel->text2wav('Goodbye');
$appel->answer();
$res=$appel->exec_dial("SIP","0123456780");
$appel->hangup();
/*
 do{
 $res=$appel->read_result();
 $appel->verbose($res['result']);
 }while($res['result'] != -1);
 $appel->verbose($res['result']);
 */
$end_time=time();
$appel->verbose($end_time);
$duration=$end_time-$start_time;
$appel->verbose($duration);
?>
