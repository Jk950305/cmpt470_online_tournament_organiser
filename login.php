<?php
session_start();

// User is already logged in
if(isset($_SESSION["user"]))
{
    echo "<script>
            window.location.href = './my_tournament.php';
        </script>";
}

?>

<?php

function login()
{
    if($_SERVER["REQUEST_METHOD"] != "POST")
    {
        return;
    }
    include('processing/useraccounts.php');
    // if(1){ dbListAllUsers(); }
    $sanitizedEmail = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    $result = db_findUser($sanitizedEmail);

    // User (email) doesnt exist
    if(!$result->rowCount())
    {
        echo "ERROR - User does not exist:<br>";
        echo $sanitizedEmail;
        return;
    }

    // User exists, check password
    $user = $result->fetch();

    // Bad password
    if(!password_verify($_POST["password"], $user["password_hash"])) 
    {
        echo "ERROR: Incorrect password";
        return;
    }

    // Password ok..
    $_SESSION["user"] = $user;

    echo "<script>
            window.location.href = './my_tournament.php';
        </script>";
}

?>




<?php

function form_refillValue($key)
{
    if(isset($_POST[$key])) 
    {
        echo "value=" . $_POST[$key];
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
	<title>Login</title>

    <link href="https://fonts.googleapis.com/css?family=Nanum+Gothic&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css" href="layout.css">
</head>
<body class="container">
    <?php include_once("header.php"); ?>



	<form action="" method="POST">

	    <h2>Login</h2>
	    <br>

	 	<table>
		 	<tr>
				<td>
					<label>Email</label>
				</td>
				<td>
					<input type="email" id="email" name="email" required 
		            	<?php form_refillValue("email"); ?>
		        	>
		        </td>
			</tr>
			<tr>
				<td>
					<label>Password&nbsp;</label>
				</td>
				<td>
					<input type="password" id="password" name="password" required
		        	>
		        </td>
			</tr>
		</table>

		<input type="submit" value="LOGIN">
        <br>
        <br>

        <span>Don't have have an account?</span><a href="./register.php"> Register</a>
        <br>
	</form>
    <br>

    <div>
        <?php login() ?>
    </div>

</body>
</html>
