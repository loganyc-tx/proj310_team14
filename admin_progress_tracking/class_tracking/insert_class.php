<!-- Author: Caleb Williamson, UIN: 128009239 -->
<?php
// Retrieve input uin number
$uin = "";
$class_id = "";
$status = "";
$semester = "";
$year = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uin = filter_input_data($_POST["uin"]);
    $class_id = filter_input_data($_POST["class_id"]);
    $status = filter_input_data($_POST["status"]);
    $semester = filter_input_data($_POST["semester"]);
    $year = filter_input_data($_POST["year"]);
}

// Check if user or class does not exist
$user_query = "SELECT UIN FROM user WHERE UIN = '" . $uin . "'";
$user_result = $database_connection->query($user_query);
$class_query = "SELECT Class_ID FROM classes WHERE Class_ID = '" . $class_id . "'";
$class_result = $database_connection->query($class_query);
if ($user_result->num_rows == 0 or $class_result->num_rows == 0) {
    echo "User or Class does not exist.";
} else {
    // Check if new entry already exists
    $entry_query = "SELECT * FROM class_enrollment WHERE UIN = '" . $uin . "' AND Class_ID = '" . $class_id . "' AND Semester = '" . $semester . "' AND Year = '" . $year . "'";
    $entry_result = $database_connection->query($entry_query);
    if ($entry_result->num_rows >= 1) {
        echo "Duplicate Entry already exists.";
    } else {
        // Execute query to determine the next ce_num to assign to the new data entry
        $ce_query = "SELECT CE_NUM FROM class_enrollment ORDER BY CE_NUM DESC LIMIT 1";
        $ce_result = $database_connection->query($ce_query);
        $row = $ce_result->fetch_assoc();
        $ce_num = $row["CE_NUM"] + 1;
        // Execute SQL queries to create results table
        $insert_query = "INSERT INTO class_enrollment (CE_NUM, UIN, Class_ID, Status, Semester, Year) Values ('" . $ce_num . "', '" . $uin . "', '" . $class_id . "', '" . $status . "', '" . $semester . "', '" . $year . "')";
        $query_result = $database_connection->query($insert_query);
        if ($query_result == FALSE) {
            echo "Error occured inserting data into database.";
        } else {
            echo "Data Successfully Added.";
        }
    }
}
?>