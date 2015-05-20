<?php
	session_start();
	include("../dbConn.php");
	
	$userName = $_POST["userName"];
	$password = $_POST["password"];
	
	$sql = "SELECT * FROM Admin WHERE UserName='".$userName."' and Password='".md5($password)."'";
	try {
		$rsUser = $conn->query($sql);
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	
	if (!$rsUser) {
		die("Invalid query<br />".$sql);
	}
	if ($rsUser->num_rows==0) {
		echo "<h2>Sorry, the User Name or Password you entered is incorrect. <a href=\"index.php\">Try again</a><br />\n".md5('$password');
	} else {
		$_SESSION["admin"]=$userName;
		echo "Login Successful";
		header('Location: index.php');
	}
?>