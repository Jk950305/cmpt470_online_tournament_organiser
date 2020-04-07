<?php 

session_start(); 
include("processing/checklogin.php");

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Martial Arts Tournaments</title>

    <!-- jquery and bootstrap CDN
    TODO: change to min files after -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:600|Nanum+Gothic&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>

    <!-- js and css -->
    <link rel="stylesheet" type="text/css" href="layout.css">
    <script src="script.js"></script>
</head>

<body class="container">

    <?php 
        include_once("header.php"); 
        include("processing/connection.php"); 
        $id = $_GET["tournament_id"];
        $event_id = $_GET["event_id"];
    ?>

    <!-- breadcrumb -->
    <ul class="breadcrumb">
        <li><a href="./index.php">Home</a> <span class="divider">/</span> </li>
        <li><a href="./events.php">Events</a> <span class="divider">/</span> </li>
        <li><a href="./tournaments.php?event_id=<?php echo $event_id; ?>">Tournaments</a> <span class="divider">/</span> </li>
        <li class="active">Edit</li>
    </ul>	
	
	<!-- Edit event -->
	<h3>Edit</h3>
	<form id = "newTournament" action="processing/updateEvent.php" method="post">
        <label for="tournamentName">Tournament Name:</label>
		<input type="text" id="tournamentName" name="tournamentName">
        <label for="elimCount">Elim Count:</label>
		<input type="number" id="elimCount" name="elimCount">
        <?php 
            echo "<input type='hidden' name='ID' value='{$id}'>"; 
            echo "<input type='hidden' name='eventID' value='{$event_id}'>"; 
        ?>
        <input type="hidden" name="callerType" value="tournament">
		<input type="submit">
	</form>



    <?php
			
			$conn = null; //disconnect
		?>

</html>