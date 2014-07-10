#! /usr/bin/php -q
<?php
//get call duration
set_time_limit(30);
require('phpagi.php');
error_reporting(E_ALL);
$agi = new AGI();

$vv=$agi->get_variable('CDR(billsec)',true);
$agi->verbose($vv);
$results = execute_agi('GET VARIABLE ANSWEREDTIME');
log_agi($results['result']);
$callduration = $results['result'];

function execute_agi($command) {
	fwrite(STDOUT, "$command\n");
	fflush(STDOUT);
	$result = fgets(STDIN);
	$result = "200 result=1";
	$ret = array('code'=> -1, 'result'=> -1, 'timeout'=> false, 'data'=> '');
	if (preg_match("/^([0-9]{1,3}) (.*)/", $result, $matches)) {
		$ret['code'] = $matches[1];
		$ret['result'] = 0;
		if (preg_match('/^result="([0-9a-zA-Z]*)"(?:\s?\((.*?)\))?$/', $matches[2], $match))  {
			$ret['result'] = $match[1];
			$ret['timeout'] = ($match[2] === 'timeout') ? true : false;
			$ret['data'] = $match[2];
		}
	}
	return $ret;
}
function log_agi($entry, $level = 1) {
	if (!is_numeric($level)) {
		$level = 1;
	}
	$result = execute_agi("VERBOSE \"$entry\" $level");
}
?>
