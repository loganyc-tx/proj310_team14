<!-- COMPLETED BY ROCK KANZARKAR -->
<?php
// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 'On');
    
// Check if the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve values from the POST data
    $UIN = $_POST['UIN'];
    $Event_ID = $_POST['Event_ID'];

    try {
        // Include the database connection file
        require_once "dbh.inc.php";

        // Check if the specified UIN exists in the user table
        $checkUserQuery = "SELECT * FROM user WHERE UIN = ?";
        $checkUserStmt = $conn->prepare($checkUserQuery);
        $checkUserStmt->bind_param('i', $UIN);
        $checkUserStmt->execute();
        $checkUserResult = $checkUserStmt->get_result();

        if ($checkUserResult->num_rows == 0) {
            // UIN does not exist in the user table
            $checkUserStmt->close();
            echo '<script>
                alert("Error adding to event: UIN does not exist!");
                window.location.href = "event_tracking_admin.php";
            </script>';
            exit();
        }

        // Check if the specified Event_ID exists in the event table
        $checkEventQuery = "SELECT * FROM event WHERE Event_ID = ?";
        $checkEventStmt = $conn->prepare($checkEventQuery);
        $checkEventStmt->bind_param('i', $Event_ID);
        $checkEventStmt->execute();
        $checkEventResult = $checkEventStmt->get_result();

        if ($checkEventResult->num_rows == 0) {
            // Event_ID does not exist in the event table
            $checkEventStmt->close();
            echo '<script>
                alert("Error adding event: Event_ID does not exist in the Events table!");
                window.location.href = "event_tracking_admin.php";
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
                window.location.href = "event_tracking_admin.php";
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
                window.location.href = "event_tracking_admin.php";
            </script>';
            $conn->commit(); // Commit the transaction
        } else {
            echo '<script>
                alert("Error adding event tracking: Something went wrong.");
                window.location.href = "event_tracking_admin.php";
            </script>';
            $conn->rollback(); // Rollback the transaction
        }

        $stmt->close();

        // Redirect back to event_tracking_admin.php
        header("location: event_tracking_admin.php");
        exit();
    } catch (PDOException $e) {
        // Log the error or show a user-friendly message
        die("Failed to add event: " . $e->getMessage());
    }
} else {
    // Redirect back to event_tracking_admin.php if the form is not submitted via POST
    header("location: event_tracking_admin.php");
    exit();
}
?>
