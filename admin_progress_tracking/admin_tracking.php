<!-- Author: Caleb Williamson, UIN: 128009239 -->
<?php
// Verify that user is an admin
session_start();
$user_type = $_SESSION["userType"];
if($user_type !== "admin") {
    header("Location: ../index.php");
    die("You are not an admin.");
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
                User Program Search
            </h3>
            <form action="admin_tracking.php" method="post">
                UIN: <input type="text" name="uin">
                <br></br>
                <input type="submit" value="Search">
            </form>
            <?php
            if(isset($_POST["uin"])) {
                if($_SERVER["REQUEST_METHOD"] == "POST") {
                    try {
                        require "student_program_search.php";
                    } catch (Exception $e) {
                        echo "Fatal Error occured when performing User Program Search. Error Message: ".$e->getMessage()."Error Trace: ".$e->getTrace();
                    }
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
            <!-- Delete Report -->
            <button><a href="delete_report/delete_report.php">Delete Report</a></button>
        </div>
    </div>
</body>

</html>