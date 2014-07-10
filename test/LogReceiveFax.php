#!/usr/bin/php -q
<?php
set_time_limit(30);
require_once __DIR__.DIRECTORY_SEPARATOR.'lib/phpagi.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'lib/Appel.php';

$agi = new AGI();
$appel = new Appel($agi);

$agi->conlog("start to log fax info");

$appel->fax_log();	//log fax

$file = $agi->get_variable('FAXOPT(filename)', true);
$agi->conlog("ReceiveFAX: ".$file);
$tatolfax = $agi->get_variable('FAXCOUNT', true);
$agi->conlog("TatolFax: ".$tatolfax);

/*
//$filedir = '/var/www/sipcom/data/fax/+33143112246/';
//$filedir = "/var/lib/asterisk/agi-bin/Sipmobile/fax/";
//$file = "fax-".time()."-rx.tiff";
//$fullfile = $filedir.$file;
//$agi->exec('ReceiveFAX', $fullfile);

$agi->set_variable('FAXOPT(ecm)', 'yes');
$agi->set_variable('FAXOPT(headerinfo)', 'Received on ' . $timeStamp->format('Y-m-d\TH:i:sP'));
$agi->set_variable('FAXOPT(localstationid)', 'fax num removed');
$agi->set_variable('FAXOPT(maxrate)', '14400');
$agi->set_variable('FAXOPT(minrate)', '2400');

$agi->conlog($agi->get_variable('FAXOPT(ecm)', true));
$agi->conlog($agi->get_variable('FAXOPT(filename)', true));
$agi->conlog($agi->get_variable('FAXOPT(headerinfo)', true));
$agi->conlog($agi->get_variable('FAXOPT(localstationid)', true));
$agi->conlog($agi->get_variable('FAXOPT(maxrate)', true));
$agi->conlog($agi->get_variable('FAXOPT(minrate)', true));
$agi->conlog($agi->get_variable('FAXOPT(pages)', true));
$agi->conlog($agi->get_variable('FAXOPT(rate)', true));
$agi->conlog($agi->get_variable('FAXOPT(remotestationid)', true));
$agi->conlog($agi->get_variable('FAXOPT(resolution)', true));
$agi->conlog($agi->get_variable('FAXOPT(status)', true));
$agi->conlog($agi->get_variable('FAXOPT(statusstr)', true));
$agi->conlog($agi->get_variable('FAXOPT(error)', true));
*/
?>
