<?php
    $nm = $_GET['a'];

    include_once 'dbh.inc.php';

    $DeleteEventsQuery = "DELETE FROM event_tracking WHERE ET_Num = '$nm'";
    $result = $conn->query($DeleteEventsQuery);

    header("location: event_tracking_admin.php");
?>