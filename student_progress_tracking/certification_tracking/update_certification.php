<!-- Author: Caleb Williamson, UIN: 128009239 -->
<?php
// Retrieve input
$ce_num = "";
$cert_id = "";
$program_num = "";
$semester = "";
$year = "";
$status = "";
$train_status = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ce_num = filter_input_data($_POST["ce_num"]);
    $cert_id = filter_input_data($_POST["cert_id"]);
    $program_num = filter_input_data($_POST["program_num"]);
    $semester = filter_input_data($_POST["semester"]);
    $year = filter_input_data($_POST["year"]);
    $status = filter_input_data($_POST["status"]);
    $train_status = filter_input_data($_POST["train_status"]);
}

// Check if intended entry to update exists
$ce_query = "SELECT CertE_Num FROM cert_enrollment WHERE CertE_Num = '" . $ce_num . "'";
$ce_result = $database_connection->query($ce_query);
if ($ce_result->num_rows == 0) {
    echo "Certification Enrollment Number does not exists.";
} else {
    // Exectue query to update classes table
    $update_query = "UPDATE cert_enrollment SET UIN = '" . $uin . "', Cert_ID = '" . $cert_id . "', Program_Num = '" . $program_num . "', Semester = '" . $semester . "', Year = '" . $year . "', Status = '".$status."', Training_Status = '".$train_status."' WHERE CertE_Num = '" . $ce_num . "'";
    $update_result = $database_connection->query($update_query);
    if ($update_result == FALSE) {
        echo "Error occured updating data in table.";
    } else {
        echo "Data Successfully Updated.";
    }
}
?>