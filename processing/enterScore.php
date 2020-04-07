<?php
	//currently it does nothing yet.
    session_start();

	include("connection.php"); 
	$url = "";

	if( $_POST["callerType"] == 'score' ){
		$pool_id = $_POST['pool_id'];
        $pool_seq = $_POST['pool_seq'];

        $first_id = $_POST['first_id'];
        $second_id = $_POST['second_id'];

        $first_score = $_POST['first_score'];
        $second_score = $_POST['second_score'];

        $match_id = $_POST['match_id'];

        $url .= "/matches.php?pool_id={$pool_id}&pool_seq={$pool_seq}";

		$sql = "UPDATE match_round
				SET first_fighter_score=?, second_fighter_score=?
				WHERE match_id=?; 
			";

			// [$first_score, $second_score, $match_id]

		try{
			$conn->prepare($sql)->execute([$first_score, $second_score, $match_id]);
			//echo $sql;
	
			header('Location: '.$url);
			exit();
		}
		catch( \Exception $e){
			echo $e->getMessage();
		}
	}else{
		echo "fail";
	}

	//redirect to previous page.
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit();


?>