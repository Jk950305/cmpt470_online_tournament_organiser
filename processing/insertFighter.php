<?php
	session_start();
	include("checklogin.php");
	include("connection.php"); 

	function getLargestPoolSeq($tournament_id){
		include("connection.php"); 
		$sql = "SELECT tournament_pool_id, pool_seq 
				FROM tournament_pool
				WHERE tournament_id = $tournament_id
				ORDER BY pool_seq 
				DESC LIMIT 1";
		try{
			$result = $conn->query($sql);
			$row = $result->fetch();
			if(empty($row)){
				$row = array();
				$row['pool_seq'] = 1;
				$row['id'] = -1;
			}
			return $row;
		}catch( \Exception $e){
			echo $e->getMessage();
		}
	}

	function createPool($tournament_id,$largest_seq){
		include("connection.php");
		$sql = "INSERT IGNORE INTO tournament_pool (
					tournament_id
					, pool_seq
					, beg_effective_dt_tm
					, end_effective_dt_tm
					, active_ind
				) VALUES (
					{$tournament_id}
					, {$largest_seq}
					, now()
					, null
					, 1
				);
			";
		try{
			$conn->exec($sql);
		}catch( \Exception $e){
			echo $e->getMessage();
		}
	}

	function unassignFighters($tournament_id){
		include("connection.php");
		$sql = "DELETE person_tournament_pool_reltn 
				FROM person_tournament_pool_reltn 
				INNER JOIN tournament_pool 
				ON person_tournament_pool_reltn.tournament_pool_id = tournament_pool.tournament_pool_id 
				WHERE tournament_id = {$tournament_id};
			";
		try{
			$conn->exec($sql);
		}catch( \Exception $e){
			echo $e->getMessage();
		}
	}

	function getAllFighters($tournament_id){
		include("connection.php");
		$sql = "SELECT
	                p.person_id as id
	                , p.name_first as name_first
	                , p.name_last as name_last
	            FROM 
	                person as p
	            INNER JOIN
	                person_tournament_reltn as pt
	            ON
	                pt.person_id = p.person_id
	            WHERE 
	                pt.tournament_id = {$tournament_id}
	            ";
	    $fighters = array();
	    try{
		    $result = $conn->query($sql);
            while($row = $result->fetch()){
		    	$fighters[] = $row;
		    }
		    return $fighters;
		}catch( \Exception $e){
			echo $e->getMessage();
		}
	}

	function getAllPools($tournament_id){
		include("connection.php");
		$sql = "SELECT tournament_pool_id as id, pool_seq
				FROM tournament_pool
				WHERE tournament_id = {$tournament_id}";
		$pools = array();
		try{
		    $result = $conn->query($sql);
            while($row = $result->fetch()){
		    	$pools[] = $row;
		    }
		    return $pools;
		}catch( \Exception $e){
			echo $e->getMessage();
		}
	}

	function insertMatchesAndRounds($pools){
		foreach($pools as $pool){
			$pool_id = $pool['id'];
			insertMatchesLeague($pool_id);
		}
	}

	function insertMatchesLeague($pool_id){
		$pool_fighters = getPoolFighters($pool_id);
		$count = ($pool_fighters!=null)?(count($pool_fighters)):0;
		for($i = 0; $i < $count; $i++){
			for($j = ($i+1); $j < $count; $j++){
				include("connection.php");
				$person_id = $pool_fighters[$i]['id'];
				$fighter_id = $pool_fighters[$j]['id'];

				$sql = "INSERT IGNORE INTO pool_match(
							tournament_pool_id, first_fighter_id, second_fighter_id, beg_effective_dt_tm, active_ind
						)VALUES(
							{$pool_id}, {$person_id}, {$fighter_id}, now(), 1
						);
					";
				$conn->exec($sql);
				$conn = null;
				$sql = "SELECT match_id as id 
						FROM pool_match
						WHERE 
							tournament_pool_id = {$pool_id} AND
							first_fighter_id = {$person_id} AND
							second_fighter_id = {$fighter_id};
						LIMIT 1;
					";
				try{
					include("connection.php");
				    $result = $conn->query($sql);
				    $row = $result->fetch();
				    $match_id = $row['id'];
				    insertRound($match_id,$person_id,$fighter_id,$count);
				}catch( \Exception $e){
					echo $e->getMessage();
				}
			}
		}

	}

	function insertFighters($pools,$fighters){
		include("connection.php");
		$pools_count = ($pools!=null)?(count($pools)):0;
		$fighters_count = ($fighters!=null)?(count($fighters)):0;
		$count = 0;
		$sql = "";
		shuffle($fighters);//shuffle fighters : random
		foreach($pools as $pool){
			$pool_id = $pool['id'];
			$sql .= "DELETE FROM pool_match WHERE tournament_pool_id = {$pool_id};";
		}

		try{
			$conn->exec($sql);
		}
		catch( \Exception $e){
			echo $e->getMessage();
		}

		$sql = "";
		foreach($fighters as $fighter){
			$person_id = $fighter['id'];
			$index = ($count%$pools_count);
			$pool_id = $pools[$index]['id'];

			$sql .= "INSERT IGNORE INTO person_tournament_pool_reltn (
						tournament_pool_id
						, person_id
						, beg_effective_dt_tm
						, end_effective_dt_tm
						, active_ind
					) VALUES (
						{$pool_id}
						, {$person_id}
						, now()
						, null
						, 1);
					";
			$count++;
		}
		try{
			$conn->exec($sql);
		}
		catch( \Exception $e){
			echo $e->getMessage();
		}
	}



	function getLeastFightersPool($tournament_id){
		include("connection.php");
		$sql = "SELECT
	                tp.tournament_pool_id as id
	                , tp.tournament_id as tournament_id
	                , tp.pool_seq as pool_seq
	                , count(*) as num
	            FROM 
	                tournament_pool as tp
	            INNER JOIN
	                person_tournament_pool_reltn as ptp
	            ON
	                tp.tournament_pool_id = ptp.tournament_pool_id
	            INNER JOIN
	                person as p
	            ON
	                ptp.person_id = p.person_id
	            WHERE 
	                tp.tournament_id = {$tournament_id} and tp.active_ind = 1
	            GROUP BY tp.tournament_pool_id
	            ORDER BY num ASC
	            LIMIT 1";
	    
	    try{
	    	$result = $conn->query($sql);
	    	$row = $result->fetch();
		    return $row;
	    }catch( \Exception $e){
			echo $e->getMessage();
		}
	    
	}

	function getPoolFighters($pool_id){
		include("connection.php");
		$sql = "SELECT
	                p.person_id as id
	                , p.name_first as name_first
	                , p.name_last as name_last
	            FROM 
	                person as p
	            INNER JOIN
	                person_tournament_pool_reltn as ptp
	            ON
	                ptp.person_id = p.person_id
	            WHERE 
	                ptp.tournament_pool_id = {$pool_id};
	            ";
	    $fighters = array();
	    try{
		    $result = $conn->query($sql);
            while($row = $result->fetch()){
		    	$fighters[] = $row;
		    }
		    return $fighters;
		}catch( \Exception $e){
			echo $e->getMessage();
		}
	}

	function insertRound($match_id,$person_id,$fighter_id,$count){
		include("connection.php");
		$sql = "INSERT IGNORE INTO match_round(
					match_id, match_seq, first_fighter_id, second_fighter_id,beg_effective_dt_tm, active_ind)
				values(
					{$match_id}, {$count}, {$person_id}, {$fighter_id}, now(), 1
				);
			";
		try{
			$conn->exec($sql);
		}catch( \Exception $e){
			echo $e->getMessage();
		}
	}

	function insertMatches($person_id,$fighters,$pool_id){
		$count = 0;
		foreach($fighters as $fighter){
			include("connection.php");
			$count++;
			$fighter_id = $fighter['id'];
			$sql = "INSERT IGNORE INTO pool_match(
						tournament_pool_id, first_fighter_id, second_fighter_id,beg_effective_dt_tm, active_ind
					)VALUES(
						{$pool_id}, {$person_id}, {$fighter_id}, now(), 1
					);
				";
			$conn->exec($sql);
			$conn = null;
			$sql = "SELECT match_id as id 
					FROM pool_match
					WHERE 
						tournament_pool_id = {$pool_id} AND
						first_fighter_id = {$person_id} AND
						second_fighter_id = {$fighter_id};
					LIMIT 1;
				";
			try{
				include("connection.php");
			    $result = $conn->query($sql);
			    $row = $result->fetch();
			    $match_id = $row['id'];
			    $conn = null;
			    insertRound($match_id,$person_id,$fighter_id,$count);
			}catch( \Exception $e){
				echo $e->getMessage();
			}
		}

	}


	function insertFighter($pool_id,$person_id,$tournament_id,$event_id){
		include("connection.php");
		$fighters = getPoolFighters($pool_id);
		$sql = "INSERT IGNORE INTO person_tournament_reltn (
					tournament_id
					, person_id
					, beg_effective_dt_tm
					, end_effective_dt_tm
					, active_ind
				) VALUES (
					{$tournament_id}
					, {$person_id}
					, now()
					, null
					, 1);
				";

		$sql .= "INSERT IGNORE INTO person_event_reltn (
					event_id
					, person_id
					, beg_effective_dt_tm
					, end_effective_dt_tm
					, active_ind
				) VALUES (
					{$event_id}
					, {$person_id}
					, now()
					, null
					, 1);
				";

		$sql .= "INSERT IGNORE INTO person_tournament_pool_reltn (
					tournament_pool_id
					, person_id
					, beg_effective_dt_tm
					, end_effective_dt_tm
					, active_ind
				) VALUES (
					{$pool_id}
					, {$person_id}
					, now()
					, null
					, 1);
				";
		try{
			$conn->exec($sql);
		}
		catch( \Exception $e){
			echo $e->getMessage();
		}
		insertMatches($person_id,$fighters,$pool_id);
	}

	function getPoolID($pool_seq,$tournament_id){
		include("connection.php");
		$sql = "SELECT tournament_pool_id as id 
				FROM tournament_pool
				WHERE 
					pool_seq = $pool_seq AND
					tournament_id = $tournament_id
				LIMIT 1;
			";
		try{
	    	$result = $conn->query($sql);
	    	$row = $result->fetch();
	    	return $row['id'];
	    }catch( \Exception $e){
	    	echo $e->getMessage();
	    }
	}
	
	function insertPersonID($fname,$lname,$bdate){
		include("connection.php"); 
		$fname_key = strtoupper(preg_replace('/\s+/', '', $fname));  
		$lname_key = strtoupper(preg_replace('/\s+/', '', $lname)); 
		$sql = "INSERT IGNORE INTO person (
				name_first
				, name_last
				, name_first_key
				, name_last_key
				, birth_dt_tm
				, beg_effective_dt_tm
				, end_effective_dt_tm
				, active_ind
			) VALUES(
				'{$fname}'
				, '{$lname}'
				, '{$fname_key}'
				, '{$lname_key}'
				, '{$bdate}'
				, now()
				, null
				, 1);
			";
		try{
	    	$conn->exec($sql);
	    }catch( \Exception $e){
	    	echo $e->getMessage();
	    }
	}

	function getPersonID($fname,$lname,$bdate){
		include("connection.php");
		$sql = "SELECT person_id as id 
				FROM person
				WHERE 
					name_first = '{$fname}' AND
					name_last = '{$lname}' AND
					birth_dt_tm = '{$bdate}';
				LIMIT 1;
			";
		try{
	    	$result = $conn->query($sql);
	    	$row = $result->fetch();
	    	return $row['id'];
	    }catch( \Exception $e){
	    	echo $e->getMessage();
	    }
	}

	function inserInto($person_id,$tournament_id,$event_id){
		include("connection.php");
		$sql = "INSERT IGNORE INTO person_tournament_reltn (
					tournament_id
					, person_id
					, beg_effective_dt_tm
					, end_effective_dt_tm
					, active_ind
				) VALUES (
					{$tournament_id}
					, {$person_id}
					, now()
					, null
					, 1);
				";

		$sql .= "INSERT IGNORE INTO person_event_reltn (
					event_id
					, person_id
					, beg_effective_dt_tm
					, end_effective_dt_tm
					, active_ind
				) VALUES (
					{$event_id}
					, {$person_id}
					, now()
					, null
					, 1);
				";
		try{
			$conn->exec($sql);
		}catch( \Exception $e){
	    	echo $e->getMessage();
	    }
	}

	//main
	$fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING);
	$lname = filter_var($_POST['lname'], FILTER_SANITIZE_STRING);
	$bdate = filter_var($_POST['bdate'], FILTER_SANITIZE_STRING);

	//create new person
	insertPersonID($fname,$lname,$bdate);

	//get new person_id
	$person_id = getPersonID($fname,$lname,$bdate);

	$tournament_id =  $_POST['tournament_id'];
	$event_id =  $_POST['event_id'];

	//get all fighters in tournament
	$fighters = getAllFighters($tournament_id);

	//base case when # of fighters ==0
	if(count($fighters) == 0){
		//create new pool with seq 1
		$pool = getLargestPoolSeq($tournament_id);
		$pool_seq = $pool['pool_seq'];
		createPool($tournament_id,$pool_seq);
		$pool_id = getPoolID($pool_seq,$tournament_id);

		//add new fighter to that pool
		insertFighter($pool_id,$person_id,$tournament_id,$event_id);

	}elseif(count($fighters)%5==0){
		//get larget pool_seq number from the tournament
		$largest_pool = getLargestPoolSeq($tournament_id);
		$largest_seq = $largest_pool['pool_seq'];

		//create an empty pool with pool_seq = larget_pool_seq + 1
		createPool($tournament_id,$largest_seq+1);

		//insert a new fighter into person_event and person_tournament table
		inserInto($person_id,$tournament_id,$event_id);

		//unassign all fighters from pools
		unassignFighters($tournament_id);

		//get all fighters of the tournament 
		$fighters = getAllFighters($tournament_id);

		//get all pools of the tournament
		$pools = getAllPools($tournament_id);

		//insert all fighters into existing pools in order
		insertFighters($pools,$fighters);

		//insert all the matches with newly created pool info
		insertMatchesAndRounds($pools);
	
	}else{
		//get a pool which has the least number of fighters in order
		$pool = getLeastFightersPool($tournament_id);
		$pool_id = $pool['id'];

		//insert the fighter into that pool
		insertFighter($pool_id,$person_id,$tournament_id,$event_id);
	}

	//redirect to previous page.
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit();



?>