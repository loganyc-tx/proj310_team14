<!-- COMPLETED BY ROCK KANZARKAR -->
<?php
    // Retrieve the value of 'a' from the URL query parameters
    $nm = $_GET['a'];

    // Include the database connection file
    include_once 'dbh.inc.php';

    // Define the SQL query to delete the event with the specified Event_ID
    $DeleteEventsQuery = "DELETE FROM event WHERE Event_ID = '$nm'";

    // Execute the delete query
    $result = $conn->query($DeleteEventsQuery);

    // Redirect to the 'event_admin.php' page after the deletion
    header("location: event_admin.php");
?>
