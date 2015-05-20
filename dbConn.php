<?php
	//If using MySQL
		$conn = mysqli_connect("localhost", "test", "test", "RLCompRepair") or die ("Could not connect to server");
/*
	//If using Microsoft Access 2013
	$dbName2013 = $_SERVER["DOCUMENT_ROOT"] . "/Ridley/RLCompRepair.accdb";
	//If using Microsoft Access 2010 or earlier.
	$dbName2010 = $_SERVER["DOCUMENT_ROOT"] . "/Ridley/RLCompRepair.mdb";
	//echo $dbName."<br />";
	if (!file_exists($dbName2013) && !file_exists($dbName2010)) {
		die("Could not find database file.<br />".$dbName);
	}
	try {
		if (file_exists($dbName2013)) {
			$db = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb, *.accdb)};;Dbq=$dbName2013");
		} else if (file_exists($dbName2010)) {
			$db = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=$dbName2010;");
		}
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch (Exception $e) {
		echo $e->getMessage();
	}
	*/
?>