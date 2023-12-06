<!-- Author: Caleb Williamson, UIN: 128009239 -->
<?php
// Retrieve input
$ce_num = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ce_num = filter_input_data($_POST["ce_num"]);
}


// Check if intended entry to does not exist
$ce_query = "SELECT CE_NUM FROM class_enrollment WHERE CE_NUM = '" . $ce_num . "'";
$ce_result = $database_connection->query($ce_query);
if ($ce_result->num_rows == 0) {
    echo "Course Enrollment Number does not exists.";
} else {
    // Exectue query to update classes table
    $update_query = "DELETE FROM class_enrollment WHERE CE_NUM = '" . $ce_num . "'";
    $update_result = $database_connection->query($update_query);
    if ($update_result == FALSE) {
        echo "Error occured deleting data in table.";
    } else {
        echo "Data Successfully Updated.";
    }
}
?>
