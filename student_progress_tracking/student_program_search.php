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
function display_table_header()
{
    return "<table><tr><th>UIN</th><th>User's Name</th><th>Program Name</th>";
}

function display_table_row($uin, $user_name, $prog_name)
{
    return "<tr><td>" . $uin . "</td><td>" . $user_name . "</td><td>" . $prog_name . "</td></tr>";
}

function display_table_footer()
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

// Create View if it does not exist, otherwise upda te it
$sql_query = "SELECT Tracking_Num, UIN, Program_Num FROM track WHERE UIN = '" . $uin . "'";
$query_result = $database_connection->query($sql_query);
if ($query_result->num_rows > 0) {
    echo display_table_header();
    while ($track_row = $query_result->fetch_assoc()) {
        $user_uin = $track_row["UIN"];
        // Exectue query to retrieve Program Title
        $sql_title_query = "SELECT Name FROM programs WHERE Program_Num = '" . $track_row["Program_Num"] . "'";
        $tile_query_result = $database_connection->query($sql_title_query);
        $programs_row = $tile_query_result->fetch_assoc();
        $program_name = $programs_row["Name"];
        // Execute query to retrieve user's name
        $sql_name_query = "SELECT First_Name, Last_Name FROM user WHERE UIN = '" . $track_row["UIN"] . "'";
        $name_query_result = $database_connection->query($sql_name_query);
        $user_row = $name_query_result->fetch_assoc();
        $full_name = $user_row["First_Name"] . " " . $user_row["Last_Name"];
        echo display_table_row($user_uin, $full_name, $program_name);
    }
    echo display_table_footer();
} else {
    echo "No results found for UIN: " . $uin . ". Try again.";
}

// Close connection
$database_connection->close();
?>