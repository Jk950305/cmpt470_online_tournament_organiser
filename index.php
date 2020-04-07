<?php session_start(); ?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Online Tournament Organiser</title>

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

    <?php include_once("header.php"); ?>

    <!-- landing cards -->
    <div class="landing-continer d-flex flex-row justify-content-center">
        <a href="events.php" class="landing-item rounded">
            <h3 class="overlay-title centjustify-content-center" style="text-align:center;">
                EVENTS
            </h3>
            <img src="img/tournaments.jpg" class="rounded" alt="image of olympic taekwondo match">
        </a>
        <a href="my_tournament.php" class="landing-item">
            <h3 class="overlay-title justify-content-center" style="text-align:center;">
                MY TOURNAMENTS
            </h3>
            <img src="img/mytourney.jpg" class="rounded" alt="image of european martial arts match">
        </a>
    </div>

</html>
