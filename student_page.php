<!-- COMPLETED BY SAM HIRVILAMPI -->
<?php
session_start();

// Check if the user is logged in and is a student
if (!isset($_SESSION["username"]) || $_SESSION["userType"] !== "student") {
    header("Location: login.php"); // Redirect to login page if not logged in as a student
    exit();
}

// Database connection parameters
$host = "localhost"; 
$user = "root";
$password = "";
$database = "csce310_team14";

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve student information using the stored UIN in the session
if (isset($_SESSION["uin"])) {
    $uin = $_SESSION["uin"];
} else {
    die("UIN not set in the session");
}
// Query information from the user and college_student table
$result = $conn->query("SELECT * FROM user INNER JOIN college_student ON user.UIN = college_student.UIN WHERE user.UIN = '$uin'");

if ($result->num_rows > 0) {
    $studentInfo = $result->fetch_assoc();
} else {
    die("Error retrieving student information");
}

// Function to update student information
function updateStudentInfo($uin, $FirstName, $MiddleInitial, $LastName, $Username, $Password, $Email, $DiscordName, $Gender, $HispanicLatino, $Race, $USCitizen, $FirstGeneration, $DoB, $GPA, $Major, $Minor1, $Minor2, $ExpectedGraduation, $School, $Classification, $Phone, $StudentType)
{
    global $conn;

    // Check each field and add it to the array if it's not empty
    $updateFields = array();

    $updateFields[] = "First_Name='$FirstName'";
    $updateFields[] = "M_Initial='$MiddleInitial'";
    $updateFields[] = "Last_Name='$LastName'";
    $updateFields[] = "Username='$Username'";
    $updateFields[] = "Passwords='$Password'";
    $updateFields[] = "Email='$Email'";
    $updateFields[] = "Discord_Name='$DiscordName'";
    $updateFields[] = "Gender='$Gender'";
    $updateFields[] = "Hispanic_Latino='$HispanicLatino'";
    $updateFields[] = "Race='$Race'";
    $updateFields[] = "US_Citizen='$USCitizen'";
    $updateFields[] = "First_Generation='$FirstGeneration'";
    $updateFields[] = "DoB='$DoB'";
    $updateFields[] = "GPA='$GPA'";
    $updateFields[] = "Major='$Major'";
    $updateFields[] = "Minor_1='$Minor1'";
    $updateFields[] = "Minor_2='$Minor2'";
    $updateFields[] = "Expected_Graduation='$ExpectedGraduation'";
    $updateFields[] = "School='$School'";
    $updateFields[] = "Classification='$Classification'";
    $updateFields[] = "Phone='$Phone'";
    $updateFields[] = "Student_Type='$StudentType'";

    // If there are fields to update, build the SQL query and execute it
    if (!empty($updateFields)) {
        $updateFieldsStr = implode(", ", $updateFields);
        $sql = "UPDATE user INNER JOIN college_student ON user.UIN = college_student.UIN SET $updateFieldsStr WHERE user.UIN='$uin'";

        // Execute the query
        if ($conn->query($sql)) {
            return true;
        } else {
            // Improve error handling
            die("Error updating student information: " . $conn->error);
        }
    } else {
        // No fields to update
        return true;
    }
}

// Check if the update form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_student_info"])) {
    $FirstName = $_POST["FirstName"];
    $MiddleInitial = $_POST["MiddleInitial"];
    $LastName = $_POST["LastName"];
    $Username = $_POST["Username"];
    $Password = $_POST["Password"];
    $Email = $_POST["Email"];
    $DiscordName = $_POST["DiscordName"];
    $Gender = $_POST["Gender"];
    $HispanicLatino = $_POST["HispanicLatino"];
    $Race = $_POST["Race"];
    $USCitizen = $_POST["USCitizen"];
    $FirstGeneration = $_POST["FirstGeneration"];
    $DoB = $_POST["DoB"];
    $GPA = $_POST["GPA"];
    $Major = $_POST["Major"];
    $Minor1 = $_POST["Minor1"];
    $Minor2 = $_POST["Minor2"];
    $ExpectedGraduation = $_POST["ExpectedGraduation"];
    $School = $_POST["School"];
    $Classification = $_POST["Classification"];
    $Phone = $_POST["Phone"];
    $StudentType = $_POST["StudentType"];

    if (updateStudentInfo($uin, $FirstName, $MiddleInitial, $LastName, $Username, $Password, $Email, $DiscordName, $Gender, $HispanicLatino, $Race, $USCitizen, $FirstGeneration, $DoB, $GPA, $Major, $Minor1, $Minor2, $ExpectedGraduation, $School, $Classification, $Phone, $StudentType)) {
        echo "Student information updated successfully!";
        // Refresh studentInfo after update
        $result = $conn->query("SELECT * FROM user INNER JOIN college_student ON user.UIN = college_student.UIN WHERE user.UIN = '$uin'");
        if ($result->num_rows > 0) {
            $studentInfo = $result->fetch_assoc();
        } else {
            die("Error retrieving updated student information");
        }
    } else {
        echo "Error updating student information.";
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["deactivate_account"])) {
    $sql = "UPDATE user SET access = 0 WHERE UIN='$uin'";
    if ($conn->query($sql)) {
        // Deactivate successful, end the session and redirect
        session_destroy();
        header("Location: index.php");
        exit();
    } else {
        // Error handling for database update
        die("Error deactivating account: " . $conn->error);
    }
}

$conn->close();
?>
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information</title>

    <script>
        // Double-check that they really want to deactivate the account
        function confirmDeactivation() {
            var confirmDeactivate = confirm("Are you sure you want to deactivate your account?");
            if (confirmDeactivate) {
                document.getElementById("deactivateForm").submit();
            }
        }
    </script>
</head>

