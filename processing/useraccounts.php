<?php

// test to list all users
function dbListAllUsers()
{
    echo "dbTestListAllUsers()<br>";
    include("connection.php");   // $conn for db access
    try
    {
        $sql = "SELECT * FROM users";
        print_r($conn);
        $result = $conn->query($sql);
        if(!$result)
        {
            echo "incorrect query <br>";
        }
        else
        {
            echo "successful query <br>";
            while($row = $result->fetch())
            {
                echo $row["name_first"] . " " . $row["name_last"] . " " . $row["email"] . " " . $row["password_hash"] . "<br>";
            }
        }
        echo "<br>";
    }
    catch (PDOException $e)
    {
        die($e->getMessage());
    }
}


function db_findUser($email)
{
    include("connection.php");   // $conn for db access
    try
    {
        $sql = "SELECT * FROM users 
                WHERE email = :email";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        // print_r($stmt);
        $stmt->execute();

        return $stmt;
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    } 
}


function db_addUser()
{
    include("connection.php");   // $conn for db access
    try
    {
        $sql = 
        "INSERT INTO users (email, 
            password_hash, 
            name_first, 
            name_last, 
            name_first_key, 
            name_last_key, 
            beg_effective_dt_tm, 
            active_ind)
        VALUES (:email, 
            :password_hash, 
            :firstname, 
            :lastname,
            :firstnamekey, 
            :lastnamekey,
            :startdate, 
            :active)";

        $person_id = NULL;
        $passwordhash = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $startdate = date("Y-m-d H:i:s");
        $enddate = NULL;
        $active = 1;

        $stmt = $conn->prepare($sql);
        // $stmt->bindParam(':person_id', $person_id);
        $stmt->bindParam(':email', $_POST["email"]);
        $stmt->bindParam(':password_hash', $passwordhash);
        $stmt->bindParam(':firstname', $_POST["firstname"]);
        $stmt->bindParam(':lastname', $_POST["lastname"]);
        $stmt->bindParam(':firstnamekey', strtoupper($_POST["firstname"]));
        $stmt->bindParam(':lastnamekey', strtoupper($_POST["lastname"]));
        // $stmt->bindParam(':birthday', $_POST["birthday"]);
        $stmt->bindParam(':startdate', $startdate);
        // $stmt->bindParam(':enddate', $enddate);
        $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);

        // print_r($stmt);
        $stmt->execute();
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    } 
}

?>