<?php
// Include your database connection code here
$host = "localhost";  // Change this to your database host
$user = "root";       // Change this to your database username
$password = "";       // Change this to your database password
$database = "csce310_team14";  // Change this to your database name

$conn = new mysqli($host, $user, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["UIN"])) {
    // Retrieve form data
        $UIN = $_POST["UIN"];
        // Retrieve other form fields and set them accordingly

        // Insert into the "user" table
        $stmtUser = $conn->prepare("INSERT INTO user (UIN, First_Name, M_Initial, Last_Name, Username, Passwords, User_Type, Email, Discord_Name, access) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        // Bind parameters and execute
        $stmtUser->bind_param("issssssssi", $UIN, $_POST["First_Name"], $_POST["M_Initial"], $_POST["Last_Name"], $_POST["Username"], $_POST["Passwords"], $_POST["User_Type"], $_POST["Email"], $_POST["Discord_Name"], $_POST["access"]);
        $stmtUser->execute();

        // Insert into the "college_student" table
        $stmtStudent = $conn->prepare("INSERT INTO college_student (UIN, Gender, Hispanic_Latino, Race, US_Citizen, First_Generation, DoB, GPA, Major, Minor_1, Minor_2, Expected_Graduation, School, Classification, Phone, Student_Type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        // Bind parameters and execute
        $stmtStudent->bind_param("isisiisdsssissis", $UIN, $_POST["Gender"], $_POST["Hispanic_Latino"], $_POST["Race"], $_POST["US_Citizen"], $_POST["First_Generation"], $_POST["DoB"], $_POST["GPA"], $_POST["Major"], $_POST["Minor_1"], $_POST["Minor_2"], $_POST["Expected_Graduation"], $_POST["School"], $_POST["Classification"], $_POST["Phone"], $_POST["Student_Type"]);
        $stmtStudent->execute();
        // Close statements and database connection
        $stmtUser->close();
        $stmtStudent->close();
        header("Location: index.php");
        exit(); // Ensure no further code is executed
    }
    else {
        // Handle the case where "uin" is not set in the form submission
        echo "UIN is required.";
    }
}
$conn->close();
?>