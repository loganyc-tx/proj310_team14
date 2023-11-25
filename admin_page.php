<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION["username"]) || $_SESSION["userType"] !== "admin") {
    header("Location: index.php"); // Redirect to login if not logged in or not an admin
    echo "Redirected back to index.php";
    exit();
}

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

// Functions for Admin Functionalities
function insertAdmin($uin, $firstName, $middleInitial, $lastName, $username, $password, $userType, $email, $discordName) {
    // Implement your logic for inserting a new admin
    // Make sure to hash the password before storing it in the database
    // For example: $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    echo "Inserting admin...";
    global $conn; // Assuming $conn is your database connection object


    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO user (UIN, First_Name, M_Initial, Last_Name, Username, Passwords, User_Type, Email, Discord_Name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind parameters
    $stmt->bind_param("sssssssss", $uin, $firstName, $middleInitial, $lastName, $username, $password, $userType, $email, $discordName);

    // Execute the statement
    $stmt->execute();

    // Check if the insertion was successful
    if ($stmt->affected_rows > 0) {
        echo "Admin added successfully!";
    } else {
        echo "Error adding admin: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}
// Process Insert Action
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && $_POST["action"] === "insert") {
        $uin = $_POST["uin"];
        $firstName = $_POST["firstName"];
        $middleInitial = $_POST["middleInitial"];
        $lastName = $_POST["lastName"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $userType = $_POST["userType"];
        $email = $_POST["email"];
        $discordName = $_POST["discordName"];

        insertAdmin($uin, $firstName, $middleInitial, $lastName, $username, $password, $userType, $email, $discordName);
    }
}

function updateAdmin($uin, $firstName, $middleInitial, $lastName, $username, $password, $userType, $email, $discordName) {
    global $conn; // Assuming $conn is your database connection object

    // Hash the password before storing it in the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE user SET First_Name=?, M_Initial=?, Last_Name=?, Username=?, Passwords=?, User_Type=?, Email=?, Discord_Name=? WHERE UIN=?");

    // Bind parameters
    $stmt->bind_param("sssssssss", $firstName, $middleInitial, $lastName, $username, $hashedPassword, $userType, $email, $discordName, $uin);

    // Execute the statement
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        echo "User updated successfully!";
    } else {
        echo "Error updating admin: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Process Update Action
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && $_POST["action"] === "update") {
        $uinToUpdate = $_POST["updateUIN"];
        $firstNameToUpdate = $_POST["updateFirstName"];
        $middleInitialToUpdate = $_POST["updateMiddleInitial"];
        $lastNameToUpdate = $_POST["updateLastName"];
        $usernameToUpdate = $_POST["updateUsername"];
        $passwordToUpdate = $_POST["updatePassword"];
        $userTypeToUpdate = $_POST["updateUserType"];
        $emailToUpdate = $_POST["updateEmail"];
        $discordNameToUpdate = $_POST["updateDiscordName"];

        updateAdmin($uinToUpdate, $firstNameToUpdate, $middleInitialToUpdate, $lastNameToUpdate, $usernameToUpdate, $passwordToUpdate, $userTypeToUpdate, $emailToUpdate, $discordNameToUpdate);
    }
}

function selectAdmins() {
    global $conn; // Assuming $conn is your database connection object

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT UIN, First_Name, M_Initial, Last_Name, Username, Passwords, User_Type, Email, Discord_Name FROM user");

    // Execute the statement
    $stmt->execute();

    // Bind the result variables
    $stmt->bind_result($uin, $firstName, $middleInitial, $lastName, $username, $password, $userType, $email, $discordName);

    // Display the results
    echo "<h4>List of Users:</h4>";
    echo "<table border='1'>";
    echo "<tr><th>UIN</th><th>First Name</th><th>Middle Initial</th><th>Last Name</th><th>Username</th><th>Password</th><th>User Type</th><th>Email</th><th>Discord Name</th></tr>";

    while ($stmt->fetch()) {
        echo "<tr><td>$uin</td><td>$firstName</td><td>$middleInitial</td><td>$lastName</td><td>$username</td><td>$password</td><td>$userType</td><td>$email</td><td>$discordName</td></tr>";
    }

    echo "</table>";

    // Close the statement
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && $_POST["action"] === "select") {

            selectAdmins();
        }
}

function deleteAdmin($username, $fullDelete = false) {
    // Implement your logic for deleting an admin
    // If $fullDelete is true, delete the corresponding data; otherwise, just disable access
}


// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    
</head>
<body>
    <h2>Admin Functionalities</h2>

    <!-- Insert -->
    <h3>Add New Administrator</h3>
    <form action="admin_page.php" method="post">
        <input type="hidden" name="action" value="insert">

        <label for="uin">UIN:</label>
        <input type="text" id="uin" name="uin" required><br>

        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="firstName" required><br>

        <label for="middleInitial">Middle Initial:</label>
        <input type="text" id="middleInitial" name="middleInitial"><br>

        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="lastName" required><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="userType">User Type:</label>
        <input type="text" id="userType" name="userType" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="discordName">Discord Name:</label>
        <input type="text" id="discordName" name="discordName" required><br>

        <input type="submit" value="Add Administrator">
    </form>

    <!-- Other functionalities... -->
    <h3>Modify Existing User</h3>
    <form action="admin_page.php" method="post">
        <input type="hidden" name="action" value="update">

        <label for="updateUIN">UIN:</label>
        <input type="text" id="updateUIN" name="updateUIN" required><br>

        <label for="updateFirstName">First Name:</label>
        <input type="text" id="updateFirstName" name="updateFirstName" required><br>

        <label for="updateMiddleInitial">Middle Initial:</label>
        <input type="text" id="updateMiddleInitial" name="updateMiddleInitial"><br>

        <label for="updateLastName">Last Name:</label>
        <input type="text" id="updateLastName" name="updateLastName" required><br>

        <label for="updateUsername">Username:</label>
        <input type="text" id="updateUsername" name="updateUsername" required><br>

        <label for="updatePassword">New Password:</label>
        <input type="password" id="updatePassword" name="updatePassword"><br>

        <label for="updateUserType">New User Type:</label>
        <input type="text" id="updateUserType" name="updateUserType"><br>

        <label for="updateEmail">Email:</label>
        <input type="email" id="updateEmail" name="updateEmail" required><br>

        <label for="updateDiscordName">Discord Name:</label>
        <input type="text" id="updateDiscordName" name="updateDiscordName" required><br>

        <input type="submit" value="Update User">
    </form>

    <h4>List of Users</h4>
    <form action="admin_page.php" method="post">
    <input type="hidden" name="action" value="select">
    <input type="submit" value="View Users">
    </form>
</form>
</body>
</html>
