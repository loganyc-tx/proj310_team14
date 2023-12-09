<!-- COMPLETED BY ROCK KANZARKAR -->
<?php
    // Retrieve the value of 'a' from the URL query parameters
    $nm = $_GET['a'];

    // Include the database connection file
    include_once 'dbh.inc.php';

    // Define the SQL query to delete the event tracking record with the specified ET_Num
    $DeleteEventsQuery = "DELETE FROM event_tracking WHERE ET_Num = '$nm'";

    // Execute the delete query
    $result = $conn->query($DeleteEventsQuery);

    // Redirect to the 'event_tracking_admin.php' page after the deletion
    header("location: event_tracking_admin.php");
?>
