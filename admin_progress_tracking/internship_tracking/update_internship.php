<!-- Author: Caleb Williamson, UIN: 128009239 -->
<?php
// Retrieve input uin number
$ia_num = "";
$uin = "";
$intern_id = "";
$status = "";
$year = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ia_num = filter_input_data($_POST["ia_num"]);
    $uin = filter_input_data($_POST["uin"]);
    $intern_id = filter_input_data($_POST["intern_id"]);
    $status = filter_input_data($_POST["status"]);
    $year = filter_input_data($_POST["year"]);
}

// Check if intended entry to update exists
$ia_query = "SELECT IA_NUM FROM intern_app WHERE IA_NUM = '" . $ia_num . "'";
$ia_result = $database_connection->query($ia_query);
if ($ia_result->num_rows == 0) {
    echo "Internship Number does not exists.";
} else {
    // Exectue query to update internship table
    $update_query = "UPDATE intern_app SET UIN = '" . $uin . "', Intern_ID = '" . $intern_id . "', Status = '" . $status . "', Year = '" . $year . "' WHERE IA_NUM = '" . $ia_num . "'";
    $update_result = $database_connection->query($update_query);
    if ($update_result == FALSE) {
        echo "Error occured updating data in table.";
    } else {
        echo "Data Successfully Updated.";
    }
}
?>