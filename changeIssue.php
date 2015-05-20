<?php
	//dbConn.php only makes connection to the database
	include("dbConn.php");
	
	$issueID = $_POST["issueID"];
	$status = $_POST["status"];
	$origTechs = $_POST["asgnTechs"];		//Original assigned techs before any changes
	
	//Put the original technicians' IDs into an array for use with array search functionality.
	$arrOrigTech = explode(",", $origTechs);
	
	$arrCurrTech = $_POST["currTechs"];			//From Tech(s) Assigned list box (in array)
	
	$newActions = $_POST["newActions"];
	
	$newActionDate = $_POST["newActDate"];
	$newActDate = date('Y/n/j', strtotime($newActionDate));
	
	$chgIssueSql = "UPDATE Issues SET Status=$status WHERE IssueID=$issueID";
	$conn->query($chgIssueSql);
	
	if ($origTechs=="") {
		//This issue is being assigned for the first time.
		for ($n=0; $n < sizeof($arrCurrTech); $n++) {
			$sqlAdd = "INSERT INTO Assignments (TechID_FK, IssueID_FK, DateStart) VALUES ($arrCurrTech[$n], $issueID, ".date('n/j/Y').")";
			$conn->query($sqlAdd);
		}
	} else {
		//This issue had been assigned to someone already.
		//Need to determine if removed, added, or completely changed techs
		for ($n=0; $n < sizeof($arrOrigTech); $n++) {
			$origTech = $arrOrigTech[$n];
			if (!in_array($origTech, $arrCurrTech)) {
				$dateEnd = date('n/j/Y');
				$sqlChg = "UPDATE Assignments SET DateEnd=".date('n/j/Y')." WHERE IssueID_FK=$issueID AND TechID_FK=$origTech";
				$conn->query($sqlChg);
			}
		}
		//Change the dateEnd if the customer has signed off
		if ($status==6) {
				$dateEnd = date('n/j/Y');
				$sqlEnd = "UPDATE Assignments SET DateEnd=$dateEnd WHERE IssueID_FK=$issueID";
				try {
					$conn->query($sqlEnd);
					echo $sqlEnd."<br />";
				} catch (Exception $e) {
					echo $e->getMessage();
				}
		}
		for ($n=0; $n<sizeof($arrCurrTech); $n++) {
			$currTech = $arrCurrTech[$n];
			//echo "CurrTech=".$currTech."<br />";
			if (!in_array($currTech, $arrOrigTech)) {
				$dateAsgn = date('n/j/Y');
				$techID = $arrCurrTech[$n];
				$sqlAdd = "INSERT INTO Assignments (TechID_FK, IssueID_FK, DateStart) VALUES ($arrCurrTech[$n], $issueID, ".date('n/j/Y').")";
				$conn->query($sqlAdd);
			}
		}
	}
	//The form will check to make sure that user enters both a date and the action
	if ($newActions!="") {
		for ($n=0; $n < sizeof($arrCurrTech); $n++) {
			$currTech = $arrCurrTech[$n];
			
			//$stmtAddAction->execute();
			$addAction = "INSERT INTO ActionsTaken (TechID_FK, IssueID_FK, Action, ActionDate) VALUES ($arrCurrTech[$n], $issueID, \"$newActions\", \"$newActDate\")";
			echo $addAction."<br />";
			try {
				$conn->query($addAction);
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}
	}
	header("Location:techs.php");
?>