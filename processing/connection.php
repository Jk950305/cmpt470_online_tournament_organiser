<?php
	//connect to database
	$servername = "34.68.5.23";
	$dbname = "tournament";
	$username = "root";
	$password = "sqlserver";
	
	//create connection	
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		//set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//echo "Connected successfully";
	} 
	catch(PDPException $e) {
		echo "Connection failed: ".$e->getMessage();
	}
	catch( \Exception $e){
		echo "Unknown exception".$e->getMessage();
	}	
?>