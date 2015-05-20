<?php
	session_start();
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Ridley-Lowell Administrative Tools</title>
        <link rel="stylesheet" href="../techStyles.css" type="text/css" />
        <script language="javascript" src="adminFuncs.js"></script>
    </head>
	<?php
        include("../dbConn.php");
    ?>
	<body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">
    	<img src="../images/ridley.gif" alt="Ridley Lowell Business and Technical Institute" /><br />
        <table cellspacing="10" cellpadding="5">
        	<tr>
				<?php
                    if (isset($_SESSION["admin"])) {
                ?>
            	<td rowspan="2" valign="top"><h3>Admin Tools</h3><br />
                		<a href="index.php?action=addRemTech">Add/Remove Technicians</a><br />
                        <a href="index.php?action=view">View Issues and Results</a><br />
                        <a href="index.php?action=pwCh">Change Password</a><br />
                        <a href="index.php?action=logout">Log Out</a>
                </td>
				<?php
                    }
                ?>
                <td>
                	<?php
						if (!isset($_SESSION["admin"])) {
							include("login.inc.php");
						} else {
							if (isset($_GET["action"])) {
								
								switch ($_GET["action"]) {
									case "addTech":
										include("addTech.inc.php");
										break;
									case "addRemTech":
										include ("addRemTech.inc.php");
										break;
									case "pwCh":
										include ("pwChange.inc.php");
										break;
									case "view":
										include ("viewIssues.inc.php");
										break;
									case "logout":
										session_unset();
										session_destroy();
										header('Location: index.php');
										break;	
								}
							} else {
								echo "<p>Please select an item from the menu at the left.</p>";	
							}
						}
					?>
                </td>
            </tr>
        </table>
    </body>
</html>