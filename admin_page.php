<!-- COMPLETED BY SAM HIRVILAMPI -->
<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION["username"]) || $_SESSION["userType"] !== "admin") {
    http_response_code(401); // Unauthorized
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

// Function to insert user into the user table
function insertUser($UIN, $First_Name, $M_Initial, $Last_Name, $Username, $Password, $User_Type, $Email, $Discord_Name, $access)
{
    global $conn;
    $sql = "INSERT INTO user (UIN, First_Name, M_Initial, Last_Name, Username, Passwords, User_Type, Email, Discord_Name, access)
            VALUES ('$UIN', '$First_Name', '$M_Initial', '$Last_Name', '$Username', '$Password', '$User_Type', '$Email', '$Discord_Name', '$access')";
    return $conn->query($sql);
}

// Function to insert student into the college_student table
function insertStudent($UIN, $Gender, $Hispanic_Latino, $Race, $US_Citizen, $First_Generation, $DoB, $GPA, $Major, $Minor_1, $Minor_2, $Expected_Graduation, $School, $Classification, $Phone, $Student_Type)
{
    global $conn;
    $sql = "INSERT INTO college_student (UIN, Gender, Hispanic_Latino, Race, US_Citizen, First_Generation, DoB, GPA, Major, Minor_1, Minor_2, Expected_Graduation, School, Classification, Phone, Student_Type)
            VALUES ('$UIN', '$Gender', '$Hispanic_Latino', '$Race', '$US_Citizen', '$First_Generation', '$DoB', '$GPA', '$Major', '$Minor_1', '$Minor_2', '$Expected_Graduation', '$School', '$Classification', '$Phone', '$Student_Type')";
    return $conn->query($sql);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["insert_user"])) {
    $User_Type = $_POST["user_type"];
    $UIN = $_POST["UIN"];
    $First_Name = $_POST["First_Name"];
    $M_Initial = $_POST["M_Initial"];
    $Last_Name = $_POST["Last_Name"];
    $Username = $_POST["Username"];
    $Password = $_POST["Password"];
    $Email = $_POST["Email"];
    $Discord_Name = $_POST["Discord_Name"];
    $access = $_POST["access"];

    if ($User_Type === "admin") {
        // Insert admin into the user table
        if (insertUser($UIN, $First_Name, $M_Initial, $Last_Name, $Username, $Password, $User_Type, $Email, $Discord_Name, $access)) {
            echo "Admin user inserted successfully!";
        } else {
            echo "Error inserting admin user: " . $conn->error;
        }
    } elseif ($User_Type === "student") {
        // Additional fields for student
        $Gender = $_POST["Gender"];
        $Hispanic_Latino = $_POST["Hispanic_Latino"];
        $Race = $_POST["Race"];
        $US_Citizen = $_POST["US_Citizen"];
        $First_Generation = $_POST["First_Generation"];
        $DoB = $_POST["DoB"];
        $GPA = $_POST["GPA"];
        $Major = $_POST["Major"];
        $Minor_1 = $_POST["Minor_1"];
        $Minor_2 = $_POST["Minor_2"];
        $Expected_Graduation = $_POST["Expected_Graduation"];
        $School = $_POST["School"];
        $Classification = $_POST["Classification"];
        $Phone = $_POST["Phone"];
        $Student_Type = $_POST["Student_Type"];

        // Insert student into both user and college_student tables
        $conn->autocommit(FALSE); // Start a transaction
        $error = false;

        if (!insertUser($UIN, $First_Name, $M_Initial, $Last_Name, $Username, $Password, $User_Type, $Email, $Discord_Name, $access)) {
            $error = true;
            echo "Error inserting student user: " . $conn->error;
        }

        if (!insertStudent($UIN, $Gender, $Hispanic_Latino, $Race, $US_Citizen, $First_Generation, $DoB, $GPA, $Major, $Minor_1, $Minor_2, $Expected_Graduation, $School, $Classification, $Phone, $Student_Type)) {
            $error = true;
            echo "Error inserting student details: " . $conn->error;
        }

        if ($error) {
            $conn->rollback(); // Rollback the transaction if any error occurred
        } else {
            $conn->commit(); // Commit the transaction if both inserts were successful
            echo "Student user inserted successfully!";
        }

        $conn->autocommit(TRUE); // Turn on autocommit
    } else {
        echo "Invalid user type";
    }
}


