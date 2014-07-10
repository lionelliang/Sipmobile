#!/usr/bin/php -q
<?php
set_time_limit(30);
require_once __DIR__.DIRECTORY_SEPARATOR.'lib/phpagi.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'lib/Appel.php';


$starttime = time();
$agi = new AGI();
$appel = new Appel($agi);

$id = $appel->cdr_Insert();

$cid = $agi->parse_callerid();

//$welcommessage = 'Welcome to sipcom paying platform';
$welcommessage = "Bienvenue chez platforme de paiement de Sipcom ";
if(!isset($cid['username'])){
	$agi->conlog("GetInfo: {$cid['username']}");
	//$welcommessage += "Mr {$cid['username']}"; 
}
$agi->text2wav('Hello', '1');
$agi->text2wav($welcommessage, '1');
$agi->text2wavFR("Boujour", '1');
$agi->text2wavFR($welcommessage, '1');

/*
$choices=array('1'=>'*Pay by credit card press 1', // text2wav reads if prompt starts with * else stream_file
 '2'=>'*Pay by RIB press 2',
 '3'=>'*For support Press 3');
$keys=$agi->menu($choices, 5000);
*/
$choices=array('1'=>'*Payez par carte bancaire, tapez 1', // text2wav reads if prompt starts with * else stream_file
 '2'=>'*Payez par cheque, tapez 2',
 '3'=>'*Pour support de service, tapez 3');
$keys=$agi->menuFR($choices, 5000);

$agi->conlog("Choix: {$keys}");
switch ($keys){
	case '1':
		$agi->text2wavFR('Vous avez choisi de payer par carte bancaire');
		$destination = $appel->getDestinationFR('Saisissez votre numéro de CB, tapez # pour terminer', 20);
		$agi->text2wavFR("vous saisissez $destination");
		break;
	case '2':
		$agi->text2wavFR('vous avez choisi de payer par cheque');
		break;
	case '3':
		$agi->text2wavFR('on essaie de joindre le service, merci de patienter');
		break;
	default:
		$agi->text2wavFR('Le choix n\'est pas reconnu, essayez un autre Choix');
		break;
}

$agi->text2wavFR('Au revoir');
$agi->hangup();

$endtime = time();
$duration = $endtime-$starttime;
$agi->conlog("Temps de réponce:{$duration}");
$appel->cdr_Update($starttime, $endtime, $id);
?>
