<?php 
	//dbConn.php only makes connection to the database
	include("dbConn.php");
	
	if (isset($_GET["compID"])) {
		$id = $_GET["compID"];
		
		$sql = "SELECT ComputerModel, ComputerSN, LogIn, Password FROM Computers WHERE ComputerID=".$id;
		try {
			$result = $conn->query($sql);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		
		if ($result->num_rows > 0) {
			$row = $result->fetch_array(MYSQLI_ASSOC);
			
			$model = $row["ComputerModel"];
			$sn = $row["ComputerSN"];
			$login = $row["LogIn"];
			$pw = $row["Password"];
			
			$comp = array(
				'model'=>$model,
				'SN'=>$sn,
				'login'=>$login,
				'pw'=>$pw
			);
			echo json_encode($comp);
		}
	}
?>