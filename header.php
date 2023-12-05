<?php


function logout() {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the index page or login page
    header("Location: index.php"); // Change 'index.php' to your desired destination
    exit();
}

// Display "Logged in as: $uin" and Logout button in the top-left corner
$loggedInMessage = "";
if (isset($_SESSION["uin"])) {
    $uin = $_SESSION["uin"];
    $loggedInMessage = "Logged in as: $uin";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>
    <!-- Add your other head elements here -->
    <style>
        /* Add your styles for the top-left area here */
        .top-left {
            position: fixed;
            top: 0;
            left: 0;
            padding: 10px;
        }
    </style>
</head>
<body>

<!-- Display "Logged in as: $uin" and Logout button in the top-left corner -->
<div class="top-left">
    <span><?php echo $loggedInMessage; ?></span>
    <?php
    // Display the logout button if the user is logged in
    if (isset($_SESSION["uin"])) {
        echo ' | <a href="logout.php">Logout</a>';
    }
    ?>
</div>
