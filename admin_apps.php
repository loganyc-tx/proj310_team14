<?php
######Logan Chen
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION["username"]) || $_SESSION["userType"] !== "admin") {
    header("Location: index.php"); // Redirect to login if not logged in or not an admin
    echo "Please log in with an admin account!";
    exit();
}

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

function insertProgram($Name, $Description) {
    // Implement your logic for inserting a new admin
    // Make sure to hash the password before storing it in the database
    // For example: $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    echo "Creating new program";
    global $conn; // Assuming $conn is your database connection object


    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO programs (Name, Description, Visible) VALUES (?, ?, ?)");
    $Visible = 1;
    // Bind parameters
    $stmt->bind_param("ssi", $Name, $Description, $Visible);

    // Execute the statement
    $stmt->execute();

    // Check if the insertion was successful
    if ($stmt->affected_rows > 0) {
        echo "Program added";
    } else {
        echo "Error adding program: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}
// Process Insert Action
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && $_POST["action"] === "insert") {
        $Name = $_POST['Name'];
        $Description = $_POST["Description"];
       

        insertProgram($Name, $Description);
    }
}

function updateProgram($Program_num, $Name, $Description) {
    global $conn; // Assuming $conn is your database connection object
    
    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE programs SET Name=?, Description=? WHERE Program_num=?");

    // Bind parameters
    $stmt->bind_param("ssi", $Name, $Description, $Program_num);

    // Execute the statement
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        echo "Program updated successfully!";
    } else {
        echo "Error updating program: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Process Update Action
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && $_POST["action"] === "update") {
        $NameToUpdate = $_POST["updateName"];
        $DescriptionToUpdate = $_POST["updateDescription"];
        $ProgNum = $_POST["selectedProgramId"];

        updateProgram($ProgNum, $NameToUpdate, $DescriptionToUpdate);
    }
}

function selectProgram() {
    global $conn; // Assuming $conn is your database connection object

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT Program_num, Name, Description, Visible FROM Programs");

    // Execute the statement
    $stmt->execute();

    // Bind the result variables
    $stmt->bind_result($Program_num, $Name, $Description, $Visible);

    // Display the results
    echo "<h4>List of Programs:</h4>";
    echo "<table border='1'>";
    echo "<tr><th>Program Number</th><th>Program Name</th><th>Description</th><th>Visible</th>";

    while ($stmt->fetch()) {
        echo "<tr><td>$Program_num</td><td>$Name</td><td>$Description</td><td>$Visible</td>";
    }

    echo "</table>";

    // Close the statement
    $stmt->close();
}

$sql = "SELECT Program_num, name FROM Programs"; 
$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && $_POST["action"] === "select") {

            selectProgram();
        }
}

function toggleProgramVisibility($Program_num) {
    global $conn;

    // Check the current state of the Visible flag
    $checkStmt = $conn->prepare("SELECT Visible FROM programs WHERE Program_num=?");
    $checkStmt->bind_param("i", $Program_num);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_assoc();

    // Toggle the Visible flag
    $Visible = isset($row['Visible']) && $row['Visible'] == 0 ? 1 : 0;

    // Update the Visible flag
    $stmt = $conn->prepare("UPDATE programs SET Visible=? WHERE Program_num=?");
    $stmt->bind_param("ii", $Visible, $Program_num);
    $stmt->execute();
    $stmt->close();
}

function deleteProgram($Program_num) {
    global $conn;

    // Prepare the SQL statement for deletion
    $stmt = $conn->prepare("DELETE FROM programs WHERE Program_num=?");
    $stmt->bind_param("i", $Program_num);
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && $_POST["action"] === "delete") {
        $ProgNum = $_POST["selectedProgramId"];
        if (isset($_POST["fullDel"])) {
            deleteProgram($ProgNum);
        } else {
            toggleProgramVisibility($ProgNum);
        }
    }
}

