<?php
    session_start();
    $event_id = $_GET["event_id"];
    $event_name = filter_var($_GET["event_name"], FILTER_SANITIZE_STRING);
    $_SESSION['event_id'] = $event_id;
    $_SESSION['event_name'] = $event_name;
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
            echo "<li class=\"active\">".htmlspecialchars($event_id)."</li>"; 
        ?>
    </ul>

    <!-- filter -->
    <h3>Create New Tournament</h3>
    <form id = "newTournament" action="processing/insertEvent.php" method="post">
        <table>
            <tr>
                <td>
                    <label for="eventName">Tournament Name&nbsp;</label>
                </td>
                <td>
                    <input type="text" id="eventName" name="tournamentName">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="elimCount">Elimination Count</label>
                </td>
                <td>
                    <input type="number" id="elimCount" name="elimCount">
                </td>
            </tr>
        </table>
                    <input type="hidden" name="callerType" value="tournament">
                    <input type="hidden" name="eventId" value=<?php echo $event_id; ?> >
                    <input type="submit">
    </form>
    <br>

    
    <!-- tournament listings -->
    <table class="col-9 table table-striped shadow bg-white rounded table-wrapper">
        <thead>
            <th>
                ID
            </th>
            <th>
                TOURNAMENT NAME
            </th>
            <th>
                ELIMINATION COUNT
            </th>
            <th colspan="2"></th>
        </thead>
        <tbody id="tournament-listing-body">
        <?php include("get_tournaments.php"); ?>
        </tbody>
    </table>

</html>
