#!/usr/bin/php -q
<?PHP
// Include the PHPAGI class
require('phpagi/phpagi.php');
// Include the mail function
//include('/var/lib/asterisk/agi-bin/mailer.php');

/**
 * @package phpAGI_examples
 * @version 2.0
 */
require_once('../phpagi-asmanager.php');

$number = '1234';

//trim($name) == false;

                   
// Evalué à vrai car $var est vide
if (empty($var)) {
  echo '$var vaut soit 0, vide, ou pas définie du tout';
}
                   
// Evalué à vrai car $var est défini
if (isset($var)) {
  echo '$var est définie même si elle est vide';
}

$asm = new AGI_AsteriskManager();
if($asm->connect())
{
	$call = $asm->send_request('Originate',
	array('Channel'=>"SIP/$number",
                      'Context'=>'default',
                      'Priority'=>1,
                      'Callerid'=>$number));
	$asm->disconnect();
}



echo "For example:";
mysql_insert('cars', array(
    'make' => 'Aston Martin',
    'model' => 'DB9',
    'year' => '2009',
));


// SQL Query Helper Function
function sqlQuery($query) {
	global $mySql;
	$data = null;
	$result = mysql_query($query, $mySql);
	# This set's up an associative array (key->value pair) for all of the data returned
	if (sizeof($result) > 0) {
		$num_fields = mysql_num_fields($result);
		$row_cnt = 0;
		while ($row_data = mysql_fetch_array($result)) {
			for ($cnt = 0; $cnt < $num_fields; $cnt++) {
				$field_name = mysql_field_name($result, $cnt);
				$data[$row_cnt][$field_name] = $row_data[$cnt];
			}
			$row_cnt++;
		}
	}
	return $data;
}

//caller ID setup function.  Add a 1 to local usa numbers if needed.
function validateCallerID($callID) {
	$first_digit = $callID[0];
	//check to see if we need to add a 1
	if ($first_digit != '1' && strlen($callID) == 10) {
		$callID = "1" . $callID; //add a 1
	}
	return $callID;
}

// A couple of variables for later use
$welcome_back_audio = "/home/ck987/asterisk_sounds/welcome_back";
$greetings_we_have_never_met_audio = "/home/ck987/asterisk_sounds/greetings_we_have_never_met";
$please_record_your_name_audio = "/home/ck987/asterisk_sounds/please_record_your_name";
$where_would_you_like_to_go_audio = "/home/ck987/asterisk_sounds/where_would_you_like_to_go";
$names_location = "/home/ck987/asterisk_html/redial/names/";

// Create an AGI Object
$agi = new AGI();

// Predefined AGI Variables, send them to the Asterisk console for debugging
$agi->conlog($agi->request["agi_request"]);
$agi->conlog($agi->request["agi_channel"]);
$agi->conlog($agi->request["agi_language"]);
$agi->conlog($agi->request["agi_uniqueid"]);
$agi->conlog($agi->request["agi_callerid"]);
$agi->conlog($agi->request["agi_dnid"]);
$agi->conlog($agi->request["agi_rdnis"]);
$agi->conlog($agi->request["agi_context"]);
$agi->conlog($agi->request["agi_extension"]);
$agi->conlog($agi->request["agi_priority"]);
$agi->conlog($agi->request["agi_enhanced"]);
$agi->conlog($agi->request["agi_accountcode"]);
$agi->conlog($agi->request["agi_network"]);
$agi->conlog($agi->request["agi_network_script"]);

// Database connection variables
$hostname = "sql.itp-redial.com";
$dbname = "redialdemo";
$username = "redial";
$password = "xxxxxxx";
// Connect to the database
$mySql = mysql_connect($hostname, $username, $password) or die (mysql_error());
mysql_select_db($dbname, $mySql) or die(mysql_error());
//set caller ID
$callerID = validateCallerID($agi->request["agi_callerid"]);
// Query the database to see if this caller has called before
$query = "select id, caller_id, last_call_time, name_audio from callers where caller_id = '" . $callerID . "'";

$result = sqlQuery($query, $mySql);
if (sizeof($result) > 0) {
	$agi->conlog("Database ID: " . $result[0]['id']);

	// We got a result from the database, play welcome message
	$agi->stream_file($welcome_back_audio);
	//pause 1 second to let stream file bug pass
	sleep(1);
	$agi->stream_file($result[0]['name_audio']);
	// get_data is similar to the Dialplan command Background
	// it plays the audio file, has a timeout in milliseconds and a max number of digits to receive
	$whereto = $agi->get_data($where_would_you_like_to_go_audio, 10000, 1);

	// $whereto['result'] is the digits that are pressed
	// send them to the console for debugging
	// say them for debugging
	if (is_numeric($whereto['result'])) {
		$agi->conlog("Result: " . $whereto['result']);
		$agi->say_number($whereto['result']);

		// Save as the Goto Dialplan command
		$agi->goto("ck987_gateway",$whereto['result'],1);
	} else {
		// Timeout.. Probably
		$agi->goto("ck987_gateway","t",1);
	}
} else {
	// We don't know this person, let's get them to record their name
	$agi->stream_file($greetings_we_have_never_met_audio);
	//pause 1 second to let stream file bug pass
	sleep(1);
	$agi->stream_file($please_record_your_name_audio);

	$record_file = $names_location . "name_" . $callerID;
	$agi->record_file($record_file, "WAV", "0123456789#*", 10000, 0, true, 5);

	// Insert this into the database
	$query = "insert into callers (caller_id, name_audio) values ('" . $callerID . "', '" . $record_file . "')";
	$insert_result = mysql_query($query, $mySql);

	$whereto = $agi->get_data($where_would_you_like_to_go_audio, 10000, 1);
	if (is_numeric($whereto['result'])) {
		$agi->conlog("Result: " . $whereto['result']);
		$agi->say_number($whereto['result']);

		$subject = "New Caller: " . $callerID;
		$body = $callerID . " has recorded their name.  Check the attachment or visit this page: http://stu.itp.nyu.edu/~ck987/redial/callers.php";

		$success = mailAttachment("ck987@nyu.edu", "chris@mailtochris.com", $subject, $body, $record_file . ".WAV");

		// Same as the Goto Dialplan command
		$agi->goto("redial_ck987",$whereto['result'],1);
	} else {
		// Timeout.. Probably
		$agi->goto("redial_ck987","t",1);
	}
}

set_time_limit(30);

//require(‘/var/lib/asterisk/agi-bin/phpagi.php’);
/*
 $agi=new AGI();

 $agi->answer();

 //Lets connect to a database and announce the values:

 $hostname=”dbhost”;

 $dbname=”database_name”;

 $username=”database_user”;

 $password=”database_password”;

 $agi->text2wave(“Welcome  to my Demo Application”); // or you can play sound file.

 mysql_connect($hostname,$username,$password) or die (mysql_error());

 mysql_select_db($dbname) or die(mysql_error());

 $agi->text2wav(“Please enter Id:”);

 $id=$agi->text_input(‘UPPERCASE’);

 $agi->text2wav(“You entered $id”);

 $query=”your sql query goes here”;

 $result = mysql_query($query);

 $num=mysql_num_rows($result);

 if($num > 0)

 {

 $row=mysql_fetch_assoc($result);

 $agi->say_number($row[id]);

 }else $agi->text2wave(“No Records Found”);
 */
?>