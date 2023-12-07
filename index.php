<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <style>
        /* Styles for the popup form */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid #ccc;
            padding: 15px;
            background: #fff;
            z-index: 1000;
        }
    </style>
</head>

<body>
    <h2>Login</h2>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="Login">
    </form>

    <button onclick="openPopup()">Student Signup</button>
    <div id="signupPopup" class="popup">
        <h2>Student Signup</h2>
        <form action="process_signup.php" method="post">
            <!-- User table fields -->
            <label for="UIN">UIN:</label>
            <input type="text" id="UIN" name="UIN" required><br>

            <label for="First_Name">First Name:</label>
            <input type="text" id="First_Name" name="First_Name" required><br>

            <label for="M_Initial">Middle Initial:</label>
            <input type="text" id="M_Initial" name="M_Initial"><br>

            <label for="Last_Name">Last Name:</label>
            <input type="text" id="Last_Name" name="Last_Name" required><br>

            <label for="Username">Username:</label>
            <input type="text" id="Username" name="Username" required><br>

            <label for="Passwords">Password:</label>
            <input type="password" id="Passwords" name="Passwords" required><br>

            <input type="hidden" id="User_Type" name="User_Type" value="student">

            <label for="Email">Email:</label>
            <input type="email" id="Email" name="Email" required><br>

            <label for="Discord_Name">Discord Name:</label>
            <input type="text" id="Discord_Name" name="Discord_Name"><br>

            <input type="hidden" id="access" name="access" value="1">

            <!-- College Student table fields -->
            <label for="Gender">Gender:</label>
            <input type="text" id="Gender" name="Gender" required><br>

            <label for="Hispanic_Latino">Hispanic/Latino:</label>
            <input type="text" id="Hispanic_Latino" name="Hispanic_Latino"><br>

            <label for="Race">Race:</label>
            <input type="text" id="Race" name="Race"><br>

            <label for="US_Citizen">US Citizen:</label>
            <input type="text" id="US_Citizen" name="US_Citizen"><br>

            <label for="First_Generation">First Generation:</label>
            <input type="text" id="First_Generation" name="First_Generation"><br>

            <label for="DoB">Date of Birth:</label>
            <input type="date" id="DoB" name="DoB" required><br>

            <label for="GPA">GPA:</label>
            <input type="text" id="GPA" name="GPA"><br>

            <label for="Major">Major:</label>
            <input type="text" id="Major" name="Major"><br>

            <label for="Minor_1">Minor 1:</label>
            <input type="text" id="Minor_1" name="Minor_1"><br>

            <label for="Minor_2">Minor 2:</label>
            <input type="text" id="Minor_2" name="Minor_2"><br>

            <label for="Expected_Graduation">Expected Graduation:</label>
            <input type="text" id="Expected_Graduation" name="Expected_Graduation" required><br>

            <label for="School">School:</label>
            <input type="text" id="School" name="School" required><br>

            <label for="Classification">Classification:</label>
            <input type="text" id="Classification" name="Classification"><br>

            <label for="Phone">Phone:</label>
            <input type="text" id="Phone" name="Phone" required><br>

            <label for="Student_Type">Student Type:</label>
            <input type="text" id="Student_Type" name="Student_Type" required><br>

            <input type="submit" value="Submit">
        </form>
        <button onclick="closePopup()">Close</button>
    </div>

    <script>
        // Function to open the signup popup
        function openPopup() {
            document.getElementById("signupPopup").style.display = "block";
        }
        // Function to close the signup popup
        function closePopup() {
            document.getElementById("signupPopup").style.display = "none";
        }
    </script>
</body>

</html>