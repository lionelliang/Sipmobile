#!/usr/bin/php -q
<?php
set_time_limit(30);
require_once __DIR__.DIRECTORY_SEPARATOR.'lib/phpagi.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'lib/Appel.php';

$agi = new AGI();
$appel = new Appel($agi);

$agi->conlog("start to log fax info");

$appel->fax_log();	//log fax
$id = $agi->get_variable('idfaxrecord', true);
$status = $agi->get_variable('FAXOPT(status)', true);
if( $status == "SUCCESS" && is_numeric($id)){
	$appel->fax_Update($id);
	$agi->conlog("update: ".$id." issent");
}elseif ( $status == "FAILED" && is_numeric($id) ){
	$appel->faxFailed_Update($id);
	$agi->conlog("update: ".$id." errorinfo");
}

$file = $agi->get_variable('FAXOPT(filename)', true);
$agi->conlog("sentFAX: ".$file);
$tatolfax = $agi->get_variable('FAXCOUNT', true);
$agi->conlog("TatolFax: ".$tatolfax);

?>
