<?php
// Establish a connection to the database
$host = "localhost";  // Change this to your database host
$user = "root";       // Change this to your database username
$password = "";       // Change this to your database password
$database = "csce310_team14";  // Change this to your database name

$conn = new mysqli($host, $user, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process the login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT UIN, User_Type, access FROM user WHERE Username=? AND Passwords=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->bind_result($uin, $userType, $access);

    if ($stmt->fetch()) {
        // Login successful
        session_start();
        $_SESSION["username"] = $username;
        $_SESSION["userType"] = $userType;
        $_SESSION["uin"] = $uin;
        // Redirect based on user type
        if ($userType == "admin" && $access == 1) {
            header("Location: admin_page.php");
        } elseif ($userType == "student" && $access == 1) {
            header("Location: student_page.php");
        } else {
            echo "Invalid Username/Password or Missing Permissions.";
        }

        exit();
    } else {
        // Login failed
        echo "Invalid username or password";
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>
