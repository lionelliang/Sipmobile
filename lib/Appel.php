#!/usr/bin/php -q
<?php
require_once __DIR__.DIRECTORY_SEPARATOR.'Variables.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'phpagi.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'phpagi-asmanager.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'DB.php';

class Appel{

	private  $agi;
	private  $db;

	//function __construct(AGI $agi){
	function __construct($agi){
		//$this->agi = new AGI();
		$this->agi = $agi;
		$this->db = new DB($agi);
	}

	public function startMenu(){
		$done = false;
		while(!$done){
			$keys = null;
			$ret = $this->agi->text2wavFR('Pour le service commercial tapez 1, pour le service technique tapez 2, pour le service facturation tapez 3', '#123');
			if($ret['result'] != 0){
				$keys = chr($ret['result']);
				$done = true;
			}
			if(is_null($keys)){
				$ret = $this->agi->get_data('beep', 5000, 3);
				if($ret['result'] == -1)
				break;
				if($ret['result'] == null || $ret['result'] == ''){
					continue;
				}else{
					$keys = $ret['result'];
					$this->agi->conlog($keys);
				}
			}
			$this->agi->text2wavFR("vous saisissez $keys");
			switch ($keys){
				case '1':
					$this->agi->text2wavFR('Bienvenu au service commercial');
					$done=true;
					break;
				case '2':
					$this->agi->text2wavFR('Bienvenu au service technique');
					$done=true;
					break;
				case '3':
					$this->agi->text2wavFR('Bienvenu au service facturation');
					$done=true;
					break;
				default:
					$this->agi->text2wavFR('Le choix n\'est pas reconnu, essayez un autre Choix');
					break;
			}
		}
		return $keys;
	}

	/**
	 *
	 * let user enter a number
	 * @param string $message message to play on user side
	 * @param int $num number length needed
	 */
	public function getDestinationFR($message, $num = 15){
		$done=false;
		while(!$done){
			$keys = null;
			$ret = $this->agi->text2wavFR($message,'1234567890');
			if($ret['result'] != 0){
				$keys = chr($ret['result']);
				$this->agi->conlog($keys);
				$done = true;
			}
			$ret = $this->agi->get_data('beep', 5000, $num);
			if($ret['code'] != AGIRES_OK || $ret['result'] == -1)
			break;
			if($ret['result'] == null || $ret['result'] == ''){
				continue;
			}else{
				if(!is_null($keys)){
					$keys = $keys.$ret['result'];
				}else{
					$keys = $ret['result'];
				}
				$this->agi->conlog($keys);
				$done=true;
				//$this->agi->text2wav("You entered $keys");
			}
		}
		return $keys;
	}

	/**
	 * check if destination is online
	 * Enter description here ...
	 * @param AGI_AsteriskManager $agimanager
	 * @param string $destination
	 */
	public function checkDestination(AGI_AsteriskManager $agimanager, $destination){
		$res=false;
		if($agimanager->connect(Variables::$strAsteriskIp, Variables::$strAsteriskUser, Variables::$strAsteriskPassword)){
			$command="sip show peers";
			$ret=$agimanager->Command($command);
			if($ret!=null){
				$this->agi->conlog($ret['data']);
				$value = $this->explaineResult($ret['data']);
			}
		}else{
			$this->agi->conlog("didn\'t login rightly");
		}
		return $ret;
	}

	/**
	 *
	 * log the basic call information
	 * @return insert id
	 */
	public function cdr_Insert(){
		//basic sip call infomation
		$calldate = date("Y-m-d H:i:s", time());
		$agi_callerid = $this->agi->request['agi_callerid'];
		$agi_calleridname = $this->agi->request['agi_calleridname'];
		$agi_request = $this->agi->request['agi_request'];
		$agi_channel = $this->agi->request['agi_channel'];
		$agi_language = $this->agi->request['agi_language'];
		$agi_type = $this->agi-> request['agi_type'];
		$agi_uniqueid = $this->agi->request['agi_uniqueid'];
		$agi_context = $this->agi->request['agi_context'];
		$agi_extension = $this->agi->request['agi_extension'];
		$agi_priority = $this->agi->request['agi_priority'];
		$this->agi->conlog($agi_uniqueid);
		//log the call in database
		$this->db->cdr_Insert($agi_callerid, $agi_calleridname, $agi_request,
		$agi_channel, $agi_language, $agi_type, $agi_uniqueid, $agi_context,
		$agi_extension, $agi_priority, $calldate);
		$id = mysql_insert_id();

		return $id;
	}

