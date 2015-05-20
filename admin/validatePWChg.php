<?php
	//dbConn.php only makes connection to the database
	include("../dbConn.php");
	session_start();
	if (isset($_SESSION["admin"])) {
		$user = $_SESSION["admin"];
		$oldPW = $_POST["oldPW"];
		$newPW = $_POST["newPW"];
		$confirm = $_POST["confirmPW"];
		
		$sqlUser = "SELECT * FROM Admin WHERE UserName='".$user."' and Password='".md5($oldPW)."'";
		
		try {
			$rsUser = $conn->query($sqlUser);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		if ($rsUser->num_rows==0) {
			echo "<h2>Sorry, the User Name you entered is incorrect. <a href=\"index.php?action=pwCh\">Try again</a><br />\n";
			echo md5("admin");
		} else {
			if ($newPW == $confirm) {
				$sqlChg = "UPDATE Admin SET Password='".md5($newPW)."' WHERE UserName='$user'";
				$conn->query($sqlChg);
				header('Location: index.php');
			} else {
				echo "<h3>Sorry, new passwords do not match.</h3><a href=\"index.php?action=pwCh\">Try again</a><br />\n";
			}
		}
	} else {
		echo "Admin session var not set";
	}
?>