function updateUser($UIN, $First_Name, $M_Initial, $Last_Name, $Username, $Password, $User_Type, $Email, $Discord_Name, $access)
{
    global $conn;

    // Initialize an array to store the fields to be updated
    $updateFields = array();

    // Check each field and add it to the array if it's not empty
    if (!empty($First_Name))
        $updateFields[] = "First_Name='$First_Name'";
    if (!empty($M_Initial))
        $updateFields[] = "M_Initial='$M_Initial'";
    if (!empty($Last_Name))
        $updateFields[] = "Last_Name='$Last_Name'";
    if (!empty($Username))
        $updateFields[] = "Username='$Username'";
    if (!empty($Password))
        $updateFields[] = "Passwords='$Password'";
    if (!empty($User_Type))
        $updateFields[] = "User_Type='$User_Type'";
    if (!empty($Email))
        $updateFields[] = "Email='$Email'";
    if (!empty($Discord_Name))
        $updateFields[] = "Discord_Name='$Discord_Name'";
    if (!empty($access))
        $updateFields[] = "access='$access'";

    // If there are fields to update, build the SQL query and execute it
    if (!empty($updateFields)) {
        $updateFieldsStr = implode(", ", $updateFields);
        $sql = "UPDATE user SET $updateFieldsStr WHERE UIN='$UIN'";

        // Execute the query
        if ($conn->query($sql)) {
            // Check if User_Type is changing from "student" to "admin"
            if ($User_Type === "admin") {
                // Delete the corresponding row in the college_student table
                $deleteStudentSql = "DELETE FROM college_student WHERE UIN='$UIN'";
                if (!$conn->query($deleteStudentSql)) {
                    die("Error deleting student details: " . $conn->error);
                }
            } elseif ($User_Type === "student") {
                // Check if the user was previously an admin
                $checkAdminSql = "SELECT User_Type FROM user WHERE UIN='$UIN'";
                $adminResult = $conn->query($checkAdminSql);
                
                if ($adminResult->num_rows == 0) {
                    // User was previously an admin, insert a new entry in the college_student table
                    $insertNewStudentSql = "INSERT INTO college_student (UIN) VALUES ('$UIN')";
                    if (!$conn->query($insertNewStudentSql)) {
                        die("Error inserting new student details: " . $conn->error);
                    }
                }
            }
            return true;
        } else {
            // Improve error handling
            die("Error updating user: " . $conn->error);
        }
    } else {
        // No fields to update
        return true;
    }
}



