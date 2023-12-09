<!-- COMPLETED BY ROCK KANZARKAR -->
<?php
    // Set up error reporting and include database connection
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
    include_once 'dbh.inc.php';

    // Start a session to manage user data
    session_start();

    // Check if the user is logged in and is an admin
    if (!isset($_SESSION["username"]) || $_SESSION["userType"] !== "admin") {
        http_response_code(401); // Unauthorized
        exit();
    }

    // Retrieve admin information using the stored UIN in the session
    if (isset($_SESSION["uin"])) {
        $uin = $_SESSION["uin"];
        // Rest of your code goes here
    } else {
        die("UIN not set in the session");
    }
?>

<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tags and Bootstrap CSS link -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        /* Inline CSS styles for the page layout */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Styles for header and navigation */
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

        /* Styles for content area */
        .content {
            padding: 20px;
            text-align: center;
        }
    </style>
</head>

<body style="margin: 50px;">
    <!-- Page header and navigation links -->
    <h2>Event Attendance</h2>
    <nav>
        <ul>
            <li><a href="admin_page.php">Role Management</a></li>
            <li><a href="admin_progress_tracking/admin_tracking.php">Program Tracking</a></li>
            <li><a href="admin_apps.php">Program Information</a></li>
            <li><a href="all_event.php">Events</a></li>
            <li><a href="event_tracking_admin.php">Event Tracking</a></li>
        </ul>
    </nav>
    
    <!-- Button to toggle event sign-up form -->
    <a class="btn btn-primary" onclick="toggleForm()">Sign Up For Event</a>
    
    <!-- Form initially hidden with inline style -->
    <form id="eventForm" action="add_event_track_admin.php" method="post" style="display:none;">
            <!-- <label for="uin">UIN:</label>
            <input type="text" id="uin" name="uin" required><br> -->
            <label for="UIN">UIN:</label>
            <input type="text" id="UIN" name="UIN" required><br>
            <label for="Event_ID">Event ID:</label>
            <input type="text" id="Event_ID" name="Event_ID" required><br>
            <input type="submit" value="Submit">
    </form>
    
    <!-- Table to display event attendance details -->
    <table class="table">
        <thead>
            <tr>
                <th>Event ID</th>
                <th>Program Name</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Type</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
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

            // Fetch and display events from the database
            $fetchEventsQuery = "SELECT * FROM eventattendance";
            $result = $conn->query($fetchEventsQuery);

            if ($result->num_rows > 0) {
                // Display event attendance details in the table
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['Event_ID'] . "</td>";
                    echo "<td>" . $row['Program_Name'] . "</td>";
                    echo "<td>" . $row['First_Name'] . "</td>";
                    echo "<td>" . $row['Last_Name'] . "</td>";
                    echo "<td>" . $row['User_Type'] . "</td>";
                    echo "<td><a href='del_event_tracking.php?a={$row['ET_Num']}'>Remove</a></td>";
                    echo "</tr>";
                }
            } else {
                // Display a message if no events are found
                echo "<tr><td colspan='11'>No events found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- JavaScript function to toggle form visibility -->
    <script>
        function toggleForm() {
            var form = document.getElementById("eventForm");
            form.style.display = (form.style.display === "none") ? "block" : "none";
        }
    </script>
</body>

</html>
