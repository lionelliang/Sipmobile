#!/usr/bin/php -q
<?php
set_time_limit(30);
require('phpagi/phpagi.php');
error_reporting(E_ALL);

# tell the caller : ab 20
$TempAlpha="ab";
$TempNbr=20;
$number01 = '0141840365';
$number02 = '0143112244';
$siptrunk = 'sipcomtrunk';

fwrite(STDOUT,"SAY ALPHA $TempAlpha \"\"\n");
fflush(STDOUT);
$result = trim(fgets(STDIN,4096));

fwrite(STDOUT,"SAY NUMBER $TempNbr \"\"\n");
fflush(STDOUT);
$result = trim(fgets(STDIN,4096));
/*
 fwrite(STDOUT,"VERBOSE \'hello there'  \"\"\n");
 fflush(STDOUT);
 $result = trim(fgets(STDIN,4096));
 */
function log_agi($entry, $level = 1) {
	if (!is_numeric($level)) {
		$level = 1;
	}
	$result = execute_agi("VERBOSE \"$entry\" $level");
}
function execute_agi($command) {
	fwrite(STDOUT, "$command\n");
	fflush(STDOUT);
	$result = fgets(STDIN);
	$ret = array('code'=> -1, 'result'=> -1, 'timeout'=> false, 'data'=> '');
	if (preg_match("/^([0-9]{1,3}) (.*)/", $result, $matches)) {
		$ret['code'] = $matches[1];
		$ret['result'] = 0;
		if (preg_match('/^result=([0-9a-zA-Z]*)(?:\s?\((.*?)\))?$/', $matches[2], $match))  {
			$ret['result'] = $match[1];
			//$ret['timeout'] = ($match[2] === 'timeout') ? true : false;
			//$ret['data'] = $match[2];
		}
	}
	return $ret;
}


//get the AGI variables; we will check caller id
function showagivars()
{
	$agivars = array();
	while (!feof(STDIN)) {
		$agivar = trim(fgets(STDIN));
		if ($agivar === '') {
			break;
		}
		else {
			$agivar = explode(':', $agivar);
			$agivars[$agivar[0]] = trim($agivar[1]);
		}
	}
	foreach($agivars as $k=>$v) {
		log_agi("Got $k=$v");
	}
}

showagivars();
//extract($agivars);
execute_agi('EXEC Dial SIP/'.$siptrunk.'/'.$number02);


$callduration = execute_agi('GET VARIABLE ANSWEREDTIME');

fwrite(STDOUT,"SAY NUMBER $callduration \"\"\n");
fflush(STDOUT);
$result = trim(fgets(STDIN,4096));

execute_agi('EXEC HANGUP');
exit;



/*
 *   agi_request - name of agi script
 *   agi_channel - current channel
 *   agi_language - current language
 *   agi_type - channel type (SIP, ZAP, IAX, ...)
 *   agi_uniqueid - unique id based on unix time
 *   agi_callerid - callerID string
 *   agi_dnid - dialed number id
 *   agi_rdnis - referring DNIS number
 *   agi_context - current context
 *   agi_extension - extension dialed
 *   agi_priority - current priority
 *   agi_enhanced - value is 1.0 if started as an EAGI script
 *   agi_accountcode - set by SetAccount in the dialplan
 *   agi_network - value is yes if this is a fastagi
 *   agi_network_script - name of the script to execute

 //$timestartcall = new DateTime()

 $timestartcall = date();
 */

//USING PHPAGI
$agi = new AGI();
$mytype=$agi->request['agi_type'];
$myuniqueid=$agi->request['agi_uniqueid'];
$mycallerid=$agi->request['agi_callerid'];
$mydnid=$agi->request['agi_dnid'];

$tempstr ='agi_type:'.$mytype. " || " .$myuniqueid. " || " .$mycallerid. " || dnid: " .$mydnid. " || channel: " .$mychannel;
log_agi($tempstr);
$agi->channel_status();
$agi->exec('Dial','SIP/'.$siptrunk.'/'.$number02);


/*

//$myrequest=$agi->request['agi_request'];
//$mychannel=$agi->request['agi_channel'];
//$mytype=$agi->request['agi_type'];

$mystr= $timestartcall . " || " . $mytype. " || " .$myuniqueid. " || " .$mycallerid. " || dnid: " .$mydnid. " || channel: " .$mychannel;
$agi->conlog($mystr . " || " . $argv);


//$agi->conlog($argv);
*/


//$agi->exec('HANGUP');
//$agi->hangup();


//$agi->exec('Dial','SIP/'.$siptrunk.'/'.$mydnid);
//$agi->hangup();

/*
 //insert into DB
 $con = mysql_connect('locahost','root','123456');
 mysql_select_db('mycallshop', $con);
 $result = mysql_query("INSERT INTO cs_call (sessionid,uniqueid,card_id,starttime,stoptime,
 sessiontime,calledstation,sessionbill,id_tariffgroup,id_tariffplan,
 id_ratecard,id_trunk,sipiax,src,id_did,
 buycost,id_card_package_offer,real_sessiontime,dnid,terminatecauseid,destination)
 VALUES ('','$myuniqueid','$mycallerid','$timestartcall ','',
 '','','','','',
 '','','','','',
 '','','$mydnid','','',) ");
 */



/*
 `id` bigint(20) NOT NULL auto_increment,
 `sessionid` varchar(40) collate utf8_bin NOT NULL,
 `uniqueid` varchar(30) collate utf8_bin NOT NULL,
 `card_id` bigint(20) NOT NULL,
 `nasipaddress` varchar(30) collate utf8_bin NOT NULL,
 `starttime` timestamp NOT NULL default CURRENT_TIMESTAMP,
 `stoptime` timestamp NOT NULL default '0000-00-00 00:00:00',
 `sessiontime` int(11) default NULL,
 `calledstation` varchar(30) collate utf8_bin NOT NULL,
 `sessionbill` float default NULL,
 `id_tariffgroup` int(11) default NULL,
 `id_tariffplan` int(11) default NULL,
 `id_ratecard` int(11) default NULL,
 `id_trunk` int(11) default NULL,
 `sipiax` int(11) default '0',
 `src` varchar(40) collate utf8_bin NOT NULL,
 `id_did` int(11) default NULL,
 `buycost` decimal(15,5) default '0.00000',
 `id_card_package_offer` int(11) default '0',
 `real_sessiontime` int(11) default NULL,
 `dnid` varchar(40) collate utf8_bin NOT NULL,
 `terminatecauseid` int(1) default '1',
 `destination` int(11) default '0',
 */

//$agi->exec('Dial','SIP/'.$siptrunk.'/'.$number02);
//$agi->conlog(date()  . " || " .  date()->diff($timestartcall ));


/*
 $agi = new AGI();
 $agi->answer();

 $cid = $agi->parse_callerid();
 $agi->text2wav("Hello, {$cid['name']}.");
 do
 {
 $agi->text2wav('Enter some numbers and then press the pound key. Press 1 1 1 followed by the pound key to quit.');
 $result = $agi->get_data('beep', 3000, 20);
 $keys = $result['result'];
 $agi->text2wav("You entered $keys");
 } while($keys != '111');
 $agi->text2wav('Goodbye');
 $agi->hangup();
 */



?>