function updateStudent($UIN, $Gender, $Hispanic_Latino, $Race, $US_Citizen, $First_Generation, $DoB, $GPA, $Major, $Minor_1, $Minor_2, $Expected_Graduation, $School, $Classification, $Phone, $Student_Type)
{
    global $conn;

    // Check if the student already exists in the college_student table
    $existingStudentQuery = "SELECT * FROM college_student WHERE UIN = '$UIN'";
    $result = $conn->query($existingStudentQuery);

    // If the student doesn't exist, insert a new record
    if ($result->num_rows == 0) {
        $insertSql = "INSERT INTO college_student (UIN, Gender, Hispanic_Latino, Race, US_Citizen, First_Generation, DoB, GPA, Major, Minor_1, Minor_2, Expected_Graduation, School, Classification, Phone, Student_Type)
            VALUES ('$UIN', '$Gender', '$Hispanic_Latino', '$Race', '$US_Citizen', '$First_Generation', '$DoB', '$GPA', '$Major', '$Minor_1', '$Minor_2', '$Expected_Graduation', '$School', '$Classification', '$Phone', '$Student_Type')";

        if ($conn->query($insertSql)) {
            return true;
        } else {
            die("Error inserting student: " . $conn->error);
        }
    }

    // Initialize an array to store the fields to be updated
    $updateFields = array();

    // Check each field and add it to the array if it's not empty
    if (!empty($Gender))
        $updateFields[] = "Gender='$Gender'";
        if (!empty($Hispanic_Latino))
        $updateFields[] = "Hispanic_Latino='$Hispanic_Latino'";
    if (!empty($Race))
        $updateFields[] = "Race='$Race'";
    if (!empty($US_Citizen))
        $updateFields[] = "US_Citizen='$US_Citizen'";
    if (!empty($First_Generation))
        $updateFields[] = "First_Generation='$First_Generation'";
    if (!empty($DoB))
        $updateFields[] = "DoB='$DoB'";
    if (!empty($GPA))
        $updateFields[] = "GPA='$GPA'";
    if (!empty($Major))
        $updateFields[] = "Major='$Major'";
    if (!empty($Minor_1))
        $updateFields[] = "Minor_1='$Minor_1'";
    if (!empty($Minor_2))
        $updateFields[] = "Minor_2='$Minor_2'";
    if (!empty($Expected_Graduation))
        $updateFields[] = "Expected_Graduation='$Expected_Graduation'";
    if (!empty($School))
        $updateFields[] = "School='$School'";
    if (!empty($Classification))
        $updateFields[] = "Classification='$Classification'";
    if (!empty($Phone))
        $updateFields[] = "Phone='$Phone'";
    if (!empty($Student_Type))
        $updateFields[] = "Student_Type='$Student_Type'";

    // If there are fields to update, build the SQL query and execute it
    if (!empty($updateFields)) {
        $updateFieldsStr = implode(", ", $updateFields);
        $updateSql = "UPDATE college_student SET $updateFieldsStr WHERE UIN='$UIN'";

        // Execute the query
        if ($conn->query($updateSql)) {
            return true;
        } else {
            die("Error updating student: " . $conn->error);
        }
    } else {
        // No fields to update
        return true;
    }
}



// Check if the update form is submitted
// Check if the update form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_user"])) {
    $User_Type = $_POST["user_type_update"];
    $UIN = $_POST["UIN"];

    // Initialize variables for other fields
    $First_Name = $_POST["First_Name"] ?? '';
    $M_Initial = $_POST["M_Initial"] ?? '';
    $Last_Name = $_POST["Last_Name"] ?? '';
    $Username = $_POST["Username"] ?? '';
    $Password = $_POST["Password"] ?? '';
    $Email = $_POST["Email"] ?? '';
    $Discord_Name = $_POST["Discord_Name"] ?? '';
    $access = $_POST["access"] ?? '';
    // Additional fields for student
    $Gender = $_POST["Gender"] ?? '';
    $Hispanic_Latino = $_POST["Hispanic_Latino"] ?? '';
    $Race = $_POST["Race"] ?? '';
    $US_Citizen = $_POST["US_Citizen"] ?? '';
    $First_Generation = $_POST["First_Generation"] ?? '';
    $DoB = $_POST["DoB"] ?? '';
    $GPA = $_POST["GPA"] ?? '';
    $Major = $_POST["Major"] ?? '';
    $Minor_1 = $_POST["Minor_1"] ?? '';
    $Minor_2 = $_POST["Minor_2"] ?? '';
    $Expected_Graduation = $_POST["Expected_Graduation"] ?? '';
    $School = $_POST["School"] ?? '';
    $Classification = $_POST["Classification"] ?? '';
    $Phone = $_POST["Phone"] ?? '';
    $Student_Type = $_POST["Student_Type"] ?? '';

    if ($User_Type === "admin") {
        // Update admin in the user table
        if (updateUser($UIN, $First_Name, $M_Initial, $Last_Name, $Username, $Password, $User_Type, $Email, $Discord_Name, $access)) {
            echo "Admin user updated successfully!";
        } else {
            echo "Error updating admin user: " . $conn->error;
        }
    } elseif ($User_Type === "student") {
        // Update student in both user and college_student tables
        if (
            updateUser($UIN, $First_Name, $M_Initial, $Last_Name, $Username, $Password, $User_Type, $Email, $Discord_Name, $access)
            && updateStudent($UIN, $Gender, $Hispanic_Latino, $Race, $US_Citizen, $First_Generation, $DoB, $GPA, $Major, $Minor_1, $Minor_2, $Expected_Graduation, $School, $Classification, $Phone, $Student_Type)
        ) {
            echo "Student user updated successfully!";
        } else {
            echo "Error updating student user: " . $conn->error;
        }
    } else {
        echo "Invalid user type";
    }
}

