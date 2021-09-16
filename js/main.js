function initCollapsible() {	
	$('.collapsible').collapsible();
}

function refresh_database() {
	$("#mainContent").load("includes/refresh_database.php");
}

function refresh_client() {
	$("#mainContent").load("includes/refresh_client.php", initCollapsible);
}
