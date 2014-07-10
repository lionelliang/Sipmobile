#!/usr/bin/php -q
<?php
set_time_limit(30);
require_once __DIR__.DIRECTORY_SEPARATOR.'lib/phpagi.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'lib/Appel.php';

$agi = new AGI();
$appel = new Appel($agi);

$dialstatus = $agi->get_variable("DIALSTATUS", true);
$agi->conlog("Dialstatus: ${$dialstatus}");
$appel->statusReponse($dialstatus);