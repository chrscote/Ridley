<?php
	//dbConn.php only makes connection to the database
	include("dbConn.php");
	//Initialize all variables to be used in form
	$lName = "";
	$fName = "";
	$street = "";
	$city = "";
	$state = "CT";
	$zip = "";
	$phone = "";
	$custID = "";
	
	$returnCust = 0;
	
	if (isset($_GET["custID"])) {
		$custID=$_GET["custID"];
		$returnCust = 1;
		$sqlCust = "SELECT LastName, FirstName, Street, City, State, Zip, Telephone FROM Customers WHERE CustomerID=".$custID;
		$resultCust = $conn->query($sqlCust);
		
		if ($resultCust->num_rows > 0) {
			$row = $resultCust->fetch_array(MYSQLI_ASSOC);
			$lName = $row["LastName"];
			$fName = $row["FirstName"];
			$street = $row["Street"];
			$city = $row["City"];
			$state = $row["State"];
			$zip = $row["Zip"];
			$phone = $row["Telephone"];
		}
		
		$sqlComp = "SELECT ComputerID AS ID, ComputerModel FROM Computers WHERE CustomerID_FK=".$custID;
		$resultComp = $conn->query($sqlComp);
	}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Ridley-Lowell Computer Repair Request</title>
        <link rel="stylesheet" href="repairStyles.css" type="text/css" />
        <script language="javascript">
			function compInfoChgd() {
				document.getElementById("compChanged").value="1";
				//alert("Computer info changed");
			}
			
			function cntctInfoChgd() {
				document.getElementById("contactChg").value="1";
			}
			
			function fillCompInfo() {
				comp = document.getElementById("compSel");
				val = comp.options[comp.selectedIndex].value;
				
				if (comp.selectedIndex != 0) {
					var xhr;
					if (window.XMLHttpRequest) {			// code for IE7+, Firefox, Chrome, Opera, Safari
						xhr=new XMLHttpRequest();
					} else {								// code for IE6, IE5
						xhr=new ActiveXObject("Microsoft.XMLHTTP");
					}
					var php = 'getCompInfo.php?compID='+val;
					//alert(php);
					xhr.open('GET', php, true);
					
					xhr.onreadystatechange = function() {
						//alert("readyStateChange");
						if (xhr.readyState == 4 && xhr.status==200) {
							//Ready and no error
							var info = JSON.parse(xhr.responseText);
							var model = info["model"];
							var sn = info["SN"];
							var login = info["login"];
							var pw = info["pw"];
							
							document.getElementById("model").value = model;
							document.getElementById("sn").value = sn;
							document.getElementById("login").value = login;
							document.getElementById("pw").value = pw;
							document.getElementById("compChanged").value=0;
						}
					}
					xhr.send();
				} else {
					document.getElementById("model").value = "";
					document.getElementById("sn").value = "";
					document.getElementById("login").value = "";
					document.getElementById("pw").value = "";
				}
			}
			function chkForm() {
				var frm = document.getElementById("repair");
				var reqFldsSet = true;
				
				if (reqFldsSet) {
					return true;
				} else {
					return false;
				}
			}
		</script>
    </head>
    
    <body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">
    	<div id="#top"><img src="images/ridley.gif" alt="Ridley Lowell Business and Technical Institute" /></div>
        <!--form action="testAddReq.php" id="repair" name="repair" method="post" enctype="multipart/form-data" onSubmit="chkForm();"-->
    	<form action="addReq.php" id="repair" name="repair" method="post" enctype="multipart/form-data" onSubmit="chkForm();">
        	<h1>Computer Repair Request</h1>
            <p>Returning customers, <a href="returnCust.php">click here</a>.</p>
            <input type="hidden" id="custId" name="custId" value="<?php echo $custID ?>" />
            <input type="hidden" id="returnCust" name="returnCust" value="<?php echo $returnCust ?>" />
            <input type="hidden" id="contactChg" name="contactChg" value="0" />
            <input type="hidden" id="compChanged" name="compChanged" value="0" />
            <table border="0" cellpadding="0" cellspacing="5">
            	<tr>
                	<td colspan="2"><label for="todayDate">Date: </label><?php echo date('n/j/Y');?><input type="hidden" id="todayDate" name="todayDate" value="<?php echo date('n/j/Y'); ?>" /></td>
                </tr>
                <tr>
                	<td colspan="2"><label for="fName">First Name*: </label> <input type="text" id="fName" name="fName" size="25" onChange="cntctInfoChgd()" required value="<?php echo $fName; ?>" /> <label for="lName">Last Name*: </label> <input type="text" id="lName" name="lName" required size="25" value="<?php echo $lName; ?>" onChange="cntctInfoChgd()" /></td>
                </tr>
                <tr>
                	<td colspan="2"><label for="address">Address*: </label> <input type="text" id="address" name="address" required size="50" onChange="cntctInfoChgd()" value="<?php echo $street; ?>" /></td>
                </tr>
                <tr>
                	<td colspan="2"><label for="city">City*: </label> <input type="text" id="city" name="city" required size="20" value="<?php echo $city; ?>" onChange="cntctInfoChgd()" /> <label for="state">State*: </label> <input type="text" id="state" name="state" size="2" maxlength="2" onChange="cntctInfoChgd()" value="<?php echo $state; ?>" /> <label for="zip">Zip*: </label> <input type="text" id="zip" name="zip" size="5" maxlength="10" onChange="cntctInfoChgd()" value="<?php echo $zip; ?>" /></td>
                </tr>
                <tr>
                	<td colspan="2"><label for="phone">Phone Number*: </label> <input type="tel" id="phone" name="phone" required value="<?php echo $phone; ?>" size="12" maxlength="15" onChange="cntctInfoChgd()" /><hr size="1" color="#999999" /></td>
                </tr>
                <tr>
                <?php if (isset($resultComp)) { ?>
                	<td colspan="2"><label for="compSel">Select the computer to be repaired or enter the information below: </label><select onChange="fillCompInfo()" id="compSel" name="compSel"><option value="0">Select one</option>
                    <?php
							while ($row = $resultComp->fetch_array(MYSQLI_ASSOC)) {
								echo "<option value=\"".$row["ID"]."\">".$row["ComputerModel"]."</option>\n";
							}
					?></select></td>
                    <?php } ?>
                </tr>
                <tr>
                	<td colspan="2"><label for="model">Computer Model/Type (i.e. Dell Inspiron 5500)*: </label> <input type="text" id="model" name="model" size="50" onChange="compInfoChgd()" required /></td>
                </tr>
                <tr>
                	<td colspan="2"><label for="sn">Computer S/N (Serial Number): </label> <input type="text" id="sn" name="sn" size="30" onChange="compInfoChgd()" /></td>
                </tr>
                <tr>
                	<td colspan="2"><label for="login">Computer Log In* (NA if none): </label> <input type="text" id="login" name="login" size="20" onChange="compInfoChgd()" required /></td>
                </tr>
                <tr>
                	<td colspan="2"><label for="pw">Computer Password* (NA if none): </label> <input type="text" id="pw" name="pw" size="20" onChange="compInfoChgd()" required /></td>
                </tr>
                <tr>
                	<td colspan="2"><em><b>*Computers must have a valid login and password to request service.</b></em><hr size="1" color="#999999" /></td>
                </tr>
                <tr>
                	<td valign="top" colspan="2"><label for="issue">Describe the issue you are having. Please be as descriptive as possible*:<br /> </label> <textarea id="issue" name="issue" cols="65" rows="10" required></textarea></td>
                </tr>
                <tr>
                	<td colspan="2"><label for="compImage">Computer Photo: </label> <input type="file" name="compImage" id="compImage" /></td>
                </tr>
                <tr>
                	<td valign="top"><label for="added">Additional Items Included with<br />Computer (i.e. AC Adapter, Wireless Mouse):<br />
                    <span id="expl">Please enter each item on separate line.</span> </label></td>
                    <td><textarea id="added" name="added" cols="55" rows="5"></textarea><br /><br /></td>
                </tr>
                <tr>
                	<td colspan="2"><a href="printSA.html" onClick="window.print();return false">Print Service Agreement</a><br /><br /></td>
                </tr>
                <tr>
                	<td colspan="2" align="center"><input type="submit" value="Submit" /></td>
                </tr>
            </table>
        </form>
	</body>
</html>