// Check if the view form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["view_users"])) {
    $selectedUserType = $_POST["user_type_view"];

    echo "<h2>View Users</h2>";

    if ($selectedUserType === "admin") {
        // Display the 'user' table for admin users
        $result = $conn->query("SELECT * FROM user USE INDEX (admin_search) WHERE User_Type = 'admin'");
        displayAdminTable($result);
    } elseif ($selectedUserType === "student") {
        // Display the 'studenttable' view for student users
        $result = $conn->query("SELECT * FROM studenttable");
        displayStudentTable($result);
    } else {
        echo "Invalid user type selected.";
    }
}

// Function to display a table based on the query result
function displayAdminTable($result)
{
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>User Type</th><th>UIN</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Discord</th><th>Access</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["User_Type"] . "</td><td>" . $row["UIN"] . "</td><td>" . $row["First_Name"] . "</td><td>" . $row["Last_Name"] . "</td><td>" . $row["Email"] . "</td><td>" . $row["Discord_Name"] . "</td><td>" . $row["access"] . "</td></tr>";
        }

        echo "</table>";
    } else {
        echo "No results found.";
    }
}
// Function to display a table based on the query result// Function to display a table based on the query result
function displayStudentTable($result)
{
    global $conn; // Add this line to use the global connection object

    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>User Type</th><th>UIN</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Discord Name</th><th>Access</th><th>Gender</th><th>Hispanic/Latino</th><th>Race</th><th>US Citizen</th><th>First Generation</th><th>DoB</th><th>GPA</th><th>Major</th><th>Minor #1</th>
            <th>Minor #2</th><th>Expected Graduation</th><th>School</th><th>Classification</th><th>Phone</th><th>Student Type</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["User_Type"] . "</td><td>" . $row["UIN"] . "</td><td>" . $row["First_Name"] . "</td><td>" . $row["Last_Name"] . "</td><td>" . $row["Email"] . "</td><td>" . $row["Discord_Name"] . "</td><td>" . $row["access"] . "</td><td>" . $row["Gender"] . "</td><td>" . $row["Hispanic_Latino"] . "</td><td>" . $row["Race"] . "</td><td>"
                . $row["US_Citizen"] . "</td><td>" . $row["First_Generation"] . "</td><td>" . $row["DoB"] . "</td><td>" . $row["GPA"] . "</td><td>" . $row["Major"] . "</td><td>" . $row["Minor_1"] . "</td><td>" . $row["Minor_2"] . "</td><td>" . $row["Expected_Graduation"] . "</td><td>" . $row["School"] . "</td><td>" . $row["Classification"] . "</td><td>"
                . $row["Phone"] . "</td><td>" . $row["Student_Type"] . "</td></tr>";
        }

        echo "</table>";
    } else {
        echo "No results found.";
    }
}



