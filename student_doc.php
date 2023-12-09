<!-- COMPLETED BY ROCK KANZARKAR -->
<?php
    // Set up error reporting and include database connection
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
    include_once 'dbh.inc.php';

    // Start a session to manage user data
    session_start();

    // Check if the user is logged in and is a student
    if (!isset($_SESSION["username"]) || $_SESSION["userType"] !== "student") {
        http_response_code(401); // Unauthorized
        exit();
    }

    // Retrieve student information using the stored UIN in the session
    if (isset($_SESSION["uin"])) {
        $uin = $_SESSION["uin"];
        // Rest of your code goes here
    } else {
        die("UIN not set in the session");
    }

    // Check if the form is submitted for updating a document
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateEvent'])) {
        $docNum = $_POST['Doc_Num'];
        $link = $_POST['editLink'];
        $docType = $_POST['editDoc_Type'];

        // Update the database with the new values
        $updateQuery = "UPDATE document
                        SET Link = ?, Doc_Type = ?
                        WHERE Doc_Num = ?";

        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('sss', $link, $docType, $docNum);

        if ($updateStmt->execute()) {
            // Document updated successfully
            // You can add a success message or redirect the user
        } else {
            echo "Error updating document: " . $updateStmt->error;
        }

        $updateStmt->close();
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
    <h2>Documents</h2>
    <nav>
        <ul>
            <li><a href="student_page.php">Profile Management</a></li>
            <li><a href="student_apps.php">Application Information</a></li>
            <li><a href="student_progress_tracking/student_tracking.php">Program Tracking</a></li>
            <li><a href="student_event.php">Events</a></li>
            <li><a href="student_doc.php">Document Management</a></li>
        </ul>
    </nav>

    <!-- Button to toggle the document addition form -->
    <a class="btn btn-primary" onclick="toggleForm()">Add Document</a>
    
    <!-- Form initially hidden with inline style -->
    <form id="eventForm" action="add_doc.php" method="post" style="display:none;">
        <!-- Form fields for adding a document -->
        <label for="App_Num">Application Number:</label>
        <input type="text" id="App_Num" name="App_Num" required><br>
        <label for="Link">Link:</label>
        <input type="text" id="Link" name="Link" required><br>
        <label for="Doc_Type">Document Type:</label>
        <input type="text" id="Doc_Type" name="Doc_Type" required><br>
        <input type="submit" value="Add Document">
    </form>

    <!-- Popup for editing a document -->
    <div id="editPopup" class="popup" style="display: none;">
        <div class="popup-content">
            <span class="close" onclick="closePopup()">&times;</span>
            <!-- Edit form for updating database -->
            <form id="editEventForm" method="post" action="">
                <input type="hidden" id="editDoc_Num" name="Doc_Num" value="">
                <label for="editLink">Link:</label>
                <input type="text" id="editLink" name="editLink"><br>
                <label for="editDoc_Type">Document Type:</label>
                <input type="text" id="editDoc_Type" name="editDoc_Type" required><br>
                <input type="submit" name="updateEvent" value="Update Document">
            </form>
        </div>
    </div>

    <!-- Table to display documents -->
    <table class="table">
        <thead>
            <tr>
                <th>Document Number</th>
                <th>Application ID</th>
                <th>Link</th>
                <th>Document Type</th>
                <th>Delete</th>
                <th>Update</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // Fetch and display documents associated with the session UIN
                $fetchDocumentsQuery = "SELECT D.Doc_Num, D.App_Num, D.Link, D.Doc_Type
                FROM document D
                JOIN applications A ON D.App_Num = A.App_Num
                WHERE A.UIN = $uin";

                $result = $conn->query($fetchDocumentsQuery);

                if ($result->num_rows > 0) {
                    // Display documents in the table
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['Doc_Num'] . "</td>";
                        echo "<td>" . $row['App_Num'] . "</td>";
                        echo "<td><a href='" . $row['Link'] . "' target='_blank'>" . $row['Link'] . "</a></td>";
                        echo "<td>" . $row['Doc_Type'] . "</td>";
                        echo "<td><a href='del_doc.php?a={$row['Doc_Num']}'>Remove</a></td>";
                        echo "<td><button onclick='showRowInfo(this)'>Edit</button></td>";
                        echo "</tr>";
                    }
                } else {
                    // Display a message if no documents are found
                    echo "<tr><td colspan='6'>No documents found</td></tr>";
                }
            ?>
        </tbody>
    </table>

    <!-- JavaScript functions for toggling form visibility and editing popup -->
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
            document.getElementById("editDoc_Num").value = cells[0].innerText;
            document.getElementById("editLink").value = cells[2].innerText;
            document.getElementById("editDoc_Type").value = cells[3].innerText;

            showPopup();
        }
    </script>
</body>
</html>















