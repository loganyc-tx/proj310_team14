<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');

    include_once 'dbh.inc.php';

    session_start();
    // Check if the user is logged in and is an admin
    if (!isset($_SESSION["username"]) || $_SESSION["userType"] !== "admin") {
        http_response_code(401); // Unauthorized
        exit();
    }

    // Retrieve student information using the stored UIN in the session
    if (isset($_SESSION["uin"])) {
        $uin = $_SESSION["uin"];
        // Rest of your code
    } else {
        die("UIN not set in the session");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateEvent'])) {
        $eventID = $_POST['eventID'];
        $programNum = $_POST['Program_Num'];
        $startDate = $_POST['Start_Date'];
        $startTime = $_POST['Start_Time'];
        $location = $_POST['Location'];
        $endDate = $_POST['End_Date'];
        $endTime = $_POST['End_Time'];
        $eventType = $_POST['Event_Type'];

        // Check if the specified Program_Num exists in the Programs table
        $checkProgramQuery = "SELECT * FROM programs WHERE Program_Num = ?";
        $checkProgramStmt = $conn->prepare($checkProgramQuery);
        $checkProgramStmt->bind_param('i', $programNum);
        $checkProgramStmt->execute();
        $checkProgramResult = $checkProgramStmt->get_result();

        if ($checkProgramResult->num_rows == 0) {
            // Program_Num does not exist in Programs table
            $checkProgramStmt->close();
            echo '<script>
                alert("Program Number does not exist!");
                window.location.href = "event_admin.php";
            </script>';
            exit(); // Exit the script
        }

        // Check if the specified Program_Num exists in the Programs table
        $checkUINQuery = "SELECT * FROM user WHERE UIN = ?";
        $checkUINStmt = $conn->prepare($checkUINQuery);
        $checkUINStmt->bind_param('i', $uin);
        $checkUINStmt->execute();
        $checkUINResult = $checkUINStmt->get_result();

        if ($checkUINResult->num_rows == 0) {
            // Program_Num does not exist in Programs table
            $checkUINStmt->close();
            echo '<script>
                alert("UIN does not exist!");
                window.location.href = "event_admin.php";
            </script>';
            exit(); // Exit the script
        }

        // Update the database with the new values
        $updateQuery = "UPDATE event
                        SET UIN = '$uin', Program_Num = '$programNum', Start_Date = '$startDate',
                            Start_Time = '$startTime', Location = '$location', End_Date = '$endDate',
                            End_Time = '$endTime', Event_Type = '$eventType'
                        WHERE Event_ID = $eventID";

        if ($conn->query($updateQuery) === TRUE) {
            //echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
?>

<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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

<body style= "margin: 50px;">
    <h2>Events</h2>
    <nav>
        <ul>
            <li><a href="admin_page.php">Role Management</a></li>
            <li><a href="admin_progress_tracking/admin_tracking.php">Program Tracking</a></li>
            <li><a href="admin_apps.php">Program Information</a></li>
            <li><a href="event_admin.php">Your Events</a></li>
            <li><a href="event_tracking_admin.php">Event Tracking</a></li>
        </ul>
    </nav>

    <a class="btn btn-primary" onclick="toggleForm()">Add Event</a>
    <!-- Form initially hidden with inline style -->
    <form id="eventForm" action="add_event.php" method="post" style="display:none;">
            <label for="Program_Num">Program Number:</label>
            <input type="text" id="Program_Num" name="Program_Num" required><br>
            <label for="Start_Date">Start Date:</label>
            <input type="date" id="Start_Date" name="Start_Date"><br>
            <label for="Start_Time">Start Time:</label>
            <input type="time" id="Start_Time" name="Start_Time" required><br>
            <label for="Location">Location:</label>
            <input type="text" id="Location" name="Location" required><br>
            <label for="End_Date">End Date:</label>
            <input type="date" id="End_Date" name="End_Date" required><br>
            <label for="End_Time">End Time:</label>
            <input type="time" id="End_Time" name="End_Time" required><br>
            <label for="Event_Type">Event Type:</label>
            <input type="text" id="Event_Type" name="Event_Type" required><br>
            <input type="submit" value="Add Event">
    </form>

    <!-- Edit Popup -->
    <div id="editPopup" class="popup" style="display: none;">
        <div class="popup-content">
            <span class="close" onclick="closePopup()">&times;</span>
            <!-- Edit form for updating database -->
            <form id="editEventForm" method="post" action="">
                <input type="hidden" id="editEventID" name="eventID" value="">
                <label for="editProgram_Num">Program Number:</label>
                <input type="text" id="editProgram_Num" name="Program_Num" required><br>
                <label for="editStart_Date">Start Date:</label>
                <input type="date" id="editStart_Date" name="Start_Date"><br>
                <label for="editStart_Time">Start Time:</label>
                <input type="time" id="editStart_Time" name="Start_Time" required><br>
                <label for="editLocation">Location:</label>
                <input type="text" id="editLocation" name="Location" required><br>
                <label for="editEnd_Date">End Date:</label>
                <input type="date" id="editEnd_Date" name="End_Date" required><br>
                <label for="editEnd_Time">End Time:</label>
                <input type="time" id="editEnd_Time" name="End_Time" required><br>
                <label for="editEvent_Type">Event Type:</label>
                <input type="text" id="editEvent_Type" name="Event_Type" required><br>
                <input type="submit" name="updateEvent" value="Update Event">
            </form>
        </div>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>Event ID</th>
                <th>UIN</th>
                <th>Program Number</th>
                <th>Start Date</th>
                <th>Start Time</th>
                <th>Location</th>
                <th>End Date</th>
                <th>End Time</th>
                <th>Event Type</th>
                <th>Delete</th>
                <th>Edit</th> <!-- New column for edit button -->
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
            $fetchEventsQuery = "SELECT * FROM event";
            $result = $conn->query($fetchEventsQuery);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['Event_ID'] . "</td>";
                    echo "<td>" . $row['UIN'] . "</td>";
                    echo "<td>" . $row['Program_Num'] . "</td>";
                    echo "<td>" . $row['Start_Date'] . "</td>";
                    echo "<td>" . $row['Start_Time'] . "</td>";
                    echo "<td>" . $row['Location'] . "</td>";
                    echo "<td>" . $row['End_Date'] . "</td>";
                    echo "<td>" . $row['End_Time'] . "</td>";
                    echo "<td>" . $row['Event_Type'] . "</td>";
                    echo "<td><a href='del_event.php?a={$row['Event_ID']}'>Remove</a></td>";
                    echo "<td><button onclick='showRowInfo(this)'>Edit</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='11'>No events found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        function toggleForm() {
            var form = document.getElementById("eventForm");
            form.style.display = (form.style.display === "none") ? "block" : "none";
        }

        function showPopup() {
            var popup = document.getElementById("editPopup");
            popup.style.display = "block";
        }

        // Function to close the edit popup
        function closePopup() {
            var popup = document.getElementById("editPopup");
            popup.style.display = "none";
        }

        // Call showPopup function when the Edit button is clicked
        function showRowInfo(button) {
            var row = button.closest('tr');
            var cells = row.getElementsByTagName('td');

            // Populate the edit form with values from the selected row
            document.getElementById("editEventID").value = cells[0].innerText; // Assuming Event_ID is in the first column
            document.getElementById("editProgram_Num").value = cells[2].innerText;
            document.getElementById("editStart_Date").value = cells[3].innerText;
            document.getElementById("editStart_Time").value = cells[4].innerText;
            document.getElementById("editLocation").value = cells[5].innerText;
            document.getElementById("editEnd_Date").value = cells[6].innerText;
            document.getElementById("editEnd_Time").value = cells[7].innerText;
            document.getElementById("editEvent_Type").value = cells[8].innerText;

            showPopup();
        }
    </script>

</body>

</html>