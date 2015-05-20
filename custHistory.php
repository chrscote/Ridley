<?php
	//dbConn.php only makes connection to the database
	include("dbConn.php");
	
	//id should always be sent with address 
	if (isset($_GET["id"])) {
		$custID = $_GET["id"];
		
		$sqlCust = "SELECT * FROM Customers WHERE CustomerID=$custID";
		try {
			$rsCust = $conn->query($sqlCust);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		if ($rsCust->num_rows > 0) {
			$row = $rsCust->fetch_array(MYSQLI_ASSOC);
			$fName = $row["FirstName"];
			$lName = $row["LastName"];
			$street = $row["Street"];
			$city = $row["City"];
			$state = $row["State"];
			$zip = $row["Zip"];
			$phone = $row["Telephone"];
		}
		//First get the computers that this customer has brought in to work on.
		$sqlComps = "SELECT DISTINCT Computers.ComputerID AS CompID, Computers.ComputerModel FROM Customers INNER JOIN Computers ON ".
			"Customers.CustomerID = Computers.CustomerID_FK WHERE Customers.CustomerID=$custID;";
		try {
			$rsComps = $conn->query($sqlComps);
			//$row = $rsComps->fetch();
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Customer History - <?php echo $fName. " ". $lName ?></title>
        <link rel="stylesheet" href="techStyles.css" type="text/css" />
    </head>
	<body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">
    	<img src="images/ridley.gif" alt="Ridley Lowell Business and Technical Institute" /><br />
    	<table width="70%" align="center" frame="border" rules="all">
        	<tr>
				<td colspan="5" align="left">
                	<table>
                    	<tr>
                			<td><h2 align="center">Customer History - <?php echo $fName. " ". $lName ?></h2></td>
                        </tr>
                        <tr>
                        	<td><?php echo $street; ?></td>
                        </tr>
                        <tr>
                        	<td><?php echo $city . ", ".$state." ".$zip ?></td>
                        </tr>
                        <tr>
                        	<td><?php echo $phone; ?></td>
                        </tr>
                    </table>
                </td>
			</tr>
            <tr>
            	<th>Computer Model</th>
                <th>Date Requested</th>
                <th>Issue</th>
                <th>Actions Taken</th>
                <th>Technicians</th>
            </tr>
		<?php
			try {
				$rsComps = $conn->query($sqlComps);
				//$row = $rsComps->fetch();
			} catch (Exception $e) {
				echo $e->getMessage();
			}
			//Now get the issues and actions performed for each computer this customer has brough in.
			//echo $sqlComps."<br />";
			//echo $sqlIssue."<br />CustID=".$custID."<br />";
			while ($row = $rsComps->fetch_array(MYSQLI_ASSOC)) {
				$compID = $row["CompID"];
				$sqlIssue = "SELECT DISTINCT Issues.IssueID, Issues.DateRequested, Issues.Issue FROM Customers INNER JOIN (Computers INNER JOIN Issues ".
					"ON Computers.ComputerID = Issues.ComputerID_FK) ON Customers.CustomerID = Computers.CustomerID_FK WHERE ".
					"Customers.CustomerID=$custID AND Computers.ComputerID=$compID;";
				$rsIssues = $conn->query($sqlIssue);
				$numIssues = $rsIssues->num_rows;
		?>
        	<tr>
        <?php
				if ($numIssues!=0) {
		?><td rowspan="<?php echo $numIssues ?>" valign="top"><?php echo $row["ComputerModel"]; ?></td>
        <?php
				}
				while ($rowIssue = $rsIssues->fetch_array(MYSQLI_ASSOC)) {
					$issueID = $rowIssue["IssueID"];
					$sqlActions = "SELECT DISTINCT Issues.IssueID, ActionsTaken.Action, ActionsTaken.ActionDate FROM Issues INNER JOIN ".
						"ActionsTaken ON Issues.IssueID = ActionsTaken.IssueID_FK WHERE Issues.IssueID=$issueID ORDER BY ActionsTaken.ActionDate;";
					$rsActions = $conn->query($sqlActions);
					//echo $sqlActions."<br />";
		?>
        		<td align="center" valign="top"><?php echo date('n/j/Y', strtotime($rowIssue["DateRequested"])) ?></td>
        		<td valign="top"><?php echo $rowIssue["Issue"] ?></td>
        		<td>
                	<table border="0">
                    	<?php
							if ($rsActions->num_rows > 0) {
								while ($rowAct = $rsActions->fetch_array(MYSQLI_ASSOC)) {
									echo "<tr>\n";
									echo "	<td valign=\"top\">". date('n/j/Y',strtotime($rowAct["ActionDate"]))."</td>\n";
									echo "	<td><img src=\"images/trnsp.gif\" width=\"5\" height=\"1\" /></td>";
									echo "	<td>".$rowAct["Action"]."</td>\n";
									echo "</tr>";
								}
							}
						?>
                    </table>
                </td>
                <td valign="top"><?php
                	$sqlTechs = "SELECT DISTINCT Issues.IssueID, Techs.FirstName, Techs.LastName FROM (Techs INNER JOIN (Issues INNER JOIN ".
						"Assignments ON Issues.IssueID = Assignments.IssueID_FK) ON Techs.TechID = Assignments.TechID_FK) INNER JOIN ActionsTaken ON ".
						"(Techs.TechID = ActionsTaken.TechID_FK) AND (Issues.IssueID = ActionsTaken.IssueID_FK) WHERE Issues.IssueID=$issueID;";
					$rsTechs = $conn->query($sqlTechs);
					//echo $sqlTechs."<br />";
					
					if ($rsTechs->num_rows>0) {
						while ($rowTech = $rsTechs->fetch_array(MYSQLI_ASSOC)) {
							echo $rowTech["FirstName"]." ".$rowTech["LastName"]."<br />";
						}
					}
				 ?></td>
            </tr>
        <?php
				}
			} 
		?>
        	<tr>
            	<td colspan="5"><a href="techs.php">Return to Issue List</a>
            </tr>
        </table>
	</body>
</html>