#!/usr/bin/php -q
<?php
# don't let this script run for more than 60 seconds
set_time_limit(60);
# turn off output buffering
ob_implicit_flush(false);
# turn off error reporting, as it will most likely interfere with
# the AGI interface
error_reporting(0);

#---- create file handles if needed
if (!defined('STDIN'))
{
	define('STDIN', fopen('php://stdin', 'r'));
}
if (!defined('STDOUT'))
{
	define('STDOUT', fopen('php://stdout', 'w'));
}
if (!defined('STDERR'))
{
	define('STDERR', fopen('php://stderr', 'w'));
}

#---- retrieve all AGI variables from Asterisk
while (!feof(STDIN))
{
	$temp = trim(fgets(STDIN,4096));
	if (($temp == "") || ($temp == "\n"))
	{
		break;
	}
	$s = split(":",$temp);
	$name = str_replace("agi_","",$s[0]);
	$agi[$name] = trim($s[1]);
}


# print all AGI variables for debugging purposes
foreach($agi as $key=>$value)
{
	fwrite(STDERR,"-- $key = $value\n");
	fflush(STDERR);
}


# tell the caller : a 20
$TempAlpha="a";
$TempNbr=20;

fwrite(STDOUT,"SAY ALPHA $TempAlpha \"\"\n");
fflush(STDOUT);
$result = trim(fgets(STDIN,4096));

fwrite(STDOUT,"SAY NUMBER $TempNbr \"\"\n");
fflush(STDOUT);
$result = trim(fgets(STDIN,4096));




require_once('phpagi/phpagi-asmanager.php');

$number = '0143112244';
$siptrunk = 'sipcomtrunk';

$asm = new AGI_AsteriskManager();
if($asm->connect())
{

	$call = $asm->send_request('Originate',
	array('Channel'=>"SIP/$siptrunk/$number",
                  'Context'=>'internal',
                  'Priority'=>1,
                  'Callerid'=>$number));


	$asm->disconnect();
}

fwrite(STDOUT,"STREAM FILE vm-goodbye \"\"\n");
fflush(STDOUT);
$result = trim(fgets(STDIN,4096));

?>
