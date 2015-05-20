<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Ridley-Lowell Returning Customer</title>
        <link rel="stylesheet" href="repairStyles.css" type="text/css" />
        <script language="javascript">
			function fillNameInfo() {
				alert("Hello");
				var fName = document.getElementById("fName").value;
				var lName = document.getElementById("lName").value;
				
				var xhr;
				if (window.XMLHttpRequest) {			// code for IE7+, Firefox, Chrome, Opera, Safari
					xhr=new XMLHttpRequest();
				} else {								// code for IE6, IE5
					xhr=new ActiveXObject("Microsoft.XMLHTTP");
				}
				var php = 'getAddresses.php?lName=' + lName + '&fName=' + fName;
				//alert(php);
				xhr.open('GET', php, true);
				
				xhr.onreadystatechange = function() {
					//alert("readyStateChange");
					if (xhr.readyState == 4 && xhr.status==200) {
						var addresses = JSON.parse(xhr.responseText);
						txt = "<table border=\"0\">";
						if (addresses.length > 0) {
							for (n=0; n<addresses.length; n++) {
								txt += "<tr>\n";
								txt += "<td>\n";
								txt += addresses[n]["fName"]+" "+addresses[n]["lName"]+"<br />\n";
								txt += addresses[n]["street"]+"<br />\n";
								txt += addresses[n]["city"]+", "+addresses[n]["state"]+" "+addresses[n]["zip"]+"<br />\n";
								txt += addresses[n]["phone"]+"<br /><br /></td>\n\n";
								txt += "<td valign=\"top\"><a href=\"main.php?custID="+addresses[n]["id"]+"\">Select</a></td>";
								txt += "<td valign=\"top\"><a href=\"editCust.php?custID="+addresses[n]["id"]+"\">Edit</a></td>";
								txt += "</tr>";
							}
							document.getElementById("names").innerHTML = txt;
						} else {
							document.getElementById("names").innerHTML = "No addresses match that name. "+ php;
						}
					}
				}
				xhr.send();
			}
		</script>
    </head>
    
    <body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">
    	<img src="images/ridley.gif" alt="Ridley Lowell Business and Technical Institute" /><br />
    	<form name="return" id="return" method="post" action="">
        Enter your first and last names:<br />
        <input type="text" name="fName" id="fName" size="12" placeholder="First Name" onChange="fillNameInfo()" /> <input type="text" name="lName" id="lName" size="12" placeholder="Last Name" onChange="fillNameInfo()" /> <button type="button" id="find" name="find" value="Find" onClick="fillNameInfo()">Find</button>
        </form>
        <div id="names">
        </div>
    </body>
</html>