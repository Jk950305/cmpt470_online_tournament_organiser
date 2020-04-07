<?php
//connect to database, provides connection as $conn
include("processing/connection.php"); 

try{
    $sql = "select
                pm.match_id as id
                , p1.name_first as first_name_first
                , p1.name_last as first_name_last
                , p2.name_first as second_name_first
                , p2.name_last as second_name_last
                , mr.first_fighter_score as first_score
                , mr.second_fighter_score as second_score
            from 
                pool_match as pm
            inner join
                match_round as mr
            on
                pm.match_id = mr.match_id
            inner join
                person as p1
            on
                p1.person_id = pm.first_fighter_id
            inner join
                person as p2
            on
                p2.person_id = pm.second_fighter_id
            where pm.tournament_pool_id = {$pool_id}";
                
    $result = $conn->query($sql);
    echo "<table class=\"col-9 table table-striped shadow bg-white rounded table-wrapper\">
        <thead>
            <th>
                ID
            </th>
            <th>
                First Fighter
            </th>
            <th>
                first_score
            </th>
            <th>
                
            </th>
            <th>
                second_score
            </th>
            <th>
                Second Fighter
            </th>
            <th colspan=\"2\"></th>
        </thead>
        <tbody id=\"tournament-listing-body\">";
    
    while($row = $result->fetch()){
        //store sql results
        $ID = $row["id"];
        $first_name = $row["first_name_first"]." ".$row["first_name_last"];
        $second_name = $row["second_name_first"]." ".$row["second_name_last"];
        $first_score = ($row["first_score"]!=null)?($row["first_score"]):0;
        $second_score = ($row["second_score"]!=null)?($row["second_score"]):0;



        //if it's active, otherwise don't show
        // if ($row["active_ind"] != 0) {
            //output to table
            echo "<tr>";          
            echo "<td>{$ID}</td>";
            echo "<td>{$first_name}</td>";
            echo "<td>{$first_score}</td>";
            echo "<td>vs</td>";
            echo "<td>{$second_score}</td>";
            echo "<td>{$second_name}</td>";
            echo "<td>".$edit."</a></td>";
            //if has access
            echo "<td>".$delete."</a></td>";
            //
            echo "</tr>";
            
            
        // }
    }
    echo "</tbody>
            </table>";               
}
catch( \Exception $e){
    echo $e->getMessage();
}   

$conn = null; //disconnect
?>