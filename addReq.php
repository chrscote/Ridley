<?php
	error_reporting(E_ALL | E_NOTICE);
    ini_set('display_errors', '1');
	
	//dbConn.php only makes connection to the database
	$conn = mysqli_connect("localhost", "test", "test", "RLCompRepair") or die ("Could not connect to server");
	
	//Get values to edit the Customers table
	//This needs to be here so we can use it in the receipt.
	$fName = $_POST["fName"];
	$lName = $_POST["lName"];
	$street = $_POST["address"];
	$city = $_POST["city"];
	$state = $_POST["state"];
	$zip = $_POST["zip"];
	$phone = $_POST["phone"];
	$dateReq = date('n/j/Y');
	$issue = $_POST["issue"];
	$issue2Add = addslashes($_POST["issue"]);
	$added = $_POST["added"];
		
	$compID = 0;
	$custID = $_POST["custId"];
	$contactChg = $_POST["contactChg"];
	$compChg = $_POST["compChanged"];
	$retCust = $_POST["returnCust"];
	
	//Set default computer ID and Image Name values to check later
	$imgName = "none.gif";
	$target_dir = "images/";
	$imageUploaded = false;
	
	
	
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
	
	if ($contactChg == "1") {
		//echo "Contact Changed<br />";
		
		if ($retCust=="1") {
			//This is a returning customer whose address or phone number has changed
			try {
				$sql = "UPDATE Customers SET FirstName='$fName', LastName='$lName', ".
					"Street='$street', City='$city', State='$state', Zip='$zip', Telephone='$phone' ".
					"WHERE CustomerID=$custID";
				$conn->query($sql);
			//echo "Return Customer $sql<br />";
			} catch (Exception $e) {
				echo $e.getMessage();
			}
		} else {
			//This is a new customer who has not been added to the database.
			try {
				$sql = "INSERT INTO Customers (FirstName, LastName, Street, City, State, Zip, Telephone) ";
				$sql .= "VALUES ('$fName', '$lName', '$street', '$city', '$state', '$zip', '$phone')";
				
				if ($conn->query($sql) === TRUE) {
					//echo "Customer Added<br />";
				} else {
					echo "Error: ".$sql."<br />".$conn->error;
				}
				
				$custID = $conn->insert_id;
			} catch (Exception $e) {
				echo $e.getMessage();
			}
			
		}
	}
	
	//Get values for Computers table
	$model = $_POST["model"];
	$sn = $_POST["sn"];
	if ($sn=="") {
		$sn = "---";
	}
	$login = $_POST["login"];
	$pw = $_POST["pw"];
	
	if ($compChg=="1" || $compID=="0") {
		if (isset($_POST["compSel"])) {
			$compID = $_POST["compSel"];
		} else {
			$compID = 0;
		}
		//echo "compChg=$compChg, compID=$compID<br />";
		if ($compID == "0") {
			//We are adding a new computer for this customer
			$sql = "INSERT INTO Computers (ComputerModel, ComputerSN, LogIn, Password, CustomerID_FK)".
					" VALUES ('$model', '$sn', '$login', '$pw', '$custID')";
			$conn->query($sql);
			//echo "Computer added<br />";
			
			$compID = $conn->insert_id;
		} else if ($compChg=="1") {
			//We only need to change the selected computer's data
			//(most likely, user changed user name or password)
			
			$compID = $_POST["compSel"];
			$sql = "UPDATE Computers SET ComputerModel='$model', ComputerSN='$sn', LogIn='$login', Password='$pw' WHERE ComputerID=".$compID;
			$result = $conn->query($sql);
			//echo "Change to computer data $sql<br />";
		}
	}
	
	//Now, we're ready to add the issue to the Issues table  
	
	$sqlIssue = "INSERT INTO Issues (DateRequested, ComputerID_FK, Issue, ItemsIncl, ImageName) VALUES (CURDATE(), ".$compID.", '".$issue2Add."', '".$added."', '".$imgName."')";
	try {
		$conn->query($sqlIssue);
		//echo " Issue added<br /> $sqlIssue<br />";
	} catch (Exception $e) {
		echo "Error adding issue: ".$e->getMessage();
	}
	
	
	$req = "<p>$fName $lName</p>\n";
	$req .= "<table cellpadding=\"5\"><tr>\n";
	$req .= "<td><b>Model:</b></td><td>$model</td></tr>\n<tr><td><b>Serial Number:</b></td><td>$sn</td></tr>\n";
	$req .= "<tr><td><b>Login:</b></td><td>$login</td></tr>\n<tr><td><b>Password:</b></td><td>$pw</td></tr>\n";
	$req .= "<tr><td><b>Issue:</b></td><td>$issue</td></tr>\n<tr><td valign=\"top\"><b>Additional Items<br />with Computer:</b></td><td>$added</td></tr>\n";
	$req .="</table>";
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Request Added</title>
        <link rel="stylesheet" href="repairStyles.css" type="text/css" />
    </head>
    
    <body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">
    	<img src="images/ridley.gif" alt="Ridley Lowell Business and Technical Institute" /><br />
    	<h2>Your request as been logged into the system:</h2>
        <?php echo $req; ?>
        <h4>Someone will contact you when your computer is ready.</h4>
        <a href="printSA.php" target="_blank">Print Service Agreement</a><br />
    </body>
</html>
