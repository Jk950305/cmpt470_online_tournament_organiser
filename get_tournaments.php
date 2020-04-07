<?php
    //connect to database, provides connection as $conn
    include("processing/connection.php"); 

    try{
        $sql = "select
                    t.tournament_id as id
                    , t.event_id as event_id
                    , t.tournament_name as name
                    , t.elimination_count as elimination_count
                    , t.active_ind
                from 
                    tournament as t
                where t.event_id = {$event_id} and t.active_ind = 1";
                    
        $result = $conn->query($sql);
        
        while($row = $result->fetch()){
            //store sql results
            $ID = $row["id"];
            $name = $row["name"];
            $elimCount = $row["elimination_count"];
            $edit = "edit";
            $delete = "delete";
            $type = "tournament";

            //if it's active, otherwise don't show
            // if ($row["active_ind"] != 0) {
                //output to table
                echo "<tr>";          
                echo "<td>{$ID}</td>";
                echo "<td><a href='pools.php?tournament_id={$ID}&tournament_name={$name}'>".$name."</a></td>";
                echo "<td>{$elimCount}</td>";
                echo "<td><a href='edit_tournament.php?tournament_id={$ID}&event_id={$event_id}'>".$edit."</a></td>";
                //if has access
                echo "<td><a href='delete.php?tournament_id={$ID}&callerType={$type}'>".$delete."</a></td>";
                //
                echo "</tr>";
                
                
            // }
        }               
    }
    catch( \Exception $e){
        echo $e->getMessage();
    }   

    $conn = null; //disconnect
?>