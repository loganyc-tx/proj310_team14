<!-- COMPLETED BY ROCK KANZARKAR -->
<?php
    // Retrieve the value of 'a' from the URL query parameters
    $nm = $_GET['a'];

    // Include the database connection file
    include_once 'dbh.inc.php';

    // Define the SQL query to delete the event tracking entry with the specified ET_Num
    $DeleteEventsQuery = "DELETE FROM event_tracking WHERE ET_Num = '$nm'";

    // Execute the delete query
    $result = $conn->query($DeleteEventsQuery);

    // Redirect to the 'signedupevents.php' page after the deletion
    header("location: signedupevents.php");
?>
