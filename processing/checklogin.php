<?php

// If user is not logged in, redirect to login
if(!isset($_SESSION["user"]))
{
	echo "<script>
            window.location.href = '/login.php';
        </script>";
    exit();
}

?>