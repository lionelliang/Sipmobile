#!/usr/bin/php -q
<?php
set_time_limit(30);
require_once __DIR__.DIRECTORY_SEPARATOR.'lib/phpagi.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'lib/Appel.php';

$agi = new AGI();
$appel = new Appel($agi);

$data = $file = $agi->get_variable('FAXOPT(filename)', true);
$agi->conlog("Envoyer fax: ".$file);

$path_parts = pathinfo($data);
$dir = $path_parts['dirname']."/";
$extension = $path_parts['extension'];
$filename = $path_parts['filename'];

if ($extension == 'tiff' || $extension == 'tif' || $extension == 'TIFF'){
	$outFile = $dir.$filename.".pdf";
	$appel->tiff2Pdf($data, $outFile);
	$data = $outFile;
}

$destNumber = $agi-> request['agi_dnid'];
$agi->conlog("Envoyer fax à ".$destNumber);
if($destNumber == "+33143112245"){
	$toUserMail = "test@sipcom.fr";
}else{
	$toUserMail = "fliguo06@gmail.com";
}
$agi->conlog($appel->emailFax($toUserMail, $data));
