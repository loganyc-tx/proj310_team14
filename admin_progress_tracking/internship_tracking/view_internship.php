<!-- Author: Caleb Williamson, UIN: 128009239 -->
<?php
// Retrieve input uin number
$uin = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uin = filter_input_data($_POST["uin"]);
}


// Execute SQL queries to create results table
$sql_query = "SELECT * FROM userinternships WHERE Student_UIN = '" . $uin . "'";
$query_result = $database_connection->query($sql_query);
if ($query_result->num_rows > 0) {
    echo display_internship_table_header();
    while ($internship_app_row = $query_result->fetch_assoc()) {
        echo display_internship_table_row($internship_app_row["Student_UIN"], $internship_app_row["Internship_ID"], $internship_app_row["Name"], $internship_app_row["Description"], $internship_app_row["Government_Internship"], $internship_app_row["Application_Number"], $internship_app_row["Application_Status"], $internship_app_row["Year_Applied"]);
    }
    echo display_internship_table_footer();
} else {
    echo "No results found for UIN: " . $uin . ". Try again.";
}
?>