// Function to delete user from both user and college_student tables
function deleteUser($UIN)
{
    global $conn;

    $conn->autocommit(FALSE); // Start a transaction
    $error = false;

    // Delete from user table
    $sqlUser = "DELETE FROM user WHERE UIN='$UIN'";
    if (!$conn->query($sqlUser)) {
        $error = true;
        echo "Error deleting user: " . $conn->error;
    }

    // Delete from college_student table
    $sqlStudent = "DELETE FROM college_student WHERE UIN='$UIN'";
    if (!$conn->query($sqlStudent)) {
        $error = true;
        echo "Error deleting student details: " . $conn->error;
    }

    if ($error) {
        $conn->rollback(); // Rollback the transaction if any error occurred
    } else {
        $conn->commit(); // Commit the transaction if both deletes were successful
        echo "User deleted successfully!";
    }

    $conn->autocommit(TRUE); // Turn on autocommit
}

// Check if the delete form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_user"])) {
    $User_Type = $_POST["user_type_delete"];
    $UIN = $_POST["UIN_delete"];

    if ($User_Type === "admin") {
        // Delete admin from the user table
        $sql = "DELETE FROM user WHERE UIN='$UIN'";
        if ($conn->query($sql)) {
            echo "Admin user deleted successfully!";
        } else {
            echo "Error deleting admin user: " . $conn->error;
        }
    } elseif ($User_Type === "student") {
        // Delete user from both user and college_student tables
        deleteUser($UIN);
    } else {
        echo "Invalid user type";
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
    <title>Admin Page</title>
</head>

<body>
    <h2>Admin Page</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="user_type">User Type:</label>
        <select name="user_type" id="user_type" required>
            <option value="admin">Admin</option>
            <option value="student">Student</option>
        </select><br>

        <label for="UIN">UIN:</label>
        <input type="text" name="UIN" required><br>

        <label for="First_Name">First Name:</label>
        <input type="text" name="First_Name" required><br>

        <label for="M_Initial">Middle Initial:</label>
        <input type="text" name="M_Initial"><br>

        <label for="Last_Name">Last Name:</label>
        <input type="text" name="Last_Name" required><br>

        <label for="Username">Username:</label>
        <input type="text" name="Username" required><br>

        <label for="Password">Password:</label>
        <input type="password" name="Password" required><br>

        <label for="Email">Email:</label>
        <input type="email" name="Email" required><br>

        <label for="Discord_Name">Discord Name:</label>
        <input type="text" name="Discord_Name"><br>

        <label for="access">Access:</label>
        <input type="text" name="access" required><br>

        <!-- Additional fields for student -->
        <div id="student-fields-insert" style="display: none;">
            <label for="Gender">Gender:</label>
            <input type="text" name="Gender"><br>

            <label for="Hispanic_Latino">Hispanic/Latino:</label>
            <input type="text" name="Hispanic_Latino"><br>

            <label for="Race">Race:</label>
            <input type="text" name="Race"><br>

            <label for="US_Citizen">US Citizen:</label>
            <input type="text" name="US_Citizen"><br>

            <label for="First_Generation">First Generation:</label>
            <input type="text" name="First_Generation"><br>

            <label for="DoB">Date of Birth:</label>
            <input type="date" name="DoB"><br>

            <label for="GPA">GPA:</label>
            <input type="text" name="GPA"><br>

            <label for="Major">Major:</label>
            <input type="text" name="Major"><br>

            <label for="Minor_1">Minor 1:</label>
            <input type="text" name="Minor_1"><br>

            <label for="Minor_2">Minor 2:</label>
            <input type="text" name="Minor_2"><br>

            <label for="Expected_Graduation">Expected Graduation:</label>
            <input type="text" name="Expected_Graduation"><br>

            <label for="School">School:</label>
            <input type="text" name="School"><br>

            <label for="Classification">Classification:</label>
            <input type="text" name="Classification"><br>

            <label for="Phone">Phone:</label>
            <input type="text" name="Phone"><br>

            <label for="Student_Type">Student Type:</label>
            <input type="text" name="Student_Type"><br>
        </div>
        <input type="hidden" name="insert_user" value="1">
        <input type="submit" value="Insert User">
    </form>


    <h2>Update User</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="user_type_update">User Type:</label>
        <select name="user_type_update" id="user_type_update" required>
            <option value="admin">Admin</option>
            <option value="student">Student</option>
        </select><br>

        <!-- Common Fields -->
        <label for="UIN">UIN:</label>
        <input type="text" name="UIN" required><br>

        <label for="First_Name">First Name:</label>
        <input type="text" name="First_Name"><br>

        <label for="M_Initial">Middle Initial:</label>
        <input type="text" name="M_Initial"><br>

        <label for="Last_Name">Last Name:</label>
        <input type="text" name="Last_Name"><br>

        <label for="Username">Username:</label>
        <input type="text" name="Username"><br>

        <label for="Password">Password:</label>
        <input type="password" name="Password"><br>

        <label for="Email">Email:</label>
        <input type="email" name="Email"><br>

        <label for="Discord_Name">Discord Name:</label>
        <input type="text" name="Discord_Name"><br>

        <label for="access">Access:</label>
        <input type="text" name="access"><br>

        <!-- Additional fields for student -->
        <div id="student-fields-update" style="display: none;">
            <label for="Gender">Gender:</label>
            <input type="text" name="Gender"><br>

            <label for="Hispanic_Latino">Hispanic/Latino:</label>
            <input type="text" name="Hispanic_Latino"><br>

            <label for="Race">Race:</label>
            <input type="text" name="Race"><br>

            <label for="US_Citizen">US Citizen:</label>
            <input type="text" name="US_Citizen"><br>

            <label for="First_Generation">First Generation:</label>
            <input type="text" name="First_Generation"><br>

            <label for="DoB">Date of Birth:</label>
            <input type="date" name="DoB"><br>

            <label for="GPA">GPA:</label>
            <input type="text" name="GPA"><br>

            <label for="Major">Major:</label>
            <input type="text" name="Major"><br>

            <label for="Minor_1">Minor 1:</label>
            <input type="text" name="Minor_1"><br>

            <label for="Minor_2">Minor 2:</label>
            <input type="text" name="Minor_2"><br>

            <label for="Expected_Graduation">Expected Graduation:</label>
            <input type="text" name="Expected_Graduation"><br>

            <label for="School">School:</label>
            <input type="text" name="School"><br>

            <label for="Classification">Classification:</label>
            <input type="text" name="Classification"><br>

            <label for="Phone">Phone:</label>
            <input type="text" name="Phone"><br>

            <label for="Student_Type">Student Type:</label>
            <input type="text" name="Student_Type"><br>
        </div>

        <input type="hidden" name="update_user" value="1">
        <input type="submit" value="Update User">
    </form>

    <h2>View Users</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="user_type_view">Select User Type:</label>
        <select name="user_type_view" id="user_type_view" required>
            <option value="admin">Admin</option>
            <option value="student">Student</option>
        </select><br>

        <input type="hidden" name="view_users" value="1">
        <input type="submit" value="View Users">
    </form>

    <h2>Delete User</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="user_type_delete">User Type:</label>
        <select name="user_type_delete" id="user_type_delete" required>
            <option value="admin">Admin</option>
            <option value="student">Student</option>
        </select><br>

        <label for="UIN_delete">UIN:</label>
        <input type="text" name="UIN_delete" required><br>

        <input type="hidden" name="delete_user" value="1">
        <input type="submit" value="Delete User">
    </form>

    <script>
        // Show/hide additional fields based on user type for insert form
        document.getElementById("user_type").addEventListener("change", function () {
            var studentFieldsInsert = document.getElementById("student-fields-insert");
            studentFieldsInsert.style.display = (this.value === "student") ? "block" : "none";
        });

        // Show/hide additional fields based on user type for update form
        document.getElementById("user_type_update").addEventListener("change", function () {
            var studentFieldsUpdate = document.getElementById("student-fields-update");
            studentFieldsUpdate.style.display = (this.value === "student") ? "block" : "none";
        });
    
    </script>
</body>

</html>