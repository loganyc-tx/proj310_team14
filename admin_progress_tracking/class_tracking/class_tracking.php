<!-- Author: Caleb Williamson, UIN: 128009239 -->
<?php
// Connect to SQL database
$server = "localhost";
$username = "root";
$password = "";
$db = "csce310_team14";
$database_connection = new mysqli($server, $username, $password, $db);
if ($database_connection->connect_error) { // Check for valid conneciton
    die("Failed to connect to database: " . $database_connection->connect_error);
}

// Functions that return the table to display results
function display_class_table_header()
{
    return "<table><tr><th>Course Enrollment Number</th><th>User's Name</th><th>Class ID</th><th>Class Name</th><th>Class Type</th><th>Semester</th><th>Year</th><th>Status</th>";
}

function display_class_table_row($course_enroll_num, $user_name, $class_id, $class_name, $class_type, $semester, $year, $status)
{
    return "<tr><td>" . $course_enroll_num . "</td><td>" . $user_name . "</td><td>" . $class_id . "</td><td>" . $class_name . "</td><td>" . $class_type . "</td><td>" . $semester . "</td><td>" . $year . "</td><td>" . $status . "</td></tr>";
}

function display_class_table_footer()
{
    return "</table>";
}

// Function to filter input to prevent cross-site scripting
function filter_input_data($input)
{
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
    <title>Class Tracking Page</title>
</head>

<body>
    <div>
        <div>
            <h1>
                Class Tracking Page
            </h1>
        </div>
        <!-- View Class Status -->
        <div>
            <h3>
                View
            </h3>
            <form action="class_tracking.php" method="post">
                UIN: <input type="text" name="uin">
                <br></br>
                <input type="submit" value="View">
            </form>
            <?php
            if (!isset($_POST["ce_num"]) and isset($_POST["uin"]) and !isset($_POST["class_id"]) and !isset($_POST["status"]) and !isset($_POST["semester"]) and !isset($_POST["year"])) {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    try {
                        require "view_class.php";
                    } catch (Exception $e) {
                        echo "Fatal Error occured when performing Class Search. Error Message: " . $e->getMessage() . "Error Trace: " . $e->getTrace();
                    }
                }
            }
            ?>
        </div>
        <!-- Insert Class Status -->
        <div>
            <h3>
                Insert
            </h3>
            <form action="class_tracking.php" method="post">
                UIN: <input type="text" name="uin">
                <br></br>
                Class ID: <input type="text" name="class_id">
                <br></br>
                Status: <input type="text" name="status">
                <br></br>
                Semester: <input type="text" name="semester">
                <br></br>
                Year: <input type="text" name="year">
                <br></br>
                <input type="submit" value="Insert">
            </form>
            <?php
            if (!isset($_POST["ce_num"]) and isset($_POST["uin"]) and isset($_POST["class_id"]) and isset($_POST["status"]) and isset($_POST["semester"]) and isset($_POST["year"])) {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    try {
                        require "insert_class.php";
                    } catch (Exception $e) {
                        echo "Fatal Error occured when inserting class status. Error Message: " . $e->getMessage() . "Error Trace: " . $e->getTrace();
                    }
                }
            }
            ?>
        </div>
        <!-- Update Class Status -->
        <div>
            <h3>
                Update
            </h3>
            <form action="class_tracking.php" method="post">
                Course Enrollment Number: <input type="text" name="ce_num">
                <br></br>
                UIN: <input type="text" name="uin">
                <br></br>
                Class ID: <input type="text" name="class_id">
                <br></br>
                Status: <input type="text" name="status">
                <br></br>
                Semester: <input type="text" name="semester">
                <br></br>
                Year: <input type="text" name="year">
                <br></br>
                <input type="submit" value="Update">
            </form>
            <?php
            if (isset($_POST["ce_num"]) and isset($_POST["uin"]) and isset($_POST["class_id"]) and isset($_POST["status"]) and isset($_POST["semester"]) and isset($_POST["year"])) {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    try {
                        require "update_class.php";
                    } catch (Exception $e) {
                        echo "Fatal Error occured when updating class status. Error Message: " . $e->getMessage() . "Error Trace: " . $e->getTrace();
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
$_POST["class_id"] = null;
$_POST["status"] = null;
$_POST["semester"] = null;
$_POST["year"] = null;

// Close connection
$database_connection->close();
?>