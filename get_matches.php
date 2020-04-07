<?php
    function getFightersList($pool_id){
        include("processing/connection.php"); 
        $fightersList = array();
        try{
            $sql = "SELECT
                        p.person_id as id
                        , p.name_first as name_first
                        , p.name_last as name_last
                        , tp.tournament_pool_id as tournament_pool_id
                        , tp.pool_seq as pool_seq
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
                        tp.tournament_pool_id = {$pool_id}
                    ";
                        
            $result = $conn->query($sql);
            
            while($row = $result->fetch()){
                //store sql results
                $row['name'] = $row["name_first"]." ".$row["name_last"];
                $fightersList[] = $row;
            }               
        }
        catch( \Exception $e){
            echo $e->getMessage();
        }   
        $conn = null; //disconnect
        return $fightersList;
    }

    function getMatchesList($pool_id){
        include("processing/connection.php");
        $matchesList = array();
        try{
            $sql = "select
                        pm.match_id as id
                        , pm.first_fighter_id as first_id
                        , pm.second_fighter_id as second_id
                        , mr.first_fighter_score as first_score
                        , mr.second_fighter_score as second_score
                        , pm.first_fighter_total as first_total
                        , pm.second_fighter_total as second_total
                    from 
                        pool_match as pm
                    inner join
                        match_round as mr
                    on
                        pm.match_id = mr.match_id
                    where pm.tournament_pool_id = {$pool_id}";
            $result = $conn->query($sql);
            
            while($row = $result->fetch()){
                //store sql results
                $match_id = $row["id"];

                $first_id = $row["first_id"];
                $second_id = $row["second_id"];

                $first_score = $row["first_score"];
                $second_score = $row["second_score"];

                $first_total = $row["first_total"];
                $second_total = $row["second_total"];

                $matchesList[] = $row;
            }  
        }catch( \Exception $e){
            echo $e->getMessage();
        }   
        $conn = null; //disconnect
        return $matchesList;
    }

    function getMatchID($first_id,$second_id,$matchesList){
        $match_id = null;
        foreach($matchesList as $match){
            if(($match['first_id']==$first_id && $match['second_id']==$second_id)
                ||
                ($match['second_id']==$first_id && $match['first_id']==$second_id)){
                $match_id = $match['id'];
            }
        }
        return $match_id;

    }

    function getScore($match_id,$first_id,$second_id,$matchesList){
        $scores = array();
        foreach($matchesList as $match){
            if($match['id']==$match_id && $match_id!= null){
                if($match['first_id']==$first_id && $match['second_id']==$second_id){
                    $scores['first_score'] = $match['first_score'];
                    $scores['second_score'] = $match['second_score'];
                }elseif($match['second_id']==$first_id && $match['first_id']==$second_id){
                    $scores['first_score'] = $match['second_score'];
                    $scores['second_score'] = $match['first_score'];

                }
            }
        }
        return $scores;
    }

    function getWld($first_score,$second_score){
        $str = "asdasda";
        if($first_score==$second_score){
            $str = "Draw";
        }elseif($first_score>$second_score){
            $str = "Win";
        }elseif($first_score<$second_score){
            $str = "Lose";
        }else{
            $str = "WAIT WHAT?";
        }
        return $str;
    }

    function getTotalScores($fighter,$matchesList){
        $score = [];
        $score['win'] = 0;
        $score['lose'] = 0;
        $score['draw'] = 0;
        $score['total'] = 0;
        foreach($matchesList as $match){
            if($match['first_id'] == $fighter['id'] && $match['first_score']!=null && $match['second_score']!=null){
                $score['total']++;
                if($match['first_score']==$match['second_score']){
                    $score['draw']++;
                }elseif($match['first_score']>$match['second_score']){
                    $score['win']++;
                }elseif($match['first_score']<$match['second_score']){
                    $score['lose']++;
                }else{
                }
            }elseif($match['second_id'] == $fighter['id'] && $match['first_score']!=null && $match['second_score']!=null){
                $score['total']++;
                if($match['second_score']==$match['first_score']){
                    $score['draw']++;
                }elseif($match['second_score']>$match['first_score']){
                    $score['win']++;
                }elseif($match['second_score']<$match['first_score']){
                    $score['lose']++;
                }else{
                }
            }
        }
        return $score;
    }

    function displayScore($i,$j,$matchesList,$fightersList,$flag){
        $first_id = $fightersList[$i]['id'];  
        $second_id = $fightersList[$j]['id'];
        $first_name = $fightersList[$i]['name'];  
        $second_name = $fightersList[$j]['name'];
        $match_id = getMatchID($first_id,$second_id,$matchesList);
        $scores = getScore($match_id,$first_id,$second_id,$matchesList);
        $first_score = $flag?($scores['first_score']):($scores['second_score']);
        $second_score = $flag?($scores['second_score']):($scores['first_score']);
        $wld = getWld($first_score,$second_score);
        $str1 = "<td 
                    class=\"matched\" 
                    onClick=\"document.location.href='scores.php?match_id={$match_id}';\">
                        {$first_score}:{$second_score} {$wld}
                </td>";
        $str2 = "<td 
                    class=\"matched\" 
                    onClick=\"document.location.href='scores.php?match_id={$match_id}';\">
                        enter scores
                </td>";

        return ($scores['first_score']==null||$scores['second_score']==null )?$str2:$str1;
    }

    $fightersList = getFightersList($pool_id);
    $matchesList = getMatchesList($pool_id);
    $count = count($fightersList);

    $output = "<colgroup>
                    <col span=\"".(count($fightersList)+1)."\"></col>
                    <col span=\"1\" class=\"result\"></col>
                </colgroup>";
    $header = "<thead><th></th>";
    $body = "<tbody id=\"tournament-listing-body\">";
    for($i = 0; $i < $count; $i++) {
        $header .= "<th><div>vs</div> ".$fightersList[$i]['name']."</th>";
        $body .= "<tr><td><strong>".$fightersList[$i]['name']."</strong></td>";
        for($j = 0; $j < $count; $j++){
            if($i == $j){
                $body .= "<td class=\"cross_out1\"></td>";
            }elseif($i<$j){
                $body .= displayScore($i,$j,$matchesList,$fightersList,True);
            }elseif($i>$j){
                $body .= displayScore($j,$i,$matchesList,$fightersList,False);
            }
        }
        $scores = getTotalScores($fightersList[$i],$matchesList);
        $tmp = $scores['win']."W-".$scores['lose']."L-".$scores['draw']."D";
        $body .= "<td>{$tmp}</td></tr>";
    }
    $header .= "<th>results</th></thead>";
    $body .= "</tbody>";
    $output .= $header;
    $output .= $body;
    echo $output;

?>