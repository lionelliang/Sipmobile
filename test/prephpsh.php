<?php
require_once __DIR__.DIRECTORY_SEPARATOR.'../lib/DB.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'../lib/phpagi.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'../lib/Appel.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'../lib/Variables.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'../lib/phpagi-asmanager.php';

$start_time=time();
$calldate = date("Y-m-d H:i:s", time());
$agi = "";//new AGI();
$appel = new Appel($agi);

$appel->fax_Update("29");