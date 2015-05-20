//This javascript file is mainly used for the viewIssues page 
//to allow for an update to the table on the page.
function chgTable() {
	var dateFrom = document.getElementById("dateFrom").value;
	var dateTo = document.getElementById("dateTo").value;
	var ddTech = document.getElementById("tech");
	var tech = ddTech.options[ddTech.selectedIndex].value;
	
	//alert("dateFrom="+dateFrom+"\ndateTo="+dateTo+"\nTech selected="+tech);
	var tblPage = "issueTable.php";
	if (dateFrom || dateTo || tech) {
		tblPage += "?";
		var vars="";
		if (dateFrom) {
			vars += "from="+dateFrom;
		}
		if (dateTo) {
			if (vars != "") {
				vars += "&";
			}
			vars+= "to="+dateTo;
		}
		if (tech) {
			if (vars != "") {
				vars += "&";
			}
			vars += "id="+tech;
		}
		tblPage += vars;
	}
	//alert("tblPage="+tblPage);
	document.getElementById("issueTable").src = tblPage;
}