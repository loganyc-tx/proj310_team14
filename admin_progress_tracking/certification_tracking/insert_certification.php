<!-- Author: Caleb Williamson, UIN: 128009239 -->
<?php
// Retrieve input
$uin = "";
$cert_id = "";
$program_num = "";
$semester = "";
$year = "";
$status = "";
$train_status = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uin = filter_input_data($_POST["uin"]);
    $cert_id = filter_input_data($_POST["cert_id"]);
    $program_num = filter_input_data($_POST["program_num"]);
    $semester = filter_input_data($_POST["semester"]);
    $year = filter_input_data($_POST["year"]);
    $status = filter_input_data($_POST["status"]);
    $train_status = filter_input_data($_POST["train_status"]);
}

// Check if user or certification does not exist
$user_query = "SELECT UIN FROM user WHERE UIN = '" . $uin . "'";
$user_result = $database_connection->query($user_query);
$cert_query = "SELECT Cert_ID FROM certification WHERE Cert_ID = '" . $cert_id . "'";
$cert_result = $database_connection->query($cert_query);
if ($user_result->num_rows == 0 or $cert_result->num_rows == 0) {
    echo "User or Certification does not exist.";
} else {
    // Check if new entry already exists
    $entry_query = "SELECT * FROM cert_enrollment WHERE UIN = '".$uin."' AND Cert_ID = '".$cert_id."' AND Program_Num = '".$program_num."' AND Semester = '".$semester."' AND Year = '".$year."' AND STATUS = '".$status."' AND Training_Status = '".$train_status."'";
    $entry_result = $database_connection->query($entry_query);
    if ($entry_result->num_rows >= 1) {
        echo "Duplicate Entry already exists.";
    } else {
        // Execute query to determine the next ce_num to assign to the new data entry
        $ce_query = "SELECT CertE_Num FROM cert_enrollment ORDER BY CertE_Num DESC LIMIT 1";
        $ce_result = $database_connection->query($ce_query);
        $row = $ce_result->fetch_assoc();
        $ce_num = $row["CertE_Num"] + 1;
        // Execute SQL queries to create results table
        $insert_query = "INSERT INTO cert_enrollment (CertE_Num, UIN, Cert_ID, Program_Num, Semester, Year, Status, Training_Status) Values ('" . $ce_num . "', '" . $uin . "', '" . $cert_id . "', '" . $program_num . "', '" . $semester . "', '" . $year . "', '".$status."', '".$train_status."')";
        $query_result = $database_connection->query($insert_query);
        if ($query_result == FALSE) {
            echo "Error occured inserting data into table.";
        } else {
            echo "Data Successfully Added.";
        }
    }
}
?>