<body>

    <h2>Student Information</h2>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <!-- Display UIN READ ONLY -->
        <div>
            <label for="UIN">UIN:</label>
            <input type="text" id="UIN" name="UIN" value="<?php echo $studentInfo['UIN']; ?>" readonly>
        </div>

        <!-- Editable fields -->
        <div>
            <label for="FirstName">First Name:</label>
            <input type="text" id="FirstName" name="FirstName" value="<?php echo $studentInfo['First_Name']; ?>">
        </div>

        <div>
            <label for="MiddleInitial">Middle Initial:</label>
            <input type="text" id="MiddleInitial" name="MiddleInitial" value="<?php echo $studentInfo['M_Initial']; ?>">
        </div>

        <div>
            <label for="LastName">Last Name:</label>
            <input type="text" id="LastName" name="LastName" value="<?php echo $studentInfo['Last_Name']; ?>">
        </div>

        <div>
            <label for="Username">Username:</label>
            <input type="text" id="Username" name="Username" value="<?php echo $studentInfo['Username']; ?>">
        </div>

        <div>
            <label for="Password">Password:</label>
            <input type="password" id="Password" name="Password" value="<?php echo $studentInfo['Passwords']; ?>">
        </div>

        <div>
            <label for="Email">Email:</label>
            <input type="text" id="Email" name="Email" value="<?php echo $studentInfo['Email']; ?>">
        </div>

        <div>
            <label for="DiscordName">Discord Name:</label>
            <input type="text" id="DiscordName" name="DiscordName" value="<?php echo $studentInfo['Discord_Name']; ?>">
        </div>

        <div>
            <label for="Gender">Gender:</label>
            <input type="text" id="Gender" name="Gender" value="<?php echo $studentInfo['Gender']; ?>">
        </div>

        <div>
            <label for="HispanicLatino">Hispanic/Latino:</label>
            <input type="hidden" name="HispanicLatino" value="0">
            <input type="hidden" name="HispanicLatino" value="1">
            <select id="HispanicLatinoSelect" name="HispanicLatino">
                <option value="0" <?php echo $studentInfo['Hispanic_Latino'] == 0 ? 'selected' : ''; ?>>No</option>
                <option value="1" <?php echo $studentInfo['Hispanic_Latino'] == 1 ? 'selected' : ''; ?>>Yes</option>
            </select>
        </div>

        <div>
            <label for="Race">Race:</label>
            <input type="text" id="Race" name="Race" value="<?php echo $studentInfo['Race']; ?>">
        </div>
        <div>
            <label for="USCitizen">US Citizen:</label>
            <input type="hidden" name="USCitizen" value="0">
            <input type="hidden" name="USCitizen" value="1">
            <select id="USCitizenSelect" name="USCitizen">
                <option value="0" <?php echo $studentInfo['US_Citizen'] == 0 ? 'selected' : ''; ?>>No</option>
                <option value="1" <?php echo $studentInfo['US_Citizen'] == 1 ? 'selected' : ''; ?>>Yes</option>
            </select>
        </div>

        <div>
            <label for="FirstGeneration">First Generation:</label>
            <input type="hidden" name="FirstGeneration" value="0">
            <input type="hidden" name="FirstGeneration" value="1">
            <select id="FirstGenerationSelect" name="FirstGeneration">
                <option value="0" <?php echo $studentInfo['First_Generation'] == 0 ? 'selected' : ''; ?>>No</option>
                <option value="1" <?php echo $studentInfo['First_Generation'] == 1 ? 'selected' : ''; ?>>Yes</option>
            </select>
        </div>


        <div>
            <label for="DoB">Date of Birth:</label>
            <input type="date" id="DoB" name="DoB" value="<?php echo $studentInfo['DoB']; ?>">
        </div>

        <div>
            <label for="GPA">GPA:</label>
            <input type="text" id="GPA" name="GPA" value="<?php echo $studentInfo['GPA']; ?>">
        </div>

        <div>
            <label for="Major">Major:</label>
            <input type="text" id="Major" name="Major" value="<?php echo $studentInfo['Major']; ?>">
        </div>

        <div>
            <label for="Minor1">Minor 1:</label>
            <input type="text" id="Minor1" name="Minor1" value="<?php echo $studentInfo['Minor_1']; ?>">
        </div>

        <div>
            <label for="Minor2">Minor 2:</label>
            <input type="text" id="Minor2" name="Minor2" value="<?php echo $studentInfo['Minor_2']; ?>">
        </div>

        <div>
            <label for="ExpectedGraduation">Expected Graduation:</label>
            <input type="text" id="ExpectedGraduation" name="ExpectedGraduation"
                value="<?php echo $studentInfo['Expected_Graduation']; ?>">
        </div>

        <div>
            <label for="School">School:</label>
            <input type="text" id="School" name="School" value="<?php echo $studentInfo['School']; ?>">
        </div>

        <div>
            <label for="Classification">Classification:</label>
            <input type="text" id="Classification" name="Classification"
                value="<?php echo $studentInfo['Classification']; ?>">
        </div>

        <div>
            <label for="Phone">Phone:</label>
            <input type="text" id="Phone" name="Phone" value="<?php echo $studentInfo['Phone']; ?>">
        </div>

        <div>
            <label for="StudentType">Student Type:</label>
            <input type="text" id="StudentType" name="StudentType" value="<?php echo $studentInfo['Student_Type']; ?>">
        </div>

        <div>
            <input type="submit" name="update_student_info" value="Update Information">
        </div>
    </form>


    <!-- Deactivate Account Form -->
    <form id="deactivateForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="deactivate_account">
    </form>

    <!-- Deactivate Account Button -->
    <div>
        <input type="button" onclick="confirmDeactivation()" value="Deactivate Account">
    </div>
</body>

</html>