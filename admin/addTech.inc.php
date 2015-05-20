<form id="addTech" action="insertStudent.php" method="post">
	<table cellpadding="5" width="75%">
    	<tr>
        	<td colspan="2"><h2>Add New Student</h2></td>
        </tr>
    	<tr>
        	<td colspan="2">Enter the student's first and last names in the boxes provided:</td>
        </tr>
        <tr>
        	<td align="right">First Name:&nbsp;</td>
            <td><input type="text" name="fName" size="15" required />
        </tr>
        <tr>
        	<td align="right">Last Name:&nbsp;</td>
            <td><input type="text" name="lName" size="15" required />
        </tr>
        <tr>
        	<td colspan="2" align="center"><input type="submit" value="Add Student" /></td>
        </tr>
    </table>
</form>