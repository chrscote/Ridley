<?php
	error_reporting(E_ALL | E_NOTICE);
    ini_set('display_errors', '1');
	
	//dbConn.php only makes connection to the database
	include("dbConn.php");
	
	//Only want to display issues that are still open (not signed off by customer)
	$sqlIssues = "SELECT Customers.CustomerID, Customers.LastName, Customers.FirstName, Issues.IssueID, Issues.Issue, Issues.DateRequested, ".
		"Status.Status, Computers.ComputerModel, Issues.DateReceived FROM Status INNER JOIN (Customers INNER JOIN (Computers INNER JOIN Issues ON ".
		"Computers.ComputerID = Issues.ComputerID_FK) ON Customers.CustomerID = Computers.CustomerID_FK) ON Status.StatusID = Issues.Status ".
		"WHERE Issues.Status < 6 ORDER BY Issues.IssueID";
	try {
		$rsIssues = $conn->query($sqlIssues);
		//echo $sqlIssues."<br />";
	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Ridley-Lowell Tech Page</title>
        <link rel="stylesheet" href="techStyles.css" type="text/css" />
    </head>
    
   <body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">
    	<img src="images/ridley.gif" alt="Ridley Lowell Business and Technical Institute" /><br />
    	<table border="1" width="100%">
        	<tr>
            	<th>Date<br />Req</th>
            	<th>Name</th>
            	<th>Model</th>
            	<th width="18%">Issue</th>
            	<th width="10%">Tech<br />Assigned</th>
            	<th width="18%">Actions<br />Taken</th>
            	<th width="18%">Current Status</th>
                <th width="4%">&nbsp;</th>
            </tr>
<?php
		while ($row = $rsIssues->fetch_array(MYSQLI_ASSOC)) {
			//echo "In while<br />";
			$issueID = $row["IssueID"];
			$custID = $row["CustomerID"];
			
			$dateReq = date('n/j/y', strtotime($row["DateRequested"]));
			
			$lastName = $row["LastName"];
			$firstName = $row["FirstName"];
			$model = $row["ComputerModel"];
			$issue = $row["Issue"];
			$dateReceived = $row["DateReceived"];
			if ($dateReceived!="") {
				$dateReceived = date('n/j/y', strtotime($dateReceived));
			}
			$status = $row["Status"];		//Actual Status string
			$techs = "";
			
			//Now get the technician(s) assigned to this issue
			$sqlTechs = "SELECT Techs.FirstName, Techs.LastName FROM Techs INNER JOIN (Issues INNER JOIN Assignments ON ".
				"Issues.IssueID = Assignments.IssueID_FK) ON Techs.TechID = Assignments.TechID_FK WHERE Issues.IssueID=$issueID AND ".
				"Assignments.DateEnd IS NULL";
			try {
				$rsTechs = $conn->query($sqlTechs);
			} catch (Exception $e) {
				echo $e->getMessage();
			}
			
			//Now we need to get all actions performed for each issue.
			//This needs to be done separately because they are separated out by who performed them for
			//instructor's report
			$sqlActions = "SELECT DISTINCT ActionsTaken.ActionDate, ActionsTaken.Action FROM Issues INNER JOIN ActionsTaken ON ".
				"Issues.IssueID = ActionsTaken.IssueID_FK WHERE Issues.IssueID=$issueID";
			try {
				$rsActions = $conn->query($sqlActions);
			} catch (Exception $e) {
				echo $e->getMessage();
			}
?>
			<tr>
            	<td valign="top"><?php echo $dateReq; ?></td>
            	<td valign="top"><a href="custHistory.php?id=<?php echo $custID ?>"><?php echo $firstName ." ".$lastName; ?></a></td>
            	<td valign="top"><?php echo $model; ?></td>
            	<td valign="top"><?php echo $issue; ?></td>
            	<td valign="top"><?php 
						while ($rowTech = $rsTechs->fetch_array(MYSQLI_ASSOC)) {
							echo $rowTech["FirstName"]." ".$rowTech["LastName"]."<br />";
						} ?></td>
            	<td valign="top"><?php 
						while ($rowAction = $rsActions->fetch_array(MYSQLI_ASSOC)) {
							echo nl2br($rowAction["Action"])."<br />";
						} ?></td>
            	<td valign="top"><?php echo $status; ?></td>
                <td valign="top"><a href="editIssue.php?id=<?php echo $issueID ?>">Edit</a></td>
            </tr>
<?php			
		}
?>
        </table>
		<a href="index.html">Return to main</a>
    </body>
</html>