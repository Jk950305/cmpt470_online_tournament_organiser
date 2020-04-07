<?php
	session_start();
	include("checklogin.php");
	include("connection.php"); 

	if( $_POST["callerType"] == 'tournament' ){
		$tournamentName = filter_var($_POST["tournamentName"], FILTER_SANITIZE_STRING);
		if (!filter_var($_POST["eventId"], FILTER_VALIDATE_INT) === false ||
			filter_var($_POST["eventId"] === 0) && 
			!filter_var($_POST["elimCount"], FILTER_VALIDATE_INT) === false ||
			filter_var($_POST["elimCount"]) === 0) {

			$eventId = $_POST["eventId"]; 
			$elimCount = $_POST["elimCount"];
			
		} else {
			throw new Exception("Error numbers cannot be less than zero", 1);
		}
		$user_id = $_SESSION["user"]["user_id"];
				
		//insert new tournament
		$sql = "insert into tournament(event_id, tournament_name, elimination_count, beg_effective_dt_tm, active_ind)
					values(?, ?, ?, now(), 1);";
		$conn->prepare($sql)->execute([$eventId, $tournamentName, $elimCount]);
				
	}elseif( $_POST["callerType"] == 'event' ){
		$eventName = filter_var($_POST["eventName"], FILTER_SANITIZE_STRING);
		$eventStart = $_POST["startDate"];
		$eventEnd = $_POST["endDate"];

		$sql = "insert into event(event_name, event_start_dt_tm, event_end_dt_tm, beg_effective_dt_tm, active_ind)
					values(?, ?, ?, now(), 1);";
		$conn->prepare($sql)->execute([$eventName, $eventStart, $eventEnd]);
				
	}else{
		echo "fail";
	}
	
	try{
		//connect to database, provides connection as $conn
		$conn->exec($sql);
		echo $sql;
		
		if( $_POST["callerType"] == 'tournament' ){
			$sql = "select last_insert_id() as tourn_id;";
			$result = $conn->query($sql);
			
			$row = $result->fetch();
			
			$tournament_id = $row["tourn_id"];
			$sql = "insert into tournament_user_reltn(user_id, tournament_id, score_keeper_ind, tournament_organiser_ind, beg_effective_dt_tm, active_ind)
					values('{$user_id}', '{$tournament_id}', 1, 1, now(), 1);";
			echo $sql;
			
			$conn->exec($sql);			
		}
		
	}
	catch( \Exception $e){
		echo $e->getMessage();
	}
	
	//redirect to previous page.
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit();


?>