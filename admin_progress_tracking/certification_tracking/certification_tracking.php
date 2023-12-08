<!-- Author: Caleb Williamson, UIN: 128009239 -->
<?php
// Verify that user is an admin
session_start();
$user_type = $_SESSION["userType"];
if($user_type !== "admin") {
    header("Location: ../index.php");
    die("You are not an admin.");
}
// Connect to SQL database
$server = "localhost";
$username = "root";
$password = "";
$db = "csce310_team14";
$database_connection = new mysqli($server, $username, $password, $db);
if($database_connection->connect_error) { // Check for valid conneciton
    die("Failed to connect to database: ".$database_connection->connect_error);
}

// Functions that return the table to display results
function display_cert_table_header() {
    return "<table><tr><th>Certification Enrollement Number</th><th>User's Name</th><th>Certification ID</th><th>Certification Name</th><th>Level</th><th>Program Number</th><th>Semester</th><th>Year</th><th>Status</th><th>Training Status</th>";
}

function display_cert_table_row($course_enroll_num, $user_name, $cert_id, $cert_name, $level, $program_num, $semester, $year, $status, $train_status) {
    return "<tr><td>".$course_enroll_num."</td><td>".$user_name."</td><td>".$cert_id."</td><td>".$cert_name."</td><td>".$level."</td><td>".$program_num."</td><td>".$semester."</td><td>".$year."</td><td>".$status."</td><td>".$train_status."</td></tr>";
}

function display_cert_table_footer() {
    return "</table>";
}

// Function to filter input to prevent cross-site scripting
function filter_input_data($input) {
    $input = htmlspecialchars($input);
    $input = trim($input);
    $input = stripslashes($input);
    return $input;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certification Tracking Page</title>
</head>

<body>
    <div>
        <div>
            <h1>
                Certification Tracking Page
            </h1>
        </div>
        <!-- View Certification Status -->
        <div>
            <h3>
                View
            </h3>
            <form action="certification_tracking.php" method="post">
                UIN: <input type="text" name="uin">
                <br></br>
                <input type="submit" value="View">
            </form>
            <?php
            if(!isset($_POST["ce_num"]) and isset($_POST["uin"]) and !isset($_POST["cert_id"]) and !isset($_POST["program_num"]) and !isset($_POST["semester"]) and !isset($_POST["year"]) and !isset($_POST["status"]) and !isset($_POST["train_status"])) {
                if($_SERVER["REQUEST_METHOD"] == "POST") {
                    try {
                        require "view_certification.php";
                    } catch (Exception $e) {
                        echo "Fatal Error occured when performing Class Search. Error Message: ".$e->getMessage()."Error Trace: ".$e->getTrace();
                    }
                }
            }
            ?>
        </div>
        <!-- Insert Certification Status -->
        <div>
            <h3>
                Insert
            </h3>
            <form action="certification_tracking.php" method="post">
                UIN: <input type="text" name="uin">
                <br></br>
                Certification ID: <input type="text" name="cert_id">
                <br></br>
                Program Number: <input type="text" name="program_num">
                <br></br>
                Semester: <input type="text" name="semester">
                <br></br>
                Year: <input type="text" name="year">
                <br></br>
                Status: <input type="text" name="status">
                <br></br>
                Training Status: <input type="text" name="train_status">
                <br></br>
                <input type="submit" value="Insert">
            </form>
            <?php
            if(!isset($_POST["ce_num"]) and isset($_POST["uin"]) and isset($_POST["cert_id"]) and isset($_POST["program_num"]) and isset($_POST["semester"]) and isset($_POST["year"]) and isset($_POST["status"]) and isset($_POST["train_status"])) {
                if($_SERVER["REQUEST_METHOD"] == "POST") {
                    try {
                        require "insert_certification.php";
                    } catch (Exception $e) {
                        echo "Fatal Error occured when inserting certification status. Error Message: ".$e->getMessage()."Error Trace: ".$e->getTrace();
                    }
                }
            }
            ?>
        </div>
        <!-- Update Certification Status -->
        <div>
            <h3>
                Update
            </h3>
            <form action="certification_tracking.php" method="post">
                Course Enrollment Number: <input type="text" name="ce_num">
                <br></br>
                UIN: <input type="text" name="uin">
                <br></br>
                Certification ID: <input type="text" name="cert_id">
                <br></br>
                Program Number: <input type="text" name="program_num">
                <br></br>
                Semester: <input type="text" name="semester">
                <br></br>
                Year: <input type="text" name="year">
                <br></br>
                Status: <input type="text" name="status">
                <br></br>
                Training Status: <input type="text" name="train_status">
                <br></br>
                <input type="submit" value="Insert">
            </form>
            <?php
            if(isset($_POST["ce_num"]) and isset($_POST["uin"]) and isset($_POST["cert_id"]) and isset($_POST["program_num"]) and isset($_POST["semester"]) and isset($_POST["year"]) and isset($_POST["status"]) and isset($_POST["train_status"])) {
                if($_SERVER["REQUEST_METHOD"] == "POST") {
                    try {
                        require "update_certification.php";
                    } catch (Exception $e) {
                        echo "Fatal Error occured when updating status. Error Message: ".$e->getMessage()."Error Trace: ".$e->getTrace();
                    }
                }
            }
            ?>
        </div>
        <!-- Navigation -->
        <div>
            <button><a href="../admin_tracking.php">Go Back</a></button>
        </div>
    </div>
</body>

</html>

<?php
// Reset POST request values
$_POST["ce_num"] = null;
$_POST["uin"] = null;
$_POST["cert_id"] = null;
$_POST["program_num"] = null;
$_POST["semester"] = null;
$_POST["year"] = null;
$_POST["status"] = null;
$_POST["train_status"] = null;

// Close connection
$database_connection->close();
?>