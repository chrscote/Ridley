<?php
	include("dbConn.php");
	
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	
	//This is a new customer who has not been added to the database.
	try {
		$sql = "INSERT INTO Customers (FirstName, LastName, Street, City, State, Zip, Telephone) ";
		$sql .= "VALUES ('Chris', 'Cote', '24A Aspinook St.', 'JC', 'CT', '06351', '860-908-5754')";
		$db->query($sql);
		echo "sql=$sql<br />";
	//echo "New Customer $sql<br />";
		
		$sqlID = "SELECT @@identity AS ID FROM Customers";
		$result = $db->query($sqlID);
		$row = $result->fetch();
		$custID = $row["ID"];
	} catch (Exception $e) {
		echo $e.getMessage();
	}
	$sqlIssue = "INSERT INTO Issues (DateRequested, CustomerID, ComputerID, Issue, ItemsIncl, ImageName) VALUES (#1/16/2015#, 1, 1, 'Having trouble', 'AC Adapter', 'none.gif')";
	try {
		$result = $db->query($sqlIssue);
		echo "Added Issue<br /> $sqlIssue<br />";
	} catch (Exception $e) {
		echo "Error adding issue: ".$e->getMessage();
	}
?>