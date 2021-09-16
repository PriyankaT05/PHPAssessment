<?php
	
	include('../includes/show_message.php');
	
	// ini_set('display_errors', 'On');
    // error_reporting(E_ALL);
    
    // log the flow for debugging
	$log = fopen("debug.log", 'w');
	fwrite($log, "Request to refresh database received.\r\n");
	ob_start();		// This is done to collect warnings and such, in order to get more control over error reporting.
	
	// connect to MySQL server and see if php_assessment database exists
	$database = mysqli_connect("localhost", "root", "", "php_assessment");
	
	$db_check = mysqli_query($database, "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'php_assessment';");
	if (mysqli_num_rows($db_check) == 0) {
		fwrite($log, "Couldn't connect to MySQL database 'php_assessment' via localhost address/hostname.\r\n");
		fclose($log);
		$err = "Couldn't connect to MySQL database 'php_assessment' via localhost address/hostname. Make sure you have created a 'php_assessment' database in your MySQL server instance.";
		sendToClient($err);
		exit();
	}
	
	fwrite($log, "Found php_assessment database in MySQL server instance.\r\n");
	
	// check to see if table 'repositories' exists.. if not, create it
	$table_check = mysqli_query($database, "SHOW TABLES LIKE 'repositories';");
	if (mysqli_num_rows($table_check) == 0) {
		$create_query = file_get_contents("create_table.sql");
		$success = mysqli_query($database, $create_query);
		if (!$success) {
			fwrite($log, "Failed to create repositories table in db.\r\n");
			fclose($log);
			sendToClient("Failed to create repositories table in db.");
			exit();
		}
	}
	
	fwrite($log, "Found or created repositories table.\r\n");
	
	
	// // actually get repository data from github
	$curl = curl_init();
	fwrite($log, "curl session initialized.\r\n");
	curl_setopt($curl, CURLOPT_URL, "https://api.github.com/search/repositories?q=stars:15000..33000+language:php+is:public");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);	// specify v3 for github api
	
	// set header values for GitHub API
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		"Accept: application/json",
		"User-Agent: php_assessment"
	));
	fwrite($log, "curl options set.\r\n");
	
	// execute!
	$json_string = curl_exec($curl);
	fwrite($log, "curl session finished.\r\n");
	
	// cleanup
	curl_close($curl);
	fwrite($log, "curl session closed.\r\n");
		
	if (!$json_string) {
		fwrite($log, "cURL session failed.\r\n");
		fclose($log);
		
		sendToClient("cURL session to get repository data from GitHub failed.");
		exit();
	} else {
		fwrite($log, "cURL successful.\r\n\r\n" . $json_string);
	}
		
	// at this point, we should have serialized json (string) data in our $json_string variable
	// let's write a query to insert the relevant data to our database
	
	$insert_query = "INSERT INTO repositories VALUES";
	$repo_data = json_decode($json_string);
	$repo_count = $repo_data->total_count;
		
	function quote($arg) {
		return "'" . $arg . "'";
	}
	
	function format_date($str) {
		$str = str_replace("T", " ", $str);
		$str = str_replace("Z", "", $str);
		return quote($str);
	}
	
	if ($repo_count > 0) {
		// safe to truncate previous data
		mysqli_query($database, "TRUNCATE TABLE repositories;");			
		
		// concat query
		for ($i = 1; $i <= $repo_count; $i++) {
			$repo = $repo_data->items[$i - 1];
			$ordered_values = array(
				"NULL",		// reserved for primary ID
				quote($repo->id),
				quote($repo->name),
				quote($repo->url),
				format_date($repo->created_at),
				format_date($repo->pushed_at),
				quote($repo->description),
				$repo->stargazers_count
			);
			
			$suffix = ($i == $repo_count) ? ';' : ',';
			
			$insert_query = $insert_query . " (" . implode(',', $ordered_values) . ")" . $suffix;
		}
		
		fwrite($log, "Query created:\r\n\r\n" . $insert_query . "\r\n");
		
		// perform insert
		$success = mysqli_query($database, $insert_query);
		if (!$success) {
			fwrite($log, "INSERT query failed. See error:\r\n\r\n" . mysqli_error($database));
			fclose($log);
			sendToClient("Server was unable to insert GitHub API repository information to targeted MySQL database. ");
			exit();
		} else {
			fwrite($log, "INSERT query successful!");
			fclose($log);
			sendToClient("Database refreshed!");
			exit();
		}
		// otherwise we will send the default 200 (OK) HTTP code
	}
?>