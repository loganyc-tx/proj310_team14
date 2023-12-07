<?php
    $nm = $_GET['a'];

    include_once 'dbh.inc.php';

    $DeleteEventsQuery = "DELETE FROM document WHERE Doc_Num = '$nm'";
    $result = $conn->query($DeleteEventsQuery);

    header("location: student_doc.php");
?>