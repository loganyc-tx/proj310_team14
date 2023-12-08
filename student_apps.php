<?php
session_start();
######Logan Chen
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "csce310_team14";
if (isset($_SESSION["uin"])) {
    $UIN = $_SESSION["uin"];
    // Rest of your code
} else {
    die("UIN not set in the session");
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//handle form insertion

function addDoc($App_Num, $Doc_Type, $Link){
   global $conn;

    $getLastDocNumQuery = "SELECT MAX(Doc_Num) AS LastDocNum FROM document";
    $result = $conn->query($getLastDocNumQuery);
    $row = $result->fetch_assoc();
    $lastDoc_Num = $row['LastDocNum'];
    $newDocNum = $lastDoc_Num + 1;
    $insertDocumentQuery = "INSERT INTO document (Doc_Num, App_Num, Link, Doc_Type) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertDocumentQuery);

    $stmt->bind_param('iiss', $newDocNum, $App_Num, $Link, $Doc_Type);

    $stmt->execute();

    // Check if the insertion was successful
    if ($stmt->affected_rows > 0) {
        $conn->commit(); // Commit the transaction
        header("location: student_apps.php?uin=" . $UIN);
        exit();
    } else {
        echo '<script>
            alert("Error adding document: ' . $stmt->error . '");
            window.location.href = "student_apps.php";
        </script>';
        $conn->rollback(); // Rollback the transaction
    }
}

// Handle form submissions
function insertApplication($program_num, $uncom_cert = '', $com_cert = '', $purpose_statement = '', $Link, $Doc_Type) {
    global $conn;
    $UIN = $_SESSION["uin"];
    $stmt = $conn->prepare("INSERT INTO applications (Program_Num, UIN, Uncom_Cert, Com_Cert, Purpose_Statement) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iissi", $program_num, $UIN, $uncom_cert, $com_cert, $purpose_statement);
    
    if ($stmt->execute()) {
        $newAppNum = $conn->insert_id;
        echo "New record created successfully. Last inserted ID is: " . $newAppNum;
/////////////////////////////TODO LINK TO ADDDOC
        addDoc($newAppNum, $Doc_Type, $Link);
        echo "Application submitted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

function updateApplication($app_num, $uncom_cert='', $com_cert='', $purpose_statement='') {
    global $conn;

    $stmt = $conn->prepare("UPDATE applications SET Uncom_Cert=?, Com_Cert=?, Purpose_Statement=? WHERE app_num=?");
    $stmt->bind_param("sssi", $uncom_cert, $com_cert, $purpose_statement, $app_num);

    if ($stmt->execute()) {
        echo "Application updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

function selectApplication() {
    global $conn; // Assuming $conn is your database connection object
    $UIN = $_SESSION["uin"];
    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT Program_Name, Program_Description, Uncom_cert, Com_Cert, Purpose_Statement 
    FROM studentprogramapplicationinfo 
    WHERE Student_UIN=?
    ");
    $stmt->bind_param("i", $UIN);
    $stmt->execute();
    $stmt->bind_result($Program_Name, $Program_Description, $Uncom_cert, $Com_Cert, $Purpose_Statement);
    

    // Display the results
    echo "<h4>List of Apps:</h4>";
    echo "<table border='1'>";
    //student_UIN, program_num, program_name, program_Description, app_num, uncom_cert, com_cert, purpose_statement
    echo "<tr><th>Program Name</th><th>Description</th><th>uncom_cert</th><th>com_cert</th><th>purpose_statement</th>";

    while ($stmt->fetch()) {
        echo "<tr><td>$Program_Name</td><td>$Program_Description</td><td>$Uncom_cert</td><td>$Com_Cert</td><td>$Purpose_Statement</td>";
    }

    echo "</table>";

    // Close the statement
    $stmt->close();
}

function deleteApplication($app_num) {
    global $conn;

    $stmt = $conn->prepare("DELETE FROM applications WHERE app_num=?");
    $stmt->bind_param("i", $app_num);

    if ($stmt->execute()) {
        echo "Application deleted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$sql = "SELECT Program_num, name, Visible FROM Programs"; 
$result = $conn->query($sql);

$sql2 = "SELECT App_Num, Link FROM appandlink"; 
$result2 = $conn->query($sql2);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Call the insertApplication function with form data
    if (isset($_POST["action"]) && $_POST["action"] === "insert") {
        insertApplication($_POST['selectedProgramId'], $_POST['uncom_cert'], $_POST['com_cert'], $_POST['purpose_statement'], $_POST['Link'], $_POST['Doc_Type']);
    }
    
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && $_POST["action"] === "update") {
        updateApplication($_POST['app_num'], $_POST['uncom_cert'], $_POST['com_cert'], $_POST['purpose_statement']);
    }
    
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && $_POST["action"] === "view") {

        selectApplication();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && $_POST["action"] === "delete") {

        deleteApplication($_POST['app_num']);
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
    <title>App Page (Student)</title>
    
</head>
<body>
    <h2>Student Functionalities</h2>

    <!-- Insert -->
    <h3>Add New App</h3>
    <form action="student_apps.php" method="post">
        
        <input type="hidden" name="action" value="insert">

        <label for="programSelect">Select Program:</label>
        <select id="programSelect" name="selectedProgramId" required>
        <option value="" disabled selected>Please select a program</option>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    if($row['Visible'] == 1){
                        echo "<option value='" . $row['Program_num'] . "'>" . $row['name'] . "</option>";
                    }
                }
            } else {
                echo "<option value=''>No programs available</option>";
            }
            ?>
        </select><br>

        <a class="btn btn-primary" onclick="toggleForm()">Add application</a>
        <!-- Form initially hidden with inline style -->
        <label for="Link">link:</label>
        <input type="text" id="Link" name="Link" required><br>
        <label for="Doc_Type">Document Type:</label>
        <input type="text" id="Doc_Type" name="Doc_Type" required><br>
        <input type="text" name="uncom_cert" placeholder="List Uncompleted Certifications"><br>
        <input type="text" name="com_cert" placeholder="List Completed Certifications"><br>
        <textarea name="purpose_statement" placeholder="Purpose Statement"></textarea><br>
        <input type="submit" name="submit" value="Submit Application">
    </form>
    
   
  



    <!-- Other functionalities... -->
    <h3>Modify Existing App</h3>
        <form action="student_apps.php" method="post">
        <input type="hidden" name="action" value="update">
        <label for="appSelect">Select app from link:</label>
        <select id="appSelect" name="app_num" required>
        <option value="" disabled selected>Please select an app from link</option>
            <?php
            mysqli_data_seek($result2, 0); //reset to top of list
            if ($result2->num_rows > 0) {
                // Output data of each row
                while ($row = $result2->fetch_assoc()) {
                    echo "<option value='" . $row['App_Num'] . "'>" . $row['Link'] . "</option>";
                }
            } else {
                echo "<option value=''>No apps available</option>";
            }
            ?>
        </select><br>
        <input type="text" name="uncom_cert" placeholder="Update Uncompleted Certifications"><br>
        <input type="text" name="com_cert" placeholder="Update Completed Certification"><br>
        <textarea name="purpose_statement" placeholder="Update Purpose Statement"></textarea><br>
        <input type="submit" name="submit" value="Update Application">
    </form>


    <h3>Delete app</h3>
    <form action="student_apps.php" method="post">
    <input type="hidden" name="action" value="delete">
    <label for="appSelect">Select app:</label>
        <select id="appSelect" name="app_num" required>
        <option value="" disabled selected>Please select an app from link</option>
            <?php
            mysqli_data_seek($result2, 0); //reset to top of list
            if ($result2->num_rows > 0) {
                // Output data of each row
                while ($row = $result2->fetch_assoc()) {
                    echo "<option value='" . $row['App_Num'] . "'>" . $row['Link'] . "</option>";
                }
            } else {
                echo "<option value=''>No apps available</option>";
            }
            ?>
        </select><br>
        <input type="submit" name="submit" value="Delete Application">
    </form>
    

    <h4>List of Apps</h4>
    <form action="student_apps.php" method="post">
    <input type="hidden" name="action" value="view">
        <input type="submit" name="submit" value="View Your Applications">
    </form>
</form>
</body>
</html>
