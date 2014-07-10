#!/usr/bin/php -q
<?php
include("phpagi/phpagi.php");
class Appel extends AGI{

	public $start_time;
	public $end_time;

	function read_result(){
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
}
?>
