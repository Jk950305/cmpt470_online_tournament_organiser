<?php
	include("connection.php"); 

	if( $_POST["callerType"] == 'tournament' ){
        $ID = $_POST["ID"];
		$tournamentName = filter_var($_POST["tournamentName"], FILTER_SANITIZE_STRING);
		$eventId = $_POST["eventId"];
		$elimCount = $_POST["elimCount"];

		$sql = "UPDATE tournament SET tournament_name=?, elimination_count=? WHERE tournament_id=?";
		try{
			//connect to database, provides connection as $conn
			$conn->prepare($sql)->execute([$tournamentName, $elimCount, $ID]);
		}
		catch( \Exception $e){
			echo $e->getMessage();
		}
		
	}elseif( $_POST["callerType"] == 'event' ){
		$eventId = $_POST["eventId"];
		$eventName =filter_var($_POST["eventName"], FILTER_SANITIZE_STRING);
		$sql = 'UPDATE event SET event_name= ? WHERE event_id= ?';
		try{
			//connect to database, provides connection as $conn
			$conn->prepare($sql)->execute([$eventName, $eventId]);
		}
		catch( \Exception $e){
			echo $e->getMessage();
		}
		
	}else{
		echo "fail";
	}

	// try{
		
	// 	//connect to database, provides connection as $conn
    //     $conn->exec($sql);
	// }
	// catch( \Exception $e){
	// 	echo $e->getMessage();
    // }
    //redirect to previous page.
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit();


?>