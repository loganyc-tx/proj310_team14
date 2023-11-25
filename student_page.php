<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "csce310_team14";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["insert_student"])) {
        // Insert new student
        $new_student_username = $_POST['new_student_username'];
        $new_student_password = md5($_POST['new_student_password']); // Use more secure methods in production

        $insert_query = "INSERT INTO user (Username, Passwords, User_Type) VALUES ('$new_student_username', '$new_student_password', 'student')";
        $conn->query($insert_query);
    } elseif (isset($_POST["update_student"])) {
        // Update existing student
        $student_id_to_update = $_POST['student_id_to_update'];
        $updated_student_password = md5($_POST['updated_student_password']); // Use more secure methods in production

        $update_query = "UPDATE user SET Passwords = '$updated_student_password' WHERE id = $student_id_to_update";
        $conn->query($update_query);
    } elseif (isset($_POST["delete_student"])) {
        // Deactivate own account
        $student_id_to_delete = $_SESSION['user_id'];

        $delete_query = "UPDATE user SET User_Type = 'inactive' WHERE id = $student_id_to_delete";
        $conn->query($delete_query);

        // Logout after deactivating account
        session_destroy();
        header("Location: login.html");
        exit();
    }
}

// Select own profile information
$student_id = $_SESSION['user_id'];
$select_query = "SELECT * FROM user WHERE User_Type = 'student' AND id = $student_id";
$result = $conn->query($select_query);
?>

<!-- The rest of your HTML for the student page remains unchanged -->

