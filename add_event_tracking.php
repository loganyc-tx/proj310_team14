<!-- COMPLETED BY ROCK KANZARKAR -->
<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
    session_start();
    // Check if the user is logged in and is an admin
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $UIN = $uin;
    $Event_ID = $_POST['Event_ID'];

    try {
        require_once "dbh.inc.php";

        // Check if the specified Event_ID exists in the event table
        $checkEventQuery = "SELECT * FROM event WHERE Event_ID = ?";
        $checkEventStmt = $conn->prepare($checkEventQuery);
        $checkEventStmt->bind_param('i', $Event_ID);
        $checkEventStmt->execute();
        $checkEventResult = $checkEventStmt->get_result();

        if ($checkEventResult->num_rows == 0) {
            // Event_ID does not exist in the event table
            $checkProgramStmt->close();
                echo '<script>
                    alert("Error adding event: Event_ID does not exist in the Events table.!");
                    window.location.href = "student_event.php";
                </script>';
                exit();
        }

        // Check if the specified UIN and Event_ID are already signed up for the event
        $checkSignUpQuery = "SELECT * FROM event_tracking WHERE UIN = ? AND Event_ID = ?";
        $checkSignUpStmt = $conn->prepare($checkSignUpQuery);
        $checkSignUpStmt->bind_param('ii', $UIN, $Event_ID);
        $checkSignUpStmt->execute();
        $checkSignUpResult = $checkSignUpStmt->get_result();

        if ($checkSignUpResult->num_rows > 0) {
            // User is already signed up for the event
            echo '<script>
                    alert("Error adding event tracking: User is already signed up for the event.");
                    window.location.href = "student_event.php";
                </script>';
            exit();
        }

        // Get the last ET_Num and add 1 to it
        $getLastETNumQuery = "SELECT MAX(ET_Num) AS LastET_Num FROM event_tracking";
        $result = $conn->query($getLastETNumQuery);
        $row = $result->fetch_assoc();
        $lastET_Num = $row['LastET_Num'];
        $newETNum = $lastET_Num + 1;

        // Use a transaction to ensure data consistency
        $conn->begin_transaction();

        // Use prepared statements to prevent SQL injection
        $insertEventTrackingQuery = "INSERT INTO event_tracking (ET_Num, Event_ID, UIN) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertEventTrackingQuery);

        $stmt->bind_param('iii', $newETNum, $Event_ID, $UIN);

        $stmt->execute();

        // Check if the insertion was successful
        if ($stmt->affected_rows > 0) {
            echo '<script>
                    alert("Event tracking added successfully");
                    window.location.href = "student_event.php";
                    </script>';
            $conn->commit(); // Commit the transaction
        } else {
            echo '<script>
                    alert("Error adding event tracking: Something went wrong.");
                    window.location.href = "student_event.php";
                </script>';
            $conn->rollback(); // Rollback the transaction
        }

        $stmt->close();

        // Redirect back to student_event.php
        header("location: student_event.php");
        exit();
    } catch (PDOException $e) {
        // Log the error or show a user-friendly message
        die("Failed to add event: " . $e->getMessage());
    }
} else {
    // Redirect back to student_event.php
    header("location: student_event.php");
    exit();
}
?>
