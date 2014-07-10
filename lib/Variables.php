<?php
class Variables{
	//asterisk server
	static $strMyIp = "192.168.0.20";	//localhost ip
	static $strAsteriskIp = "localhost";// "192.168.0.160";
	static $strAsteriskPort = "5038";
	static $strAsteriskUser = "admin";
	static $strAsteriskPassword = "123456";
	static $strAsteriskTimeOut = "2000";

	//mysql server
	public static $strMysqlIp = "localhost";
	public static $strMysqlUser = "asterisk";
	public static $strMysqlPassword = "123456";

	//Microsoft sql server
	public static $strMicrosoftsqlIp = "192.168.0.97";
	public static $strMicrosoftsqlUser = "sa";
	public static $strMicrosoftsqlPassword = "123456";
	
	//database
	public static $strSipcomcall = "sipcomcall";
	public static $strAsterisk = "asterisk";
	public static $strSipcomweb = "sipcomweb";
	
	//trunk name
	public static $strTrunk = "SIP/sipcomdevtrunk/";
}