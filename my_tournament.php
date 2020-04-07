<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>My Tournaments</title>

	<link href="https://fonts.googleapis.com/css?family=Montserrat:600|Nanum+Gothic&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css" href="layout.css">
    <script src="script.js"></script>
</head>
<body class="container">
    <?php include_once("processing/checklogin.php"); ?>
    <?php include_once("header.php"); ?>

    <!-- breadcrumb -->
    <ul class="breadcrumb">
        <li><a href="index.php">Home</a> <span class="divider">/</span> </li>
        <li class="active">My Tournaments</li>
    </ul>

	<br>
	<?php
		echo "Hello, " . $_SESSION["user"]["name_first"] . " " .$_SESSION["user"]["name_last"] . " (" . $_SESSION["user"]["email"] . ")";
		echo "<br><br><br>";
	?>
	<h2> Your Tournaments</h2>
	
	<table class="col-9 table table-striped shadow bg-white rounded table-wrapper">
		<thead>
			<th>
				ID
			</th>
			<th>
				NAME
			</th>
		</thead>
		<tbody id="tournament-listing-body">
		<?php
			//connect to database, provides connection as $conn
			include("processing/connection.php"); 
			$user_id = $_SESSION["user"]["user_id"];
			
			try{
				$sql = "select
							tur.tournament_id as t_id						
							, t.tournament_name as name
						from 
							tournament_user_reltn tur
							
						join (tournament t)
												
						on ( 
							tur.user_id = {$user_id}
							and tur.active_ind = 1
							
							and t.tournament_id = tur.tournament_id
							and t.active_ind = 1
							);";
						
				$result = $conn->query($sql);
			
				while($row = $result->fetch()){
					//store sql results
					$ID = $row["t_id"];
					$name = $row["name"];
					
					//output to table
					echo "<tr>";
					
					echo "<td>".$ID."</td>";
					echo "<td><a href='pools.php?tournament_id={$ID}&tournament_name={$name}'>".$name."</a></td>";
					
					echo "</tr>";
				}
			}
			catch( \Exception $e){
				echo $e->getMessage();
			}		
			
			$conn = null; //disconnect			
		?>
		</table>
</body>
</html>