<?php
    session_start();
    if(isset($_SESSION['event_id']) && isset($_SESSION['event_name']) ){
        $event_id = $_SESSION['event_id'];
        $event_name = filter_var($_SESSION['event_name'], FILTER_SANITIZE_STRING);
        $tournament_id = $_GET['tournament_id'];
        $tournament_name = filter_var($_GET['tournament_name'], FILTER_SANITIZE_STRING);
        $_SESSION['tournament_id'] = $tournament_id;
        $_SESSION['tournament_name'] = $tournament_name;
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
            echo "<li class=\"active\">".htmlspecialchars($tournament_name)."</li>";
        ?>
    </ul>

    <?php 
        $tournament_id = $_GET['tournament_id'];
    ?>

    <h3>Add New Fighter</h3>
    <form id = "newFighter" action="processing/insertFighter.php" method="post">
        <table>
            <tr>
                <td>
                    <label for="fname">First Name&nbsp;</label>
                </td>
                <td>
                    <input type="text" id="fname" name="fname">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="lname">Last Name</label>
                </td>
                <td>
                    <input type="text" id="lname" name="lname">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="bdate">Birth Date</label>
                </td>
                <td>
                    <input type="date" id="bdate" name="bdate">
                </td>
            </tr>
        </table>
        <input type="hidden" name="tournament_id" value=<?php echo $tournament_id; ?> >
        <input type="hidden" name="event_id" value=<?php echo $event_id; ?> >
        <input type="submit" value="Add">
    </form>
    <br>



    <h3>List of Pools</h3>
    <table class="col-9 table table-striped shadow bg-white rounded table-wrapper">
        <thead>
            <th>
                ID
            </th>
            <th>
                POOL SEQ
            </th>
            <th>
                NUMBER OF PARTICIPANTS
            </th>
        </thead>
        <tbody id="tournament-listing-body">
        <?php include("get_pools.php"); ?>
        </tbody>
    </table>

</html>
