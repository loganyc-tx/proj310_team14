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
    <title>Delete Report Page</title>
</head>

<body>
    <div>
        <div>
            <h1>
                Delete Report Page
            </h1>
        </div>
        <!-- Student Program Search -->
        <div>
            <h3>
                Enter Report to Delete
            </h3>
            <form action="delete_report.php" method="post">
                Report ID: <input type="text" name="report_id">
                <br></br>
                <input type="submit" value="Delete">
            </form>
            <?php
            if (isset($_POST["report_id"])) {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    try {
                        // require "student_program_search.php";
                        // Write code for deleting report here
                        $report_id = "";
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $report_id = filter_input_data($_POST["report_id"]);
                        }


                        // Check if intended entry to does not exist
                        $select_query = "SELECT report_id FROM reports WHERE report_id = '" . $report_id . "'";
                        $select_result = $database_connection->query($select_query);
                        if ($select_result->num_rows == 0) {
                            echo "Report does not exists.";
                        } else {
                            // Exectue query to delete report from database
                            $delete_query = "DELETE FROM reports WHERE report_id = '" . $report_id . "'";
                            $delete_result = $database_connection->query($delete_query);
                            if ($delete_result == FALSE) {
                                echo "Error occured deleting data in table.";
                            } else {
                                echo "Data Successfully Updated.";
                            }
                        }
                    } catch (Exception $e) {
                        echo "Fatal Error occured when performing Delete Report Action. Error Message: " . $e->getMessage() . "Error Trace: " . $e->getTrace();
                    }
                }
            }
            ?>
        </div>
        <div>
            <!-- Go back button -->
            <!-- Navigation -->
            <div>
                <button><a href="../admin_tracking.php">Go Back</a></button>
            </div>
        </div>
    </div>
</body>

</html>