<?php
	//dbConn.php only makes connection to the database
	include("dbConn.php");
	
	if (isset($_GET["lName"])||isset($_GET["fName"])) {
		$lName = $_GET["lName"];
		$fName = $_GET["fName"];
		
		$addresses = array();
		
		try {
			//$addrSQL = "SELECT Customers.ID, Customers.LastName, Customers.FirstName, Customers.Street, Customers.City, Customers.State, Customers.Telephone, Computers.ComputerModel, Computers.LogIn, Computers.Password, Computers.ComputerSN, Customers.Zip ".
			//"FROM Customers INNER JOIN Computers ON Customers.ID = Computers.CustomerID WHERE LastName LIKE '".$lName."' AND FirstName LIKE '".$fName."';";
			$addrSQL = "SELECT Customers.CustomerID AS ID, Customers.LastName, Customers.FirstName, Customers.Street, Customers.City, Customers.State, Customers.Telephone, Customers.Zip FROM Customers WHERE Customers.LastName LIKE '".$lName."%' AND Customers.FirstName LIKE '".$fName."%'";
			$result = $conn->query($addrSQL);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		
		
		//echo $addrSQL."<br />";
		
		$n = 0;		//Counter for addresses array
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			//echo "In while<br />";
			$id = $row["ID"];
			$fName = $row["FirstName"];
			$lName = $row["LastName"];
			$street = $row["Street"];
			$city = $row["City"];
			$state = $row["State"];
			$zip = $row["Zip"];
			$phone = $row["Telephone"];
			
			$addresses[$n] = array(
				'id'=>$id,
				'fName'=>$fName,
				'lName'=>$lName,
				'street'=>$street,
				'city'=>$city,
				'state'=>$state,
				'zip' =>$zip,
				'phone' => $phone
			);
			$n++;
		}
		echo json_encode($addresses);
	} else {
		echo "No name given";
	}
?>