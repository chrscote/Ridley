<?php
	include("../dbConn.php");
	
	$sqlTechs = "SELECT TechID, FirstName, LastName FROM Techs WHERE CurrentStudent=True ORDER BY TechID";
	try {
		$rsTechs = $conn->query($sqlTechs);
	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>
<form action="addRemoveTech.php" method="post">
	<table cellpadding="5">
    	<tr>
        	<td colspan="2"><h2>Current Student Technicians</h2></td>
        </tr>
<?php
	while ($row = $rsTechs->fetch_array(MYSQLI_ASSOC)) {
		$id = $row["TechID"];
		$first = $row["FirstName"];
		$last = $row["LastName"];
?>        
        <tr>
        	<td><?php echo $first . " " . $last ?></td>
            <td><a href="removeTech.php?id=<?php echo $id ?>">Remove</a></td>
        </tr>
<?php
	}
?>
		<tr height="100px">
        	<td colspan="2"><a href="index.php?action=addTech">Add new Student Technician</a></td>
        </tr>
	</table>
</form>