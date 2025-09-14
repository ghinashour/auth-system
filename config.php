<?php
//host
$host = "localhost";

//databasename
$dbname = "auth-sys";

//user 
$user = "root";


//password
$pass = "";

$conn = new PDO("mysql:$host;dbname=$dbname;", $user, $pass);

if ($conn == true) {
    echo "it's working fine";
} else {
    echo "connection is wrong: err";
}