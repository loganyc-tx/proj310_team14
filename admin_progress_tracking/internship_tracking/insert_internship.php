<!-- Author: Caleb Williamson, UIN: 128009239 -->
<?php
// Retrieve input uin number
$uin = "";
$intern_id = "";
$status = "";
$year = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uin = filter_input_data($_POST["uin"]);
    $intern_id = filter_input_data($_POST["intern_id"]);
    $status = filter_input_data($_POST["status"]);
    $year = filter_input_data($_POST["year"]);
}

// Check if user or Internship does not exist
$user_query = "SELECT UIN FROM user WHERE UIN = '" . $uin . "'";
$user_result = $database_connection->query($user_query);
$internship_query = "SELECT Intern_ID FROM internship WHERE Intern_ID = '" . $intern_id . "'";
$internship_result = $database_connection->query($internship_query);
if ($user_result->num_rows == 0 or $internship_result->num_rows == 0) {
    echo "User or Internship does not exist.";
} else {
    // Check if new entry already exists
    $entry_query = "SELECT * FROM intern_app WHERE UIN = '" . $uin . "' AND Intern_ID = '" . $intern_id . "' AND Status = '" . $status . "' AND Year = '" . $year . "'";
    $entry_result = $database_connection->query($entry_query);
    if ($entry_result->num_rows >= 1) {
        echo "Duplicate Entry already exists.";
    } else {
        // Execute query to determine the next ia_num to assign to the new data entry
        $ia_query = "SELECT IA_NUM FROM intern_app ORDER BY IA_NUM DESC LIMIT 1";
        $ia_result = $database_connection->query($ia_query);
        $row = $ia_result->fetch_assoc();
        $ia_num = $row["IA_NUM"] + 1;
        // Execute SQL queries to create results table
        $insert_query = "INSERT INTO intern_app (IA_NUM, UIN, Intern_ID, Status, Year) Values ('" . $ia_num . "', '" . $uin . "', '" . $intern_id . "', '" . $status . "', '" . $year . "')";
        $query_result = $database_connection->query($insert_query);
        if ($query_result == FALSE) {
            echo "Error occured inserting data into database.";
        } else {
            echo "Data Successfully Added.";
        }
    }
}
?>