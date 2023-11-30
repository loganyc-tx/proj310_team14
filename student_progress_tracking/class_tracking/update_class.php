<!-- Author: Caleb Williamson, UIN: 128009239 -->
<?php
// Retrieve input
$ce_num = "";
$class_id = "";
$status = "";
$semester = "";
$year = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ce_num = filter_input_data($_POST["ce_num"]);
    $class_id = filter_input_data($_POST["class_id"]);
    $status = filter_input_data($_POST["status"]);
    $semester = filter_input_data($_POST["semester"]);
    $year = filter_input_data($_POST["year"]);
}

// Check if intended entry to update exists
$ce_query = "SELECT CE_NUM FROM class_enrollment WHERE CE_NUM = '" . $ce_num . "'";
$ce_result = $database_connection->query($ce_query);
if ($ce_result->num_rows == 0) {
    echo "Course Enrollment Number does not exists.";
} else {
    // Exectue query to update classes table
    $update_query = "UPDATE class_enrollment SET UIN = '" . $uin . "', Class_ID = '" . $class_id . "', Status = '" . $status . "', Semester = '" . $semester . "', Year = '" . $year . "' WHERE CE_NUM = '" . $ce_num . "'";
    $update_result = $database_connection->query($update_query);
    if ($update_result == FALSE) {
        echo "Error occured updating data in table.";
    } else {
        echo "Data Successfully Updated.";
    }
}
?>