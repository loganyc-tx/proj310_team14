<!-- COMPLETED BY ROCK KANZARKAR -->
<?php
// Database connection parameters
$host = "localhost";     // Hostname where your database server is running
$user = "root";          // Database username
$password = "";          // Database password
$database = "csce310_team14";  // Database name

// Create a new mysqli object for establishing a database connection
$conn = new mysqli($host, $user, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    // If the connection fails, terminate the script and display an error message
    die("Connection failed: " . $conn->connect_error);
}
