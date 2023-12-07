<!-- COMPLETED BY SAM HIRVILAMPI -->
<?php
session_start();

// Check if the user is logged in and is a student
if (!isset($_SESSION["username"]) || $_SESSION["userType"] !== "admin") {
    header("Location: login.php"); // Redirect to login page if not logged in as a student
    exit();
}
?>
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Landing Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        nav {
            background-color: #eee;
            padding: 10px;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }

        nav li {
            margin: 0 10px;
        }

        nav a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            font-size: 16px;
        }

        nav a:hover {
            color: #ff6600;
        }

        .content {
            padding: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <header>
        <h1>Admin Panel</h1>
    </header>

    <h2>Welcome, Admin!</h2>

    <!-- Navigation bar -->
    <nav>
        <ul>
            <li><a href="admin_page.php">Role Management</a></li>
            <li><a href="admin_tracking.php">Program Tracking</a></li>
            <li><a href="admin_apps.php">Program Information</a></li>
            <li><a href="event_admin.php">Event Management</a></li>
            <li><a href="event_tracking_admin.php">Event Tracking</a></li>
        </ul>
        </ul>
    </nav>
</body>

</html>
