<?php
// Establish a connection to the database
$host = "localhost";
$user = "root";
$password = "";
$database = "csce310_team14";

$conn = new mysqli($host, $user, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}