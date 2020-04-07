<?php
    session_start();
    if(isset($_SESSION['event_id']) && isset($_SESSION['event_name']) 
        && isset($_SESSION['tournament_id'])&& isset($_SESSION['tournament_name'])){
        $event_id = $_SESSION['event_id'];
        $event_name = $_SESSION['event_name'];
        $tournament_id = $_SESSION['tournament_id'];
        $tournament_name = $_SESSION['tournament_name'];
        $pool_id = $_GET['pool_id'];
        $pool_seq = $_GET['pool_seq'];
        $_SESSION['pool_id'] = $pool_id;
        $_SESSION['pool_seq'] = $pool_seq;
    }
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

    <!-- header -->
    <?php 
        include_once("header.php");
    ?>

    
    <!-- breadcrumb -->
    <ul class="breadcrumb">
        <li><a href="./index.php">Home</a> <span class="divider">/</span> </li>
        <li><a href="./events.php">Events</a> <span class="divider">/</span> </li>
        <?php
            echo "<li><a href=\"./tournaments.php?event_id={$event_id}&event_name={$event_name}\">".htmlspecialchars($event_name)."</a> <span class=\"divider\">/</span> </li>";
            echo "<li><a href=\"./pools.php?tournament_id={$tournament_id}&tournament_name={$tournament_name}\"> {$tournament_name} </a> <span class=\"divider\">/</span> </li>";
            echo "<li class=\"active\">".$_GET["pool_seq"]."</li>";
        ?>
    </ul>

    <h3>Matches</h3>
    <table class="col-9 table table-bordered shadow bg-white rounded table-wrapper match-tab table-hover">
        <?php 
            //grid style
            include("get_matches.php");

            //table style
            //include("get_temp.php"); 
        ?>
    </table>


</html>