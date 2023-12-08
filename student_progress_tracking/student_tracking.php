<!-- Author: Caleb Williamson, UIN: 128009239 -->
<?php
// Verify that uin exists
$uin = "";
session_start();
$uin = $_SESSION["uin"];
if(!isset($uin)) {
    header("Location: index.php");
    die("UIN was not found.");
}
// Verify that user is a student
$user_type = $_SESSION["userType"];
if(!isset($user_type)) {
    header("Location: index.php");
    die("You are not a student.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Tracking Page</title>
</head>

<body>
    <div>
        <div>
            <h1>
                Progress Tracking Page
            </h1>
        </div>
        <!-- Student Program Search -->
        <div>
            <h3>
                My Programs
            </h3>
            <?php
            if(isset($uin)) {
                try {
                    require "student_program_search.php";
                } catch (Exception $e) {
                    echo "Fatal Error occured when loading programs. Error Message: ".$e->getMessage()."Error Trace: ".$e->getTrace();
                }
            }
            ?>
        </div>
        <div>
            <!-- Classes Status Search -->
            <button><a href="class_tracking/class_tracking.php">Class Tracking</a></button>
            <!-- Certification Status Search -->
            <button><a href="certification_tracking/certification_tracking.php">Certification Tracking</a></button>
            <!-- Internship Status Search -->
            <button><a href="internship_tracking/internship_tracking.php">Internship Tracking</a></button>
        </div>
        <div>
            <button><a href="../student_landing.php">Go Back</a></button>
        </div>
    </div>
</body>

</html>