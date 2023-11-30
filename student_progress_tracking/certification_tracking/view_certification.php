<!-- Author: Caleb Williamson, UIN: 128009239 -->
<?php
// Execute SQL queries to create results table
$sql_query = "SELECT CertE_Num, Cert_ID, Status, Training_Status, Program_Num, Semester, Year FROM cert_enrollment WHERE UIN = '" . $uin . "'";
$query_result = $database_connection->query($sql_query);
if ($query_result->num_rows > 0) {
    echo display_cert_table_header();
    while ($cert_enroll_row = $query_result->fetch_assoc()) {
        // Exectue query to retrieve cert name and level
        $sql_cert_query = "SELECT Name, Level FROM certification WHERE Cert_ID = '" . $cert_enroll_row["Cert_ID"] . "'";
        $cert_query_result = $database_connection->query($sql_cert_query);
        $cert_row = $cert_query_result->fetch_assoc();
        $cert_name = $cert_row["Name"];
        $certs_level = $cert_row["Level"];
        // Execute query to retrieve user's name
        $sql_name_query = "SELECT First_Name, Last_Name FROM user WHERE UIN = '" . $uin . "'";
        $name_query_result = $database_connection->query($sql_name_query);
        $user_row = $name_query_result->fetch_assoc();
        $full_name = $user_row["First_Name"] . " " . $user_row["Last_Name"];
        echo display_cert_table_row($cert_enroll_row["CertE_Num"], $full_name, $cert_enroll_row["Cert_ID"], $cert_name, $certs_level, $cert_enroll_row["Program_Num"], $cert_enroll_row["Semester"], $cert_enroll_row["Year"], $cert_enroll_row["Status"], $cert_enroll_row["Training_Status"]);
    }
    echo display_cert_table_footer();
} else {
    echo "No results found for UIN: " . $uin . ". Try again.";
}
?>