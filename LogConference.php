#!/usr/bin/php -q
<?php
set_time_limit(30);
require_once __DIR__.DIRECTORY_SEPARATOR.'lib/phpagi.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'lib/Appel.php';

$agi = new AGI();
$appel = new Appel($agi);

$agi_calleridname = $agi->request['agi_calleridname'];
$agi->conlog("Log le conférence $agi_calleridname");
//$filedir = '/var/spool/asterisk/meetme/';
//$file = "conference_recording_".$agi_calleridname."_".date("Ymd_His").".wav";
//$agi->exec("Set","MEETME_RECORDINGFILE=".$filedir.$file);
//$agi->set_var("MEETME_RECORDINGFILE", $filedir.$file);
//$agi->exec("Set","MEETME_EXIT_KEY=#");
//$agi->set_var('MEETME_RECORDINGFILE', "/var/spool/asterisk/meetme/conference_recording-3344");
//$agi->text2wav("Hello");
//$agi->text2wav("Conference has attantants:");
//$agi->exec("MeetMeCount", "3345");
//$options = array("3345","icMpr");
//$options = array("3345","cMmpr");	//just listen to the conference
/*
 '1' — disable "you are currently the only person in this conference" message for first member (new in 1.2 trunk, see bug 6316) (This is not included in 1.2.11)
 'a' — set admin mode
 'A' — set marked mode
 'b' — run AGI script specified in ${MEETME_AGI_BACKGROUND}

 Default: conf-background.agi (Note: This does not work with non-Zap channels in the same conference)
 'c' — announce user(s) count on joining a conference
 'd' — dynamically add conference
 'D' — dynamically add conference, prompting for a PIN
 At the pin prompt, if the user does NOT want a pin assigned to the conference, they should hit the # key.
 'e' — select an empty conference
 'E' — select an empty pinless conference
 'F' — Pass DTMF through the conference.
 'i' — announce user join/leave with review — requires chan_zap.so (new in Asterisk 1.2) — Asterisk 1.4 has an issue with loud squelches after announcements. See bug 9430
 'I' --announce user join/leave without review
 'M' — enable music on hold when the conference has a single caller
 'm' — set monitor only mode (Listen only, no talking)
 'p' — allow user to exit the conference by pressing '#'
 'P' — always prompt for the pin even if it is specified
 'q' — quiet mode (don't play enter/leave sounds)
 'r' — Record conference (records as ${MEETME_RECORDINGFILE} using format ${MEETME_RECORDINGFORMAT}). Default filename is meetme-conf-rec-${CONFNO}-${UNIQUEID} and the default format is wav. — requires chan_zap.so
 's' — Present menu (user or admin) when '*' is received ('send' to menu)
 't' — set talk only mode. (Talk only, no listening)
 'T' — set talker detection (sent to manager interface and meetme list)
 'v' — video mode (this option currently does nothing at all)
 'w' — wait until the marked user enters the conference (plays music on hold until marked user enters if M is used)

 All other connected users will hear MusicOnHold until the marked user enters.
 'X' — allow user to exit the conference by entering a valid single digit extension of the context specified in ${MEETME_EXIT_CONTEXT} or the current context if that variable is not defined. Due to a bug (see 5773 and 5631) this option didn't work in Asterisk v1.2.0.
 'x' — close the conference when last marked user exits
 */
//$agi->exec('MeetMe', $options);	//press '#' to exit the conferece

$meetmesecs = $agi->get_variable('MEETMESECS', true);
$recordingfile = $agi->get_variable('MEETME_RECORDINGFILE', true);
$recordingformat = $agi->get_variable('MEETME_RECORDINGFORMAT', true);
$exitcontext = $agi->get_variable('MEETME_EXIT_CONTEXT', true);
$agibackground = $agi->get_variable('MEETME_AGI_BACKGROUND', true);
$agi->conlog($meetmesecs);
$agi->conlog($recordingfile);
$agi->conlog($recordingformat);
$agi->conlog($exitcontext);
$agi->conlog($agibackground);

$agi->conlog("exitez de conférence");
?>
