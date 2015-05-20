<?php
	//dbConn.php only makes connection to the database
	include("../dbConn.php");
	
	$stuID = "";
	$from = "";
	$to = "";
	$where = "";
	$vals = "";
	
	if (isset($_GET["id"]))
		$stuID = $_GET["id"];
	if (isset($_GET["from"]))
		$from = $_GET["from"];
	if (isset($_GET["to"]))
		$to = $_GET["to"];
	
	$sqlIssues = "SELECT Customers.FirstName, Customers.LastName, Computers.ComputerModel, Issues.Issue, Issues.DateRequested, ".
		"Issues.DateReceived, ActionsTaken.Action, Techs.TechID FROM Techs INNER JOIN (((Customers INNER JOIN Computers ON ".
		"Customers.CustomerID = Computers.CustomerID_FK) INNER JOIN Issues ON Computers.ComputerID = Issues.ComputerID_FK) INNER JOIN ".
		"ActionsTaken ON Issues.IssueID = ActionsTaken.IssueID_FK) ON Techs.TechID = ActionsTaken.TechID_FK ";
	if ($stuID != "" || $from!="" || $to!="") {
		$where = "WHERE ";
		if ($stuID != "") {
			$vals .= "Techs.TechID = $stuID ";
		}
		if ($from != "") {
			if ($vals != "") 
				$vals .= "AND ";
			$vals .= "Issues.DateRequested >= \"$from\" ";
		}
		if ($to != "") {
			if ($vals != "")
				$vals .= "AND ";
			$vals .= "Issues.DateRequested <= \"$to\" ";
		}
	}
	$sqlIssues .= $where. $vals . "ORDER BY Issues.DateRequested";
	//echo $sqlIssues."<br />";
	try {
		$rsIssues = $conn->query($sqlIssues);
		//echo $sqlIssues."<br />";
	} catch (Exception $ex) {
		echo $ex->getMessage();	
	}
?>
	<table width="100%" rules="rows" cellpadding="8">
		<tr>
			<th>Date Requested</th>
			<th>Customer Name</th>
			<th>Computer Model</th>
			<th>Issue</th>
			<th>Actions Performed</th>
		</tr>
		<?php
			while ($row = $rsIssues->fetch_array(MYSQLI_ASSOC)) {
				$custName = $row["FirstName"]." ".$row["LastName"];
				$compModel = $row["ComputerModel"];
				$issue = $row["Issue"];
				$dateReq = $row["DateRequested"];
				$dateRec = $row["DateReceived"];
				$actions = $row["Action"];
		?>
		<tr>
			<td><?php echo date('n/j/Y',strtotime($dateReq)) ?></td>
			<td><?php echo $custName ?></td>
			<td align="center"><?php echo $compModel ?></td>
			<td><?php echo $issue ?></td>
			<td><?php echo $actions ?></td>
		</tr>
		<?php
		}
	?>
	</table>