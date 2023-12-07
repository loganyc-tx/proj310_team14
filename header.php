<?php


function logout() {
    // Unset all session variables
    $_SESSION = array();

    session_destroy();

    // Redirect to the index page
    header("Location: index.php");
    exit();
}

// Display "Logged in as: $uin" and Logout button
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
    <style>
        .top-right {
            position: fixed;
            top: 0;
            right: 0;
            padding: 10px;
        }
    </style>
</head>
<body>

<!-- Display "Logged in as: $uin" and Logout button -->
<div class="top-right">
    <span><?php echo $loggedInMessage; ?></span>
    <?php
    // Display the logout button if the user is logged in
    if (isset($_SESSION["uin"])) {
        echo ' | <a href="logout.php">Logout</a>';
    }
    ?>
</div>