<!-- COMPLETED BY SAM HIRVILAMPI -->
<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the index page or login page
header("Location: index.php"); // Change 'index.php' to your desired destination
exit();
?>
