<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="description" content="A process that retrieves PHP repository information.">
	<meta name="author" content="priyanka.taluchuri@gmail.com">

	<title>PHP Assessment</title>
	
	<!-- Let's use JQuery here to make dom traversal and manipulation easier -->
	<!-- Usually we target the CDN, but having a local copy gives a little more certainty -->
	<!-- In production, use the minified version, v3.3.1 -->
	<script src="js/jquery.js"></script>
	<!-- Using Materialize, minified v0.100.2 -->
	<script src="materialize/js/materialize.min.js"></script>
	<link rel="stylesheet" href="materialize/css/materialize.min.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!-- adding our own stylesheet -->
	<link rel="stylesheet" href="style.css">
	
</head>
<body>
	<div class="mainContainer">
		<div class="card white topRow">
			<div class="card-content">
				<span class="card-title" style="text-align: center">PHP Assessment</span>
				<a class="waves-effect waves-light blue darken-2 btn" onclick="refresh_client()" id="btnRefreshClient">
					<i class="material-icons right">refresh</i>REFRESH CLIENT
				</a>
				<a style="float:right;" class="waves-effect waves-light blue darken-2 btn" onclick="refresh_database()" id="btnRefreshDB ">
					<i class="material-icons right">refresh</i>REFRESH DATABASE
				</a>
			</div>
		</div>
		<div id="mainContent">
      <!-- Display the decoded json results or success/error messages here -->
		</div>
	</div>
	<script src="js/main.js"></script>
</body>
</html>
