<?php
//connect to database, provides connection as $conn
include("processing/connection.php"); 

try{
    $sql = "SELECT
                tp.tournament_pool_id as id
                , tp.pool_seq as pool_seq
            FROM
                tournament_pool as tp
            WHERE
                tp.tournament_id = {$tournament_id}";
                
    $result = $conn->query($sql);
    
    while($row = $result->fetch()){
        //store sql results
        $ID = $row["id"];
        $pool_seq = $row["pool_seq"];

        echo "<option value=\"{$ID}\">{$pool_seq}</option>";
            
            
        // }
    }               
}
catch( \Exception $e){
    echo $e->getMessage();
}   

$conn = null; //disconnect
?>