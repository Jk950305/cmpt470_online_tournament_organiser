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

function register()
{
    if($_SERVER["REQUEST_METHOD"] != "POST")
    {
        return;
    }
    include('processing/useraccounts.php');
    // if(1){ dbListAllUsers(); }
    $sanitizedEmail = $_POST["email"];
    
  	$result = db_findUser($sanitizedEmail);
	// User already exists
  	if($result->rowCount())
  	{
		echo "ERROR - User already exists with that email:<br>";
		$u = $result->fetch();
        echo $u["email"];
        return;
  	}

    // Create new user
	db_addUser();
    $result = db_findUser($_POST["email"]);
    $user = $result->fetch();

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

function form_refillRadio($key, $val)
{
    if(isset($_POST[$key]) && ($_POST[$key] == $val))
    {
        echo "checked";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
	<title>Register</title>

        <link href="https://fonts.googleapis.com/css?family=Montserrat:600|Nanum+Gothic&display=swap" rel="stylesheet">
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

        <h2>Register</h2>
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
            		<label>Password</label>
                </td>
                <td>
            		<input type="password" id="password" name="password" required
                    >
        		</td>
            </tr>
            <tr>
                <td>
        			<label>First Name&nbsp;</label>
                </td>
                <td>
        		 	<input type="text" id="firstname" name="firstname" required
                    	<?php form_refillValue("firstname"); ?>
                	>
                </td>
            </tr>
        	<tr>
                <td>
        			<label>Last Name</label>
                </td>
                <td>
        			<input type="text" id="lastname" name="lastname" required
                    	<?php form_refillValue("lastname"); ?>
                    >
                </td>
        	</tr>

<!--         <label>Birthday</label>
        <input type="date" id="birthday" name="birthday" required
            <?php //form_refillValue("birthday"); ?>
        >
        <br>

		<label>Account Type</label>
		<br>
		<input type="radio" name="accounttype" value="accounttype-to" required
            <?php //form_refillRadio("accounttype", "accounttype-to"); ?>
        >
		<label>Tournament Organizer</label>
		<br>
		<input type="radio" name="accounttype" value="accounttype-sk" required
            <?php // form_refillRadio("accounttype", "accounttype-sk"); ?>
        >
		<label>Score Keeper</label>
		<br> -->

        </table>
    	<input type="submit" value="REGISTER">
        <br>
        <br>

        <span>Already have an account?</span><a href="./login.php"> Login</a>
        <br>
	</form>
    <br>

    <div>
        <?php register() ?>
    </div>

</body>
</html>
