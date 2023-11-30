<!-- Author: Caleb Williamson, UIN: 128009239 -->
<?php
// Execute SQL queries to create results table
$sql_query = "SELECT CE_NUM, Class_ID, Status, Semester, Year FROM class_enrollment WHERE UIN = '" . $uin . "'";
$query_result = $database_connection->query($sql_query);
if ($query_result->num_rows > 0) {
    echo display_class_table_header();
    while ($class_enroll_row = $query_result->fetch_assoc()) {
        // Exectue query to retrieve class name
        $sql_classname_query = "SELECT Name, Type FROM classes WHERE Class_ID = '" . $class_enroll_row["Class_ID"] . "'";
        $classname_query_result = $database_connection->query($sql_classname_query);
        $classess_row = $classname_query_result->fetch_assoc();
        $class_name = $classess_row["Name"];
        $class_type = $classess_row["Type"];
        // Execute query to retrieve user's name
        $sql_name_query = "SELECT First_Name, Last_Name FROM user WHERE UIN = '" . $uin . "'";
        $name_query_result = $database_connection->query($sql_name_query);
        $user_row = $name_query_result->fetch_assoc();
        $full_name = $user_row["First_Name"] . " " . $user_row["Last_Name"];
        echo display_class_table_row($class_enroll_row["CE_NUM"], $full_name, $class_enroll_row["Class_ID"], $class_name, $class_type, $class_enroll_row["Semester"], $class_enroll_row["Year"], $class_enroll_row["Status"]);
    }
    echo display_class_table_footer();
} else {
    echo "No results found for UIN: " . $uin . ". Try again.";
}
?>