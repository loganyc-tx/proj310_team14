<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

    session_start();
    // Check if the user is logged in and is an admin
    if (!isset($_SESSION["username"]) || $_SESSION["userType"] !== "admin") {
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
    $Program_Num = $_POST['Program_Num'];
    $Start_Date = $_POST['Start_Date'];
    $Start_Time = $_POST['Start_Time'];
    $Location = $_POST['Location'];
    $End_Date = $_POST['End_Date'];
    $End_Time = $_POST['End_Time'];
    $Event_Type = $_POST['Event_Type'];

    try {
        require_once "dbh.inc.php";

        // Check if the specified Program_Num exists in the Programs table
        $checkProgramQuery = "SELECT * FROM programs WHERE Program_Num = ?";
        $checkProgramStmt = $conn->prepare($checkProgramQuery);
        $checkProgramStmt->bind_param('i', $Program_Num);
        $checkProgramStmt->execute();
        $checkProgramResult = $checkProgramStmt->get_result();

        if ($checkProgramResult->num_rows == 0) {
            // Program_Num does not exist in Programs table
            $checkProgramStmt->close();
            echo '<script>
                alert("Program Number does not exist!");
                window.location.href = "event_admin.php";
            </script>';
            exit(); // Exit the script
        }

        // Get the last Event_ID and add 1 to it
        $getLastEventIDQuery = "SELECT MAX(Event_ID) AS LastEventID FROM event";
        $result = $conn->query($getLastEventIDQuery);
        $row = $result->fetch_assoc();
        $lastEventID = $row['LastEventID'];
        $newEventID = $lastEventID + 1;

        // Use prepared statements to prevent SQL injection
        $insertEventQuery = "INSERT INTO event (Event_ID, UIN, Program_Num, Start_Date, Start_Time, Location, End_Date, End_Time, Event_Type)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($insertEventQuery);

        $stmt->bind_param('iiissssss', $newEventID, $UIN, $Program_Num, $Start_Date, $Start_Time, $Location, $End_Date, $End_Time, $Event_Type);

        $stmt->execute();

        $stmt->close();

        // Commit the transaction
        $conn->commit();
        
        // Redirect back to table.php
        header("location: event_admin.php");
        exit();
    } catch (PDOException $e) {
        echo '<script>
            alert("Failed to add event: ' . $e->getMessage() . '");
            window.location.href = "event_admin.php";
        </script>';
        exit(); // Exit the script
    }
} else {
    // Redirect back to table.php
    header("location: event_admin.php");
    exit();
}
?>

