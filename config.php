<?php
//host
$host = "localhost";

//databasename
$dbname = "auth-sys";

//user 
$user = "root";


//password
$pass = "";
$conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

if ($conn == true) {
    return true;
} else {
    echo "connection is wrong: err";
}