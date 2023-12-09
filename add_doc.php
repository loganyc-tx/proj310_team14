<!-- COMPLETED BY ROCK KANZARKAR -->
<?php
// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Start a session
session_start();

// Check if the user is logged in and is a student
if (!isset($_SESSION["username"]) || $_SESSION["userType"] !== "student") {
    http_response_code(401); // Unauthorized
    exit();
}

// Retrieve student information using the stored UIN in the session
if (isset($_SESSION["uin"])) {
    $uin = $_SESSION["uin"];
    // Rest of your code
} else {
    die("UIN not set in the session");
}

// Check if the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve values from the POST data
    $App_Num = $_POST['App_Num'];
    $Doc_Type = $_POST['Doc_Type'];
    $Link = $_POST['Link'];

    try {
        // Establish a connection to the database
        $host = "localhost";
        $user = "root";
        $password = "";
        $database = "csce310_team14";

        $conn = new mysqli($host, $user, $password, $database);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if the student has an existing application for the specified App_Num
        $checkExistingAppQuery = "SELECT COUNT(*) AS NumApps FROM applications WHERE UIN = ? AND App_Num = ?";
        $stmtAppCheck = $conn->prepare($checkExistingAppQuery);
        $stmtAppCheck->bind_param('ii', $uin, $App_Num); // Assuming UIN and App_Num are both integers, adjust types if necessary
        $stmtAppCheck->execute();
        $resultAppCheck = $stmtAppCheck->get_result();
        $rowAppCheck = $resultAppCheck->fetch_assoc();

        if ($rowAppCheck['NumApps'] == 0) {
            // User does not have an existing application for the specified App_Num
            echo '<script>
                alert("You do not have an existing application for the specified App_Num. Please submit an application first.");
                window.location.href = "student_doc.php";
            </script>';
            $stmtAppCheck->close();
            $conn->close();
            exit();
        }

        // Get the last Doc_Num and add 1 to it
        $getLastDocNumQuery = "SELECT MAX(Doc_Num) AS LastDocNum FROM document";
        $result = $conn->query($getLastDocNumQuery);
        $row = $result->fetch_assoc();
        $lastDoc_Num = $row['LastDocNum'];
        $newDocNum = $lastDoc_Num + 1;

        // Use a transaction to ensure data consistency
        $conn->begin_transaction();

        // Use prepared statements to prevent SQL injection
        $insertDocumentQuery = "INSERT INTO document (Doc_Num, App_Num, Link, Doc_Type) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertDocumentQuery);

        $stmt->bind_param('iiss', $newDocNum, $App_Num, $Link, $Doc_Type);

        $stmt->execute();

        // Check if the insertion was successful
        if ($stmt->affected_rows > 0) {
            $conn->commit(); // Commit the transaction
            header("location: student_doc.php?uin=" . $uin);
            exit();
        } else {
            echo '<script>
                alert("Error adding document: ' . $stmt->error . '");
                window.location.href = "student_doc.php";
            </script>';
            $conn->rollback(); // Rollback the transaction
        }

        $stmt->close();
        $conn->close();
    } catch (PDOException $e) {
        // Log the error or show a user-friendly message
        echo '<script>alert("Error adding document: ' . $e->getMessage() . '");</script>';
        header("location: student_doc.php");
        exit();
    }
} else {
    // Redirect back to student_doc.php if accessed directly
    header("location: student_doc.php");
    exit();
}
?>



