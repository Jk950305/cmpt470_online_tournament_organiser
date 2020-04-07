<?php

if(isset($_SESSION["user"]))
{
	echo "<div> 
		<a href='/logout.php'>LOGOUT</a> 
		<div>";
}
else
{
	echo "<div> 
		<a href='/login.php'>LOGIN</a> 
		<div>";
}

?>