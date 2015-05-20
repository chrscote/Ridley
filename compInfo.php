<?php
	//dbConn.php only makes connection to the database
	include("dbConn.php");
	$compID = $_GET["compID"];
	$sql="SELECT ComputerModel, ComputerSN, LogIn, Password FROM Computers WHERE ComputerID=$compID";
	$result = $conn->mysqli_query($sql);
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$model = $row["ComputerModel"];
		$sn = $row["ComputerSN"];
		$login= $row["LogIn"];
		$pw = $row["Password"];
		echo "model=$model&sn=$sn&login=$login&pw=$pw";
	}
?>