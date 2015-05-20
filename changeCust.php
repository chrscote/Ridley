<?php
	//dbConn.php only makes connection to the database
	include("dbConn.php");
	
	//Perform the edit then redirect to main page.
	$id = $_POST["custID"];
	$lName = $_POST["lName"];
	$fName = $_POST["fName"];
	$street = $_POST["street"];
	$city = $_POST["city"];
	$state = $_POST["state"];
	$zip = $_POST["zip"];
	$phone = $_POST["phone"];
	
	$editSQL = "UPDATE Customers SET LastName='".$lName."', FirstName='".$fName."', Street='".$street."', City='".$city."', State='".$state."',".
		"Zip='".$zip."', Telephone='".$phone."' WHERE CustomerID=".$id;
	$conn->query($editSQL);
	
	header("Location:main.php?custID=".$id);

?>