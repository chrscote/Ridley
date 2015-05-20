<?php
	//dbConn.php only makes connection to the database
	include("dbConn.php");
	
	//Set up main variables to be used in the form
	$custID = 0;
	$custName = "";
	$compModel = "";
	$login = "";
	$pw = "";
	$issue = "";
	$statusVal = "";
	$actions = "";
	
	//To format date use: date('n/j/y',strtotime(dateVariable)
	
	//Need to get list of status values for drop-down
	$sqlStatuses = "SELECT StatusID, Status FROM Status";
	try {
		$rsStatuses = $conn->query($sqlStatuses);
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	
	//This query grabs all technicians who are current students
	//This is used in case a student who started working on it has graduated.
	$sqlAllTechs = "SELECT TechID, FirstName, LastName FROM Techs WHERE CurrentStudent=TRUE ";
	try {
		$rsAllTechs = $conn->query($sqlAllTechs);
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	
	
	//When linking to this page from technician page, issue id is included in GET string as id variable
	//Otherwise, we are returning here from the same page to update data in the database
	if (isset($_GET["id"])) {
		$issueID = $_GET["id"];
		
		$sqlIssue = "SELECT Customers.CustomerID, Customers.FirstName, Customers.LastName, Computers.ComputerModel, Computers.LogIn, Computers.Password, ".
			"Issues.DateRequested, Issues.Issue, Issues.Status, Issues.ImageName FROM (Customers INNER JOIN Computers ON ".
			"Customers.CustomerID = Computers.CustomerID_FK) INNER JOIN Issues ON Computers.ComputerID = Issues.ComputerID_FK ".
			"WHERE Issues.IssueID=$issueID";
		try {
			$rsIssue = $conn->query($sqlIssue);
			
			if ($rsIssue->num_rows > 0) {
				$rowIssue = $rsIssue->fetch_array(MYSQLI_ASSOC);
				//Now that I have successfully retrieved the main info, place it in variables
				//If this was done outside of the try-catch, I'd need to check status of rowIssue
				$custID = $rowIssue["CustomerID"];
				$custName = $rowIssue["FirstName"]. " ".$rowIssue["LastName"];
				$compModel = $rowIssue["ComputerModel"];
				$login = $rowIssue["LogIn"];
				$pw = $rowIssue["Password"];
				$issue = $rowIssue["Issue"];
				$dateReq = date('n/j/y', strtotime($rowIssue["DateRequested"]));
				$imgName = $rowIssue["ImageName"];
				$statusVal = $rowIssue["Status"];		//Numeric value associated with StatusID in Status table
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		
		//Now, get the technician(s) currently assigned to this issue (there is an
		//option of having 2 or more techs working the same issue at the same time
		$sqlCurrTechs = "SELECT Techs.TechID, Techs.FirstName, Techs.LastName FROM Techs INNER JOIN (Issues INNER JOIN Assignments ".
			"ON Issues.IssueID = Assignments.IssueID_FK) ON Techs.TechID = Assignments.TechID_FK WHERE Issues.IssueID=$issueID ".
			"AND Assignments.DateEnd IS NULL ORDER BY Techs.TechID";
		try {
			$rsCurrTechs = $conn->query($sqlCurrTechs);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		
		//Finally, get any actions that have been performed for this issue
		$sqlActions = "SELECT DISTINCT ActionsTaken.ActionDate, ActionsTaken.Action FROM Issues INNER JOIN ActionsTaken ON ".
			"Issues.IssueID = ActionsTaken.IssueID_FK WHERE Issues.IssueID=$issueID";
		try {
			$rsActions = $conn->query($sqlActions);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Ridley Lowell Edit Actions Page</title>
        <link rel="stylesheet" href="repairStyles.css" type="text/css" />
        <script language="javascript">
		function moveTech(srcID, destID) {
			var src = document.getElementById(srcID);
			var dest = document.getElementById(destID);
		 
			for (var count=0; count < src.options.length; count++) {
				if(src.options[count].selected == true) {
						var option = src.options[count];
		 
						var newOption = document.createElement("option");
						newOption.value = option.value;
						newOption.text = option.text;
						newOption.selected = true;
						try {
								 dest.add(newOption, null); //Standard
								 src.remove(count, null);
						 }catch(error) {
								 dest.add(newOption); // IE only
								 src.remove(count);
						 }
						count--;
				}
			}
		}
		
		function chkForm() {
			var rtnVal = true;
			var newActions = document.getElementById("newActions").value;
			var newActDate = document.getElementById("newActDate").value;
			
			if (newActDate == "" && newActions != "") {
				alert("Please enter the date when the new action(s) were performed.");
				rtnVal = false;
			} else if (newActions=="" && newActDate != "") {
				alert("Please enter the new action(s) that were performed.");
				rtnVal = false;
			}
			if (rtnVal) {
				selectTechs();
			}
			return rtnVal;
		}
		
		function selectTechs() {
			var ddTechs = document.getElementById("currTechs");
			for (var n=0; n<ddTechs.options.length; n++) {
				ddTechs.options[n].selected = true;
				//alert(ddTechs.options[n].value);
			}
		}
		</script>
    </head>
    
   <body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">
    	<img src="images/ridley.gif" alt="Ridley Lowell Business and Technical Institute" /><br />
        <form id="editAction" name="editAction" method="post" action="changeIssue.php" onSubmit="return chkForm();">
        	<input type="hidden" name="issueID" id="issueID" value="<?php echo $issueID ?>" />
            <h2><a href="custHistory.php?id=<?php echo $custID ?>"><?php echo $custName; ?></a></h2>     
            <h3><?php echo $compModel; ?></h3>
            <table border="0" cellspacing="10">
            	<tr>
                	<td><b>Login: </b></td>
                    <td><?php echo $login; ?></td>
                    <td><b>Password: </b></td>
                    <td><?php echo $pw; ?></td>
                    <td rowspan="7"><img src="images/<?php echo $imgName ?>" height="350" /></td>
                </tr>
            	<tr>
                	<td><b>Issue: </b></td>
                    <td colspan="3"><?php echo $issue; ?></td>
                </tr>
            	<tr>
                	<td><b>Date Requested: </b></td>
                    <td><?php echo $dateReq;  ?></td>
                    <td><b>Status: </b></td>
                    <td><select id="status" name="status">
                    	<?php
							while ($row = $rsStatuses->fetch_array(MYSQLI_ASSOC)) {
								$selected = "";
								if ($row["StatusID"] == $statusVal)
									$selected = " selected=\"selected\"";
								echo "<option value=\"".$row["StatusID"]."\"$selected>".$row["Status"]."</option>";
							}
						?>
                    </select></td>
                </tr>
                <tr>
                	<td colspan="4"><img src="images/trnsp.gif" width="1" height="10" /></td>
                </tr>
                <tr>
                	<td colspan="4" align="center">
                    	<!-- Splitting techs into separate table within this table cell-->
                        <table>
                        	<tr>
                            	<td align="center"><b>Available Techs</b></td>
                                <td colspan="3">&nbsp;</td>
                                <td align="center"><b>Tech(s) Assigned</b></td>
                            </tr>
                            <tr>
                            	<td align="center"><select id="availTechs" name="availTechs[]" size="4" multiple="multiple">
                                	<?php
										while ($rowTech = $rsAllTechs->fetch_array(MYSQLI_ASSOC)) {
											$techID = $rowTech["TechID"];
											$techName = $rowTech["FirstName"]." ".$rowTech["LastName"];
											$currTech = 0;
											$currID = -1;
											while ($rowCurr = $rsCurrTechs->fetch_array(MYSQLI_ASSOC)) {
												$currID = $rowCurr["TechID"];
												if ($currID==$techID) {
													echo "Current tech ".$currID."<br />";
													$currTech = 1;
													break;
												}
											}
											mysqli_data_seek($rsCurrTechs, 0);
											if ($currTech != 1) {
												echo "<option value=\"".$techID."\">".$techName."</option>";
											}
										}
									?>
                                	</select>
                                </td>
                                <td><img src="images/trnsp.gif" width="10" height="1" /></td>
                            	<td><input type="button" id="addTech" value=">" onClick="javascript:moveTech('availTechs', 'currTechs');" /><br /><br />
                                		<input type="button" id="remTech" value="<" onClick="javascript:moveTech('currTechs', 'availTechs');" /></td>
                                <td><img src="images/trnsp.gif" width="10" height="1" /></td>
                                <td align="center"><select id="currTechs" name="currTechs[]" size="4" multiple="multiple">
                                	<?php
										$hiddenVal = "";
										while ($rowTech = $rsCurrTechs->fetch_array(MYSQLI_ASSOC)) {
											$techID = $rowTech["TechID"];
											$techName = $rowTech["FirstName"]." ".$rowTech["LastName"];
											echo "<option value=\"".$techID."\">".$techName."</option>";
											$hiddenVal .= $techID.",";
										}
										$hiddenVal = substr($hiddenVal, 0, strlen($hiddenVal)-1);
									?>
                                	</select>
                                    <input type="hidden" id="asgnTechs" name="asgnTechs" value="<?php echo $hiddenVal ?>" />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                	<td colspan="4" align="center"><b>Actions Performed</b><br />
                    	<table width="75%" cellspacing="15">
                    	<?php
							while ($row = $rsActions->fetch_array(MYSQLI_ASSOC)) {
								echo "<tr><td valign=\"top\">".date('n/j/y',strtotime($row["ActionDate"]))."</td>\n";
								echo "<td>".nl2br($row["Action"])."</td></tr>\n";
							}
						?>
                        	<tr>
                            	<td valign="top"><input type="date" id="newActDate" name="newActDate" /></td>
                                <td><textarea id="newActions" name="newActions" cols="40" rows="5"></textarea></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                	<td colspan="4" align="center"><input type="submit" value="Submit" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Cancel" /></td>
                </tr>
            </table>
        </form>
	</body>
</html>