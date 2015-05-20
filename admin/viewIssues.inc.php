<?php
	//dbConn.php only makes connection to the database
	include("../dbConn.php");
	
	if (isset($_GET["id"])) {
		$stuID = $_GET["id"];
		//First get the name of the stu
		$sqlStudent = "SELECT FirstName, LastName FROM Techs WHERE TechID=$stuID";
		try {
			$rsStu = mysql_($sqlStudent);
		} catch (Exception $exc) {
			echo $exc->getMessage();
		}
		$row = $rsStu->fetch_array(MYSQLI_ASSOC);
		$fName = $row["FirstName"];
		$lName = $row["LastName"];
	}
	
	$sqlTechs = "SELECT TechID, FirstName, LastName FROM Techs ORDER BY LastName";
	try {
		$rsTechs = $conn->query($sqlTechs);
	} catch (Exception $e) {
		echo $e->getMessage();	
	}
	
?>
<h2>Issues Performed by Student</h2>
<p>Select Date Range and Student to view actions performed.</p>
<table>
	<tr>
    	<td align="center">
        	From: <input type="date" id="dateFrom" placeholder="mm/dd/yyyy" onChange="chgTable();" /> 
            To: <input type="date" id="dateTo" placeholder="mm/dd/yyyy" onChange="chgTable();" />
        	Technician: <select id="tech" name="tech" onChange="chgTable();">
            	<option value=""></option>
        	<?php
				while ($row = $rsTechs->fetch_array(MYSQLI_ASSOC)) {
					$id = $row["TechID"];
					$fName = $row["FirstName"];
					$lName = $row["LastName"];
					echo "<option value=\"$id\">$fName $lName</option>\n";
			?>
            <?php
				}
			?>
                
            </select>
        </td>
    </tr>
	<tr>
    	<td valign="top" align="left">
        	<table width="100%">
        	<?php
				while ($row = $rsTechs->fetch_array(MYSQLI_ASSOC)) {
					$id = $row["TechID"];
					$fName = $row["FirstName"];
					$lName = $row["LastName"];
			?><tr>
            	<td><a href="index.php?action=view&id=<?php echo $id ?>"><?php echo $fName." ".$lName ?></a></td>
              </tr>
			<?php
				}
			?>
            </table>
        </td>
    </tr>
    <tr>
    	<td><iframe id="issueTable" name="issueTable" width="900" height="600" frameborder="0" src="issueTable.php" /></td>
    </tr>
</table>