<?php 
	//dbConn.php only makes connection to the database
	include("dbConn.php");
	
	$dateFrom = "";
	$dateTo = "";
	if (isset($_GET["techID"])) {
		$stuID = $_GET["techID"];
	}
	if (isset($_GET["dateFrom"])) {
		$dateFrom = $_GET["dateFrom"];
	}
	if (isset($_GET["dateTo"])) {
		$dateTo = $_GET["dateTo"];		
	}
	$sqlIssues = "SELECT Customers.FirstName, Customers.LastName, Computers.ComputerModel, Issues.Issue, Issues.DateRequested, ".
		"Issues.DateReceived, ActionsTaken.Action, Techs.TechID FROM Techs INNER JOIN (((Customers INNER JOIN Computers ON ".
		"Customers.CustomerID = Computers.CustomerID_FK) INNER JOIN Issues ON Computers.ComputerID = Issues.ComputerID_FK) INNER JOIN ".
		"ActionsTaken ON Issues.IssueID = ActionsTaken.IssueID_FK) ON Techs.TechID = ActionsTaken.TechID_FK WHERE Techs.TechID=$stuID ";
	$sqlIssues.="ORDER BY Issues.DateRequested";
	try {
		$rsIssues = $conn->query($sqlIssues);
	} catch (Exception $ex) {
		echo $ex->getMessage();	
	}
?>