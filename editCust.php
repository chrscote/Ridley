<?php
	//dbConn.php only makes connection to the database
	include("dbConn.php");
	
	$custID = $_GET["custID"];
	
	$sql = "SELECT LastName, FirstName, Street, City, State, Zip, Telephone FROM Customers WHERE CustomerID=".$custID;
	$result = $conn->query($sql);
	
	if ($result->mysqli_num_rows > 0) {
		$row = $result->mysqli_fetch_array(MYSQLI_ASSOC);
		$fName = $row["FirstName"];
		$lName = $row["LastName"];
		$street = $row["Street"];
		$city = $row["City"];
		$state = $row["State"];
		$zip = $row["Zip"];
		$phone = $row["Telephone"];
	}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Ridley-Lowell Edit Customer Information</title>
        <link rel="stylesheet" href="repairStyles.css" type="text/css" />
    </head>
    
    <body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">
    	<div id="#top"><img src="images/ridley.gif" alt="Ridley Lowell Business and Technical Institute" /></div>
    	<form name="editCust" id="editCust" method="post" action="changeCust.php">
        	<input type="hidden" id="editCust" name="editCust" value="1" />
            <input type="hidden" id="custID" name="custID" value="<?php echo $custID ?>" />
            <table border="0" cellpadding="0" cellspacing="5">
            	<tr>
                	<td><h2>Please make any changes to your current address or phone number.</h2></td>
                </tr>
                <tr>
                	<td><label for="lName">Last Name: </label> <input type="text" id="lName" name="lName" size="25" value="<?php echo $lName; ?>" /> &nbsp;&nbsp;&nbsp;<label for="fName">First Name: </label> <input type="text" id="fName" name="fName" size="25" value="<?php echo $fName; ?>" /></td>
                </tr>
                <tr>
                	<td><label for="address">Address: </label> <input type="text" id="street" name="street" size="50" value="<?php echo $street; ?>" /></td>
                </tr>
                <tr>
                	<td><label for="city">City: </label> <input type="text" id="city" name="city" size="20" value="<?php echo $city; ?>" /> <label for="state">State: </label> <input type="text" id="state" name="state" size="2" maxlength="2" value="<?php echo $state; ?>" /> <label for="zip">Zip: </label> <input type="text" id="zip" name="zip" size="5" maxlength="10" align="right" value="<?php echo $zip; ?>" /></td>
                </tr>
                <tr>
                	<td><label for="phone">Phone Number: </label> <input type="tel" id="phone" name="phone" value="<?php echo $phone; ?>" size="12" maxlength="15" /></td>
                </tr>
                <tr>
                	<td align="center"><input type="submit" value="Submit Changes" /><hr size="1" color="#999999" /></td>
                </tr>
			</table>
        </form>
    </body>
</html>