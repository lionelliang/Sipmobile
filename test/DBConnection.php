<?php

Class SafePDO{

	private $conn;



	public function __construct() {

		$dbh = new PDO('mysql:host=localhost;dbname=sipcomcall', "asterisk", "123456");
		// Temporarily change the PHP exception handler while we . . .
		set_exception_handler(array(__CLASS__, 'exception_handler'));

		// Change the exception handler back to whatever it was before
		restore_exception_handler();
	}
	public static function exception_handler($exception) {
		// Output the exception details
		die('Uncaught exception: '. $exception->getMessage());
	}

}

// Connect to the database with defined constants
$dbh = new SafePDO();

?>