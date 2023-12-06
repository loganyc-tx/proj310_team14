<!-- Author: Caleb Williamson, UIN: 128009239 -->
<?php
// Retrieve input
$ia_num = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ia_num = filter_input_data($_POST["ia_num"]);
}

// Check if intended entry to does not exist
$ia_query = "SELECT IA_NUM FROM intern_app WHERE IA_NUM = '" . $ia_num . "'";
$ia_result = $database_connection->query($ia_query);
if ($ia_result->num_rows == 0) {
    echo "Internship Application Number does not exists.";
} else {
    // Exectue query to update classes table
    $update_query = "DELETE FROM intern_app WHERE IA_NUM = '" . $ia_num . "'";
    $update_result = $database_connection->query($update_query);
    if ($update_result == FALSE) {
        echo "Error occured deleting data in table.";
    } else {
        echo "Data Successfully Updated.";
    }
}
?>