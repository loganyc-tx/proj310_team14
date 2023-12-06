<!-- Author: Caleb Williamson, UIN: 128009239 -->
<?php
$uin = "123456789";
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
function display_internship_table_header()
{
    return "<table><tr><th>UIN</th><th>Internship ID</th><th>Internship Name</th><th>Description</th><th>Government_Internship</th><th>Application_Number</th><th>Application_Status</th><th>Year_Applied</th>";
}

function display_internship_table_row($uin, $intern_id, $name, $description, $gov_intern, $app_num, $app_status, $year)
{
    return "<tr><td>" . $uin . "</td><td>" . $intern_id . "</td><td>" . $name . "</td><td>" . $description . "</td><td>" . $gov_intern . "</td><td>" . $app_num . "</td><td>" . $app_status . "</td><td>" . $year . "</td></tr>";
}

function display_internship_table_footer()
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
    <title>Internship Tracking Page</title>
</head>

<body>
    <div>
        <div>
            <h1>
                Internship Tracking Page
            </h1>
        </div>
        <!-- View Internship Status -->
        <div>
            <h3>
                View
            </h3>
            <?php
            try {
                require "view_internship.php";
            } catch (Exception $e) {
                echo "Fatal Error occured when performing Internship Search. Error Message: " . $e->getMessage() . "Error Trace: " . $e->getTrace();
            }
            ?>
        </div>
        <!-- Insert Internship Status -->
        <div>
            <h3>
                Insert
            </h3>
            <form action="internship_tracking.php" method="post">
                Internship ID: <input type="text" name="intern_id">
                <br></br>
                Status: <input type="text" name="status">
                <br></br>
                Year: <input type="text" name="year">
                <br></br>
                <input type="submit" value="Insert">
            </form>
            <?php
            if (!isset($_POST["ia_num"]) and isset($_POST["intern_id"]) and isset($_POST["status"]) and isset($_POST["year"])) {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    try {
                        require "insert_internship.php";
                    } catch (Exception $e) {
                        echo "Fatal Error occured when inserting internship status. Error Message: " . $e->getMessage() . "Error Trace: " . $e->getTrace();
                    }
                }
            }
            ?>
        </div>
        <!-- Update Internship Status -->
        <div>
            <h3>
                Update
            </h3>
            <form action="internship_tracking.php" method="post">
                Intern Application Number: <input type="text" name="ia_num">
                <br></br>
                Intern ID: <input type="text" name="intern_id">
                <br></br>
                Status: <input type="text" name="status">
                <br></br>
                Year: <input type="text" name="year">
                <br></br>
                <input type="submit" value="Update">
            </form>
            <?php
            if (isset($_POST["ia_num"]) and isset($_POST["intern_id"]) and isset($_POST["status"]) and isset($_POST["year"])) {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    try {
                        require "update_internship.php";
                    } catch (Exception $e) {
                        echo "Fatal Error occured when updating internship status. Error Message: " . $e->getMessage() . "Error Trace: " . $e->getTrace();
                    }
                }
            }
            ?>
        </div>
        <!-- Delete Internship Status -->
        <div>
            <h3>
                Delete
            </h3>
            <form action="internship_tracking.php" method="post">
                Internship Application Number: <input type="text" name="ia_num">
                <br></br>
                <input type="submit" value="Delete">
            </form>
            <?php
            if (isset($_POST["ia_num"]) and !isset($_POST["intern_id"]) and !isset($_POST["status"]) and !isset($_POST["year"])) {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    try {
                        require "delete_internship.php";
                    } catch (Exception $e) {
                        echo "Fatal Error occured when deleting internship status. Error Message: " . $e->getMessage() . "Error Trace: " . $e->getTrace();
                    }
                }
            }
            ?>
        </div>
        <!-- Navigation -->
        <div>
            <button><a href="../student_tracking.php">Go Back</a></button>
        </div>
    </div>
</body>

</html>

<?php
// Reset POST request values
$_POST["ia_num"] = null;
$_POST["uin"] = null;
$_POST["intern_id"] = null;
$_POST["status"] = null;
$_POST["year"] = null;

// Close connection
$database_connection->close();
?>