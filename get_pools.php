<?php
//connect to database, provides connection as $conn
include("processing/connection.php"); 

try{
    if (!filter_var($tournament_id, FILTER_VALIDATE_INT) === false) {
        //int is valid
        $sql = "SELECT
                    tp.tournament_pool_id as id
                    , tp.tournament_id as tournament_id
                    , tp.pool_seq as pool_seq
                    , count( DISTINCT ptp.person_id) as num
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
                GROUP BY tp.tournament_pool_id";
                    
        $result = $conn->query($sql);
        
        while($row = $result->fetch()){
            //store sql results
            $ID = $row["id"];
            $pool_seq = $row["pool_seq"];
            $num_participants = $row["num"];
    
            //output to table
            echo "<tr>";          
            echo "<td>{$ID}</td>";
            echo "<td><a href='matches.php?pool_id={$ID}&pool_seq={$pool_seq}'>".$pool_seq."</a></td>";
            echo "<td>".$num_participants."</td>";
            echo "</tr>";
        }               
    } else {
        echo "There was an error with your query";
    }
}
catch( \Exception $e){
    echo $e->getMessage();
}   

$conn = null; //disconnect
?>