	/**
	 *
	 * log fax transmission infomation
	 * @return insert id
	 */
	public function fax_log(){

		//basic sip call infomation
		$agi_callerid = $this->agi->request['agi_callerid'];
		$agi_calleridname = $this->agi->request['agi_calleridname'];
		$agi_request = $this->agi->request['agi_request'];
		$agi_channel = $this->agi->request['agi_channel'];
		$agi_language = $this->agi->request['agi_language'];
		$agi_type = $this->agi-> request['agi_type'];
		$agi_uniqueid = $this->agi->request['agi_uniqueid'];
		$agi_context = $this->agi->request['agi_context'];
		$agi_extension = $this->agi->request['agi_extension'];
		$agi_priority = $this->agi->request['agi_priority'];
		$agi_dnid = $this->agi->request['agi_dnid'];
		$agi_callingpres = $this->agi->request['agi_callingpres'];
		$agi_callingani2 = $this->agi->request['agi_callingani2'];
		$agi_callington = $this->agi->request['agi_callington'];
		$agi_callingtns = $this->agi->request['agi_callingtns'];
		$agi_rdnis = $this->agi->request['agi_rdnis'];
		$agi_dnid = $this->agi->request['agi_dnid'];
		$agi_enhanced = $this->agi->request['agi_enhanced'];
		$agi_threadid = $this->agi->request['agi_threadid'];
		/*
		 $this->agi->conlog($agi_callerid);
		 $this->agi->conlog($agi_calleridname);
		 $this->agi->conlog($agi_request);
		 $this->agi->conlog($agi_language);
		 $this->agi->conlog($agi_type);
		 $this->agi->conlog($agi_uniqueid);
		 $this->agi->conlog($agi_context);
		 $this->agi->conlog($agi_extension);
		 $this->agi->conlog($agi_priority);
		 $this->agi->conlog($agi_dnid);
		 $this->agi->conlog($agi_callingpres);
		 $this->agi->conlog($agi_callingani2);
		 $this->agi->conlog($agi_callington);
		 $this->agi->conlog($agi_rdnis);
		 $this->agi->conlog($agi_enhanced);
		 $this->agi->conlog($agi_threadid);
		 */
		//fax transmission infomation
		$ecm = $this->agi->get_variable('FAXOPT(ecm)', true);
		$filename = $this->agi->get_variable('FAXOPT(filename)', true);
		$headerinfo = $this->agi->get_variable('FAXOPT(headerinfo)', true);
		$localstationid = $this->agi->get_variable('FAXOPT(localstationid)', true);
		$maxrate = $this->agi->get_variable('FAXOPT(maxrate)', true);
		$minrate = $this->agi->get_variable('FAXOPT(minrate)', true);
		$pages = $this->agi->get_variable('FAXOPT(pages)', true);
		$rate = $this->agi->get_variable('FAXOPT(rate)', true);
		$remotestationid = $this->agi->get_variable('FAXOPT(remotestationid)', true);
		$resolution = $this->agi->get_variable('FAXOPT(resolution)', true);
		$status = $this->agi->get_variable('FAXOPT(status)', true);
		$statusstr = $this->agi->get_variable('FAXOPT(statusstr)', true);
		$error = $this->agi->get_variable('FAXOPT(error)', true);

		$this->agi->conlog($ecm);
		$this->agi->conlog($filename);
		$this->agi->conlog($headerinfo);
		$this->agi->conlog($localstationid);
		$this->agi->conlog($maxrate);
		$this->agi->conlog($minrate);
		$this->agi->conlog($pages);
		$this->agi->conlog($rate);
		$this->agi->conlog($remotestationid);
		$this->agi->conlog($resolution);
		$this->agi->conlog($status);
		$this->agi->conlog($statusstr);
		$this->agi->conlog($error);

		//log the fax in database
		$this->db->faxcdr_Insert($agi_callerid, $agi_calleridname, $ecm, $filename, $headerinfo,
		$localstationid, $maxrate, $minrate, $pages, $rate, $remotestationid, $resolution, $status,
		$statusstr, $error, $agi_request, $agi_channel, $agi_language, $agi_callingpres, $agi_callingani2,
		$agi_callington, $agi_callingtns, $agi_rdnis, $agi_type, $agi_dnid, $agi_context, $agi_extension,
		$agi_priority, $agi_enhanced, $agi_threadid, $agi_uniqueid);

		$id = mysql_insert_id();

		return $id;
	}

