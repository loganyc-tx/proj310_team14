<?php
    $nm = $_GET['a'];

    include_once 'dbh.inc.php';

    $DeleteEventsQuery = "DELETE FROM event WHERE Event_ID = '$nm'";
    $result = $conn->query($DeleteEventsQuery);

    header("location: event_admin.php");
?>