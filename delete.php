<?php
	session_start();
	include("processing/checklogin.php");
	include("processing/connection.php"); 

	$type = $_GET["callerType"];

	if ( $type == 'tournament' ) {
		$deleteID = $_GET["tournament_id"];

        $sql = "UPDATE tournament SET active_ind=0, end_effective_dt_tm=now() WHERE tournament_id='{$deleteID}'";
		echo $sql;
		
	} elseif ( $type == 'event' ) {
        $deleteID = $_GET["event_id"];
		
		$sql = "UPDATE event SET active_ind=0, end_effective_dt_tm=now() WHERE event_id='{$deleteID}'";
		
	} else {
		echo "fail";
	}

	try {
		$conn->exec($sql);
		
	} catch ( \Exception $e){
		echo $e->getMessage();
	}
	
	//redirect to previous page.
	header('Location: events.php');
	exit();
?>