	/**
	 * update fax history in ms sql server if the fax is sent
	 * @param string $server
	 * @param 0/1 $issent
	 * @param int $id
	 */
	public function fax_Update($id){
		$datesent = date("Y-m-d H:i:s", time());
		$server = Variables::$strMyIp;
		$issent = '1';
		return $this->db->fax_Update($datesent, $server, $issent, $id);
	}
	
	/**
	 * update fax history in ms sql server if sending fax has an error
	 * @param smalltime $datesent
	 * @param string $server
	 * @param 0/1 $issent
	 * @param int $id
	 */
	public function faxFailed_Update($id){
		$errorinfo = $this->agi->get_variable('FAXOPT(error)', true);
		return $this->db->faxFailed_Update($errorinfo, $id);
	}
	
	/**
	 * 
	 * Send the fax using email
	 * @param string $toUserMail
	 * @param string $atachedFile
	 */
	public static function emailFax($toUserMail, $atachedFile){
		if (file_exists($atachedFile)){
			$command = "sendEmail -l /var/log/sendEmail.log -f test@sipcom.fr -t $toUserMail -u You have a FAX -a $atachedFile -m You have a new FAX. php Find attached. -s auth.smtp.1and1.fr -xu test@sipcom.fr -xp test123";
			//$this->agi->conlog(system($command, $retval));
			system($command, $retval);	//shell_exec
			return $retval;
		}else{
			return false;
		}
	}
	
	public function cdr_Update($hangupcause, $dst, $starttime, $endtime, $id){
		$duration = $endtime-$starttime;
		$starttime = date("Y-m-d H:i:s", $starttime);
		$endtime = date("Y-m-d H:i:s", $endtime);
		$bridgestart = $this->getCELEventTime("BRIDGE_START");
		$bridgeend = $this->getCELEventTime("BRIDGE_END");
		if(empty($bridgeend) || $bridgeend == "" || $bridgeend == null){
			$bridgeend = time();
		}
		$this->db->cdr_Update($hangupcause, $dst, $starttime, $endtime, $duration, $bridgestart, $bridgeend, $id);
	}

	/**
	 * Get bridge time from cel, with same uniqueid
	 * @return bridge event start time
	 */
	public function getCELEventTime($event){
		$agi_uniqueid = $this->agi->request['agi_uniqueid'];
		$ret = $this->db->getCEL($agi_uniqueid, $event);
		if(!empty($ret)){
			$row = mysql_fetch_assoc($ret);
			$bridgestart = $row['eventtime'];
			//$this->agi->conlog("${$event} time: {$bridgestart}");
			return  $bridgestart;
		}else{
			return null;
		}
	}

