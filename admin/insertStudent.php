<?php
	include("../dbConn.php");
	
	$fName = $_POST["fName"];
	$lName = $_POST["lName"];
	
	//Currently making the assumption that there are no repetitions of both first and last names.
	//For example, there will not be 2 students named John Smith in the same class.
	$sqlAdd = "INSERT INTO Techs (FirstName, LastName, CurrentStudent) VALUES ('$fName', '$lName', True)";
	try {
		$conn->query($sqlAdd);
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	
	header("Location:index.php?action=addRemTech");
?>