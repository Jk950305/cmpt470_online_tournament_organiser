<?php
    session_start();
    if(isset($_SESSION['event_id']) && isset($_SESSION['event_name']) 
        && isset($_SESSION['tournament_id'])&& isset($_SESSION['tournament_name'])
        && isset($_SESSION['pool_id'])&& isset($_SESSION['pool_seq']) ){
        $event_id = $_SESSION['event_id'];
        $event_name = $_SESSION['event_name'];
        $tournament_id = $_SESSION['tournament_id'];
        $tournament_name = $_SESSION['tournament_name'];
        $pool_id = $_SESSION['pool_id'];
        $pool_seq = $_SESSION['pool_seq'];
        $match_id = $_GET['match_id'];
    }

    function getInfo($match_id){
        include("processing/connection.php");
        $sql = "select
                    mr.match_id as id
                    , mr.first_fighter_id as first_id
                    , mr.second_fighter_id as second_id
                    , mr.first_fighter_score as first_score
                    , mr.second_fighter_score as second_score
                    , p1.name_first as first_name_first
                    , p1.name_last as first_name_last
                    , p2.name_first as second_name_first
                    , p2.name_last as second_name_last
                from 
                    match_round as mr
                inner join
                    person as p1
                on
                    mr.first_fighter_id = p1.person_id
                inner join
                    person as p2
                on
                    mr.second_fighter_id = p2.person_id
                where
                    mr.match_id = {$match_id}
                limit 1;
                ";
        try{
            $result = $conn->query($sql);
            $row = $result->fetch();
            $row['first_name'] = $row['first_name_first']." ".$row['first_name_last'];
            $row['second_name'] = $row['second_name_first']." ".$row['second_name_last'];
            return $row;

        }catch( \Exception $e){
            echo $e->getMessage();
        }   
        $conn = null; //disconnect
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
    <link href="https://fonts.googleapis.com/css?family=Nanum+Gothic&display=swap" rel="stylesheet">
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
        $info = getInfo($match_id);
    ?>
    
    <!-- breadcrumb -->
    <ul class="breadcrumb">
        <li><a href="./index.php">Home</a> <span class="divider">/</span> </li>
        <li><a href="./events.php">Events</a> <span class="divider">/</span> </li>
        <?php
            echo "<li><a href=\"./tournaments.php?event_id={$event_id}&event_name={$event_name}\"> {$event_name} </a> <span class=\"divider\">/</span> </li>";
            echo "<li><a href=\"./pools.php?tournament_id={$tournament_id}&tournament_name={$tournament_name}\"> {$tournament_name} </a> <span class=\"divider\">/</span> </li>";
            echo "<li><a href=\"./matches.php?pool_id={$pool_id}&pool_seq={$pool_seq}\">{$pool_seq}</a> <span class=\"divider\">/</span></li>";
            echo "<li class=\"active\">{$info['first_name']} vs {$info['second_name']}</li>";
        ?>
    </ul>

    <h3>Enter Score</h3>
    <form id = "newTournament" action="processing/enterScore.php" method="post">
        <label for="first_score"><?php echo $info['first_name']?></label>
        <input type="number" id="first_score" name="first_score" value=<?php echo $info['first_score']?>>
        :
        <input type="number" id="second_score" name="second_score" value=<?php echo $info['second_score']?>>
        <label for="second_score"><?php echo $info['second_name'] ?></label>
        <input type="hidden" name="callerType" value="score">
        <input type="hidden" name="pool_id" value=<?php echo $pool_id; ?>>
        <input type="hidden" name="pool_seq" value=<?php echo $pool_seq; ?>>
        <input type="hidden" name="first_id" value=<?php echo $info['first_id']; ?>>
        <input type="hidden" name="second_id" value=<?php echo $info['second_id']; ?>>
        <input type="hidden" name="match_id" value=<?php echo $match_id; ?>>
        <input type="submit">
    </form>


</html>