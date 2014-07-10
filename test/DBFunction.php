<?php

//build a query string:
$query = sprintf("SELECT firstname, lastname, address, age FROM friends
    WHERE firstname='%s' AND lastname='%s'",
mysql_real_escape_string($firstname),
mysql_real_escape_string($lastname));
//use result
// Voir aussi mysql_result(), mysql_fetch_array(), mysql_fetch_row(), mysql_insert_id(), etc.
while ($row = mysql_fetch_assoc($result)) {
	echo $row['firstname'];
	echo $row['lastname'];
	echo $row['address'];
	echo $row['age'];
}

//insert with a table:
function mysql_insert($table, $inserts) {
	$values = array_map('mysql_real_escape_string', array_values($inserts));
	$keys = array_keys($inserts);

	return mysql_query('INSERT INTO `'.$table.'` (`'.implode('`,`', $keys).'`) VALUES (\''.implode('\',\'', $values).'\')');
}


function mysql_insert($table, $toAdd){

	$fields = implode(array_keys($toAdd), ',');
	$values = "'".implode(array_values($toAdd), "','")."'"; # better

	$q = 'INSERT INTO `'.$table.'` ('.$fields.') VALUES ('.$values.')';
	$res = mysql_query($q) OR die(mysql_error());

	return true;

	//-- Example of usage
	//$tToAdd = array('id'=>3, 'name'=>'Yo', 'salary' => 5000);
	//insertIntoDB('myTable', $tToAdd)
}

function mysql_update($table, $update, $where){
	$fields = array_keys($update);
	$values = array_values($update);
	$i=0;
	$query="UPDATE ".$table." SET ";
	while($fields[$i]){
		if($i<0){$query.=", ";}
		$query.=$fields[$i]." = '".$values[$i]."'";
		$i++;
	}
	$query.=" WHERE ".$where." LIMIT 1;";
	mysql_query($query) or die(mysql_error());
	return true;

	//Example
	// mysql_update('myTable', $anarray, "type = 'main'")

}

$today = date("Y-m-d H:i:s");                     // 2001-03-10 17:16:18 (le format DATETIME de MySQL)