function generateAndStoreReport() {
    global $conn;
    $reportContent = "Report Generated on: " . date("Y-m-d H:i:s") . "\n\n";

    // Example metrics (replace with actual queries)
    $queries = [
        "Total Students" => "SELECT COUNT(*) AS total FROM `college_student`",
        "# of students with completed certs" => "SELECT COUNT(*) AS total FROM applications WHERE Com_Cert != ''",
        "# of Hispanic/Latino students" => "SELECT COUNT(*) AS total FROM `college_student` WHERE `Hispanic_Latino` = 1",
        "# of male students students" => "SELECT COUNT(*) AS total FROM `college_student` WHERE `Gender` = 'male'",
        "# of female students students" => "SELECT COUNT(*) AS total FROM `college_student` WHERE `Gender` = 'female'",
        "Majors Distribution" => "SPECIAL_QUERY"
        // Add more queries for other metrics
    ];

    foreach ($queries as $metric => $query) {
        if ($query == "SPECIAL_QUERY") {
            // Handle the majors query separately
            $majorsQuery = "SELECT Major, COUNT(*) as count FROM `college_student` GROUP BY Major";
            $majorsResult = $conn->query($majorsQuery);
            if ($majorsResult) {
                $majorsReport = "";
                while ($row = $majorsResult->fetch_assoc()) {
                    $majorsReport .= $row['Major'] . ":" . $row['count'] . ", ";
                }
                $reportContent .= $metric . ": " . rtrim($majorsReport, ", ") . "\n"; // Remove trailing comma
            } else {
                $reportContent .= $metric . ": Data not available\n";
            }
        } else {
            // Handle all other queries
            $result = $conn->query($query);
            if ($result && $row = $result->fetch_assoc()) {
                $reportContent .= $metric . ": " . $row['total'] . "\n";
            } else {
                $reportContent .= $metric . ": Data not available\n";
            }
        }
    }

    // Save report content to a text file
    $fileName = "Report_" . date("Y_m_d_H_i_s") . ".txt";
    file_put_contents($fileName, $reportContent);

    // Insert the report into the database
    $stmt = $conn->prepare("INSERT INTO reports (report_content) VALUES (?)");
    $stmt->bind_param("s", $reportContent);
    $stmt->execute();
    $stmt->close();

    echo "Report generated and stored successfully.";
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generate_report'])) {
    generateAndStoreReport();
}


// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Page (Admin)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            line-height: 1.6;
            padding: 20px;
            margin: 0;
        }

        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }

        header {
            background: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"], button {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover, button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="admin_landing.php">Return to landing</a></li>
        </ul>
        </ul>
    </nav>
    <h2>Admin Functionalities</h2>

    <!-- Insert -->
    <h3>Add New Program</h3>
    <form action="admin_apps.php" method="post">
        <input type="hidden" name="action" value="insert">
        <label for="Name">Program Name:</label>
        <input type="text" id="Name" name="Name" required><br>

        <label for="Name">Description:</label>
        <input type="text" id="Description" name="Description" required><br>

        <input type="submit" value="Add Program">
    </form>

    <!-- Other functionalities... -->
    <h3>Modify Existing Program</h3>
    <form action="admin_apps.php" method="post">
        <input type="hidden" name="action" value="update">

        <label for="programSelect">Select Program:</label>
        <select id="programSelect" name="selectedProgramId" required>
        <option value="" disabled selected>Please select a program</option>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['Program_num'] . "'>" . $row['name'] . "</option>";
                }
            } else {
                echo "<option value=''>No programs available</option>";
            }
            ?>
        </select><br>

        <label for="updateName">Update program name:</label>
        <input type="text" id="updateName" name="updateName" required><br>

        <label for="updateDescription">Update program description:</label>
        <input type="text" id="updateDescription" name="updateDescription" required><br>
        
        <input type="submit" value="Update Program">
    </form>

    <h3>Delete or toggle program visibility</h3>
    <form action="admin_apps.php" method="post">
        <input type="hidden" name="action" value="delete">

        <label for="programSelect">Select Program:</label>
        <select id="programSelect" name="selectedProgramId" required>
        <option value="" disabled selected>Please select a program</option>
            <?php
            mysqli_data_seek($result, 0); //reset to top of list
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['Program_num'] . "'>" . $row['name'] . "</option>";
                }
            } else {
                echo "<option value=''>No programs available</option>";
            }
            ?>
        </select><br>
        <label for="fullDelete">Full Delete:</label>
        <input type="checkbox" id="fullDelete" name="fullDel" value="1"><br>
        <p>Not selecting full delete makes a program non-visible. Doing it again toggles it back to visible.</p>
        <input type="submit" value="Delete Program">
    </form>

    <h4>List of Programs</h4>
    <form action="admin_apps.php" method="post">
    <input type="hidden" name="action" value="select">
    <input type="submit" value="View Programs">
    </form>
    <head>
    <title>Generate Report</title>
    </head>
    <body>
        <form method="post">
            <input type="submit" name="generate_report" value="Generate Report">
        </form>
    </body>
</body>
</html>
