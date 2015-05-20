<?php
	include("../dbConn.php");
	if (isset($_GET["id"])) {
		$id = $_GET["id"];
		
		$sqlRemove = "UPDATE Techs SET CurrentStudent=False WHERE TechID=$id";
		
		try {
			$conn->query($sqlRemove);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	header("Location:index.php?action=addRemTech");
?>