<form action="validatePWChg.php" method="post">
  <table>
    	<tr>
        	<td colspan="2">Enter a new password. It is highly recommended that you use a secure password (using a combination
            	of uppercase, lowercase, numbers, and special characters).</td>
        </tr>
        <tr>
            <td align="right">Old Password:&nbsp;</td>
            <td>&nbsp;<input type="password" size="15" name="oldPW" id="oldPW" /></td>
        </tr>
        <tr>
            <td align="right">New Password:&nbsp;</td>
            <td>&nbsp;<input type="password" size="15" name="newPW" id="newPW" /></td>
        </tr>
        <tr>
            <td align="right">Confirm Password:&nbsp;</td>
            <td>&nbsp;<input type="password" size="15" name="confirmPW" id="confirmPW" /></td>
        </tr>
        <tr>
            <td align="center" colspan="2"><input type="submit" value="Change Password" />&nbsp;&nbsp;<input type="button" value="Cancel" onClick="javascript:window.location='index.php'" /></td>
        </tr>
    </table>
</form>