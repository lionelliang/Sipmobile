#!/usr/bin/php -q
<?php
set_time_limit(30);
require_once __DIR__.DIRECTORY_SEPARATOR.'lib/phpagi.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'lib/Appel.php';

$starttime = time();
$agi = new AGI();
$appel = new Appel($agi);

$agi->set_variable("starttime", $starttime);

$agi->text2wavFR("Bienvenue ");
	$destination = $appel->getDestinationFR("Entrer numéro de conférence, tapez # pour terminer");
	$agi->set_variable("Destination", $destination);		//3345,3346,pwd=123546
	$agi->text2wavFR("Joignez à ".$destination);
	$agi->conlog("Destination: $destination");
?>
