<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "bootcamp_b7";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// echo "Connected successfully";

?>