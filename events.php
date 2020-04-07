<?php session_start(); ?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Martial Arts Tournaments</title>

    <!-- jquery and bootstrap CDN
    TODO: change to min files after -->
	<!-- for the typefaces -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:600|Nanum+Gothic&display=swap" rel="stylesheet">
	<!-- for jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<!-- google CDN for bootstrap css and js-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>

    <!-- js and css -->
    <link rel="stylesheet" type="text/css" href="layout.css">
    <script src="script.js"></script>
</head>

<body class="container">

    <?php include_once("header.php"); ?>

    <!-- breadcrumb -->
    <ul class="breadcrumb">
        <li><a href="index.php">Home</a> <span class="divider">/</span> </li>
        <li class="active">Events</li>
    </ul>

    <!-- filter -->

	<!-- Add new event -->
	<h3>Create New Event</h3>
	<form id = "newEvent" action="processing/insertEvent.php" method="post">
		<table>
			<tr>
				<td>
					<label for="eventName">Event Name&nbsp;</label>
				</td>
				<td>
					<input type="text" id="eventName" name="eventName">
				</td>
			</tr>
			<tr>
				<td>
					<label for="startDate">Start Date</label>
				</td>
				<td>
					<input type="date" id="startDate" name="startDate" value=<?php echo date("Y-m-d") ?> >
				</td>
			</tr>
			<tr>
				<td>
					<label for="endDate">End Date</label>
				</td>
				<td>
					<input type="date" id="endDate" name="endDate" value=<?php echo date("Y-m-d") ?> >
				</td>
			</tr>
		</table>
			        <input type="hidden" name="callerType" value="event">
					<input type="submit">
	</form>

    
    <!-- event listings -->
	<table class="col-9 table table-striped shadow bg-white rounded table-wrapper">
		<thead>
			<th>
				ID
			</th>
			<th>
				NAME
			</th>
			<th>
				ELIMS TO DATE
			</th>
			<th>
				START
			</th>
			<th>
				END
			</th>
			<th>
				ONGOING
			</th>
			<th colspan="2"></th>
		</thead>
		<tbody id="tournament-listing-body">
		<?php
			//connect to database, provides connection as $conn
			include("processing/connection.php"); 
			try{
				$sql = "select
							e.event_id as id
							, e.event_name as name
							, 0
							, EVENT_START_DT_TM
							, EVENT_END_DT_TM 
						from 
							event as e 
						where e.active_ind = 1
						
						order by e.event_start_dt_tm desc";
							
				$result = $conn->query($sql);
				
				while($row = $result->fetch()){
					//store sql results
					$ID = $row["id"];
					$name = $row["name"];
					$elims_to_date = "0";
					$start = $row["EVENT_START_DT_TM"];
					$end = $row["EVENT_END_DT_TM"];
					$edit = "edit";
					$delete = "delete";
					$type = "event";
					$ongoing = (strtotime(date("Y-m-d")) >= strtotime($start) and strtotime(date("Y-m-d")) <= strtotime($end));
					if($ongoing) {
						$ongoing_img = '<img src="img/green_dot.png">';
					}
					else {
						$ongoing_img = '<img src="img/red_dot.png">';
					}
					//output to table
					echo "<tr>";
										
					echo "<td>".$ID."</td>";
					echo "<td><a href='tournaments.php?event_id={$ID}&event_name={$name}'>".$name."</a></td>";
					echo "<td>".$elims_to_date."</td>";
					echo "<td>".$start."</td>";
					echo "<td>".$end."</td>";
					echo "<td>".$ongoing_img."</td>";

					//if session[event access id] = {id}   prob add image
					echo "<td><a href='edit_event.php?event_id={$ID}&event_name={$name}'>".$edit."</a></td>";
					//endif
					//ditto
					echo "<td><a href='delete.php?event_id={$ID}&callerType={$type}'>".$delete."</a></td>";

                    echo "</tr>";               

				}				
			}
			catch( \Exception $e){
				echo $e->getMessage();
			}	
			
			$conn = null; //disconnect
		?>
		</tbody>
	</table>

</html>