	/**
	 *
	 * get call status
	 * ANSWER: Call is answered. A successful dial. The caller reached the callee.
	 * BUSY: Busy signal. The dial command reached its number but the number is busy.
	 * NOANSWER: No answer. The dial command reached its number, the number rang for too long, then the dial timed out.
	 * CANCEL: Call is cancelled. The dial command reached its number but the caller hung up before the callee picked up.
	 * CONGESTION: Congestion. This status is usually a sign that the dialled number is not recognised.
	 * CHANUNAVAIL: Channel unavailable. On SIP, peer may not be registered.
	 * DONTCALL: Privacy mode, callee rejected the call
	 * TORTURE: Privacy mode, callee chose to send caller to torture menu
	 * INVALIDARGS: Error parsing Dial command arguments (added for Asterisk 1.4.1, SVN r53135-53136)
	 * @param string $status
	 */
	public function statusReponse($status){
		switch ($status){
			case 'ANSWER':
				break;
			case 'BUSY':
				//$this->agi->text2wav('The number you dialed is busy, please try again later.');
				$this->agi->text2wavFR('le numéro que vous appellez est occupé, essayez plus tard');
				break;
			case 'NOANSWER':
				//$this->agi->text2wav('The number you dialed is not answered.');
				$this->agi->text2wavFR('le numéro que vous appellez n\'est pas répondu');
				break;
			case 'CHANUNAVAIL':
				//$this->agi->text2wav('The number you dialed is not available.');
				$this->agi->text2wavFR('le numéro que vous appellez n\'est pas joignable'); 
				break;
			case 'CONGESTION':
				//$this->agi->text2wav('The number you dialed is not available.');
				$this->agi->text2wavFR('le numéro que vous appellez n\'est pas joignable');
				break;
			default:
				//$this->agi->text2wav('The number you dialed is busy.');
				$this->agi->text2wavFR('le numéro que vous appellez n\'est pas joignable');
				break;
		}
	}

	/**
	 *
	 * check if user account is empty
	 */
	public function checkBalance(){
		$agi_calleridname = trim($this->agi->request['agi_calleridname']);
		$ret = $this->db->getBalance($agi_calleridname);
		if(!empty($ret)){
			$row = mysql_fetch_assoc($ret);
			return $row['balance'] > 0;
		}else{
			$this->agi->conlog("Didn't find this user: {$agi_calleridname}");
		}
	}

	/**
	 * 
	 * Convert tiff file to pdf
	 * @param string $inFile
	 * @param string $outFile
	 */
	public static function tiff2Pdf($inFile, $outFile){
		if (file_exists($inFile)){
			$gs_command = "tiff2pdf -o $outFile $inFile";
			system($gs_command, $retval);
			//echo $retval."\n";
			return $retval;
		}else{
			return false;
		}
	}
	
	/**
	 * @return $value[0][1]={123456780/0123456780,192.168.0.127,D,Yes,Yes,48886,OK}
	 * Name/username             Host                                    Dyn Forcerport Comedia    ACL Port     Status
	 * 0123456780/0123456780     192.168.0.127                            D  Yes        Yes            48886    OK
	 * $value[1] ={2,1,1,0,0}
	 * 2 sip peers [Monitored: 1 online, 1 offline Unmonitored: 0 online, 0 offline]
	 */
	function explaineResult($ret){
		$content = explode("\r\n", trim($ret));
		$keys = array();
		if (!empty($content)){
			unset($content[0]); 							//remove Privilege: Command
			$results = explode("\n", trim($content[1]));
			$resultSize = sizeof($results);

			$line_sys = $results[$resultSize-1];
			//$line_sys = preg_replace('/\s\s+/', ' ', $lines[4]);
			$parse_sys = explode(' ', trim($line_sys));
			$sys_info[0] = trim($parse_sys[0]);
			$sys_info[1] = trim($parse_sys[4]);
			$sys_info[2] = trim($parse_sys[6]);
			$sys_info[3] = trim($parse_sys[9]);
			$sys_info[4] = trim($parse_sys[11]);

			unset($results[0]);								// remove Privilege: Command
			unset($results[$resultSize-1]);			//remove :2 sip peers [Monitored: 2 online, 0 offline Unmonitored: 0 online, 0 offline]

			$i=0;
			foreach ($results as $result) {
				$parse = explode(' ', trim($result));
				$name = explode("/", trim($parse[0]));

				$j=0;
				$user_info[$i][$j++] = trim($name[0]);
				$user_info[$i][$j++] = trim($name[1]);
				$user_info[$i][$j++] = trim($parse[2]);
				$user_info[$i][$j++] = trim($parse[3]);
				$user_info[$i][$j++] = trim($parse[4]);
				$user_info[$i][$j++] = trim($parse[5]);
				$user_info[$i][$j++] = trim($parse[6]);
				$i += 1;
			}
		}
		$value[0] = $user_info;
		$value[1] = $sys_info;

		return $value;
	}
}
?>
