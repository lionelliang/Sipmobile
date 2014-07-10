#!/usr/bin/php -q
<?php
set_time_limit(30);
require_once __DIR__.DIRECTORY_SEPARATOR.'lib/phpagi.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'lib/Appel.php';

$agi = new AGI();
$appel = new Appel($agi);

$starttime = $agi->get_variable("starttime", true);
$id = $agi->get_variable("id", true);

//$agi->exec('Dial',"SIP/00918885268942 at sip.trunk.gradwell.com,60,r");
$answeredtime = $agi->get_variable("answeredtime", true);
$agi->conlog("answeredtime ".$answeredtime);
$hangupcause = $agi->get_variable("HANGUPCAUSE", true);
$agi->conlog("HANGUPCAUSE ".$hangupcause);
$dst = $agi->get_variable("Destination", true);
$agi->conlog("Destination ".$dst);

$endtime = time();
$reponceduration = $endtime-$starttime;
$agi->conlog("Durée de la réponce :{$reponceduration}");

$appel->cdr_Update($hangupcause, $dst, $starttime, $endtime, $id);

?>
