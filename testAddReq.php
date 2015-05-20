<?php
	error_reporting(E_ALL | E_NOTICE);
    ini_set('display_errors', '1');
		
	//dbConn.php only makes connection to the database
	include("dbConn.php");
	
	$target_dir = "images/";
	$imgName = "none.gif";
	
	$custID = $_POST["custId"];
	$compID = $_POST["compSel"];
	$dateReq = date('n/j/Y');
	$issue = $_POST["issue"];
	$added = $_POST["added"];
	
	
	if (isset($_FILES["compImage"])) {
		//echo "FILES['compImage']=".$_FILES["compImage"]["name"]."<br />";
		$img = $_FILES["compImage"];
		$baseName = basename($_FILES["compImage"]["name"]);
		if ($baseName!="") {
			$imgName = $baseName;
		}
		$target_file = $target_dir . basename($_FILES["compImage"]["name"]);
		
		//Upload the image
		if (move_uploaded_file($_FILES["compImage"]["tmp_name"], $target_file)) {
			$imageUploaded = true;
		}
	}
	
	$sqlIssue = "INSERT INTO Issues (DateRequested, CustomerID, ComputerID, Issue, ItemsIncl, ImageName) VALUES (#".$dateReq."#, ".$custID.", ".$compID.", '".$issue."', '".$added."', '".$imgName."')";
	
	//$sqlIssue = "INSERT INTO Issues (DateRequested, CustomerID, ComputerID, Issue, ItemsIncl, ImageName) VALUES (#1/1/2015#, 1, 1, 'Lots of trouble', 'AC Adapter', 'none.gif')";
	try {
		$result = $db->query($sqlIssue);
		echo "Added Issue<br /> $sqlIssue<br />";
	} catch (Exception $e) {
		echo "Error adding issue: ".$e->getMessage();
	}
	
?>