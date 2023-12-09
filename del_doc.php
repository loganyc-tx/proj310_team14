<!-- COMPLETED BY ROCK KANZARKAR -->
<?php
    // Retrieve the value of 'a' from the URL query parameters
    $nm = $_GET['a'];

    // Include the database connection file
    include_once 'dbh.inc.php';

    // Define the SQL query to delete the document record with the specified Doc_Num
    $DeleteEventsQuery = "DELETE FROM document WHERE Doc_Num = '$nm'";

    // Execute the delete query
    $result = $conn->query($DeleteEventsQuery);

    // Redirect to the 'student_doc.php' page after the deletion
    header("location: student_doc.php");
?>
