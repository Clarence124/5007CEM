<?php
// Database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restauranttable.db";

// Create a new MySQLi instance
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set a flag to track validation errors
$validationError = false;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted form data
    $firstName = $_POST["inputFirstName"];
    $lastName = $_POST["inputLastName"];
    $email = $_POST["inputEmailAddress"];
    $password = $_POST["inputPassword"];
    $confirmPassword = $_POST["inputConfPassword"];
    $profilePicture = $_FILES["profilePicture"];

    // Perform data validation
    if ($firstName == "" || $lastName == "" || $email == "" || $password == "" || $confirmPassword == "") {
        echo "<script>alert('All fields are required.');</script>";
    } elseif (!ctype_alpha($firstName)) {
        echo "<script>alert('First name should only contain alphabetic characters.');</script>";
    } elseif (!ctype_alpha($lastName)) {
        echo "<script>alert('Last name should only contain alphabetic characters.');</script>";
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email address.');</script>";
    } elseif (strlen($password) < 6) {
        echo "<script>alert('Password should be at least 6 characters long.');</script>";
    } elseif ($confirmPassword !== $password) {
        echo "<script>alert('Confirm password does not match the password.');</script>";
    } else {
        // Validation successful, proceed with saving changes or further processing
        // ...
    }

    // Check if the file was uploaded without errors
    if (isset($_FILES["profilePicture"]) && $_FILES["profilePicture"]["error"] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES["profilePicture"]["tmp_name"];
        $fileName = basename($_FILES["profilePicture"]["name"]); // Assign the file name

        // Perform additional validation on the uploaded file if required

        // Move the uploaded file to a desired location
        $destination = "uploads/" . $fileName;
        if (move_uploaded_file($tmpName, $destination)) {
            // File moved successfully, update the profile picture path in the database
            $updateQuery = "UPDATE profile SET profile_picture = '$destination' WHERE email = '$email'";
            if ($conn->query($updateQuery) === TRUE) {
                echo "<script>alert('Profile picture uploaded successfully!');</script>";
            } else {
                echo "<script>alert('Error updating profile picture: " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('Error uploading profile picture.');</script>";
        }
    } elseif ($_FILES["profilePicture"]["error"] !== UPLOAD_ERR_NO_FILE) {
        echo "<script>alert('File upload error: " . $_FILES["profilePicture"]["error"] . "');</script>";
    }



    // Check if the first name exists in the "register" table
    $selectQuery = "SELECT * FROM register WHERE fname = '$firstName'";
    $result = $conn->query($selectQuery);

    if ($result->num_rows === 0) {
        echo "<script>alert('First name does not exist in the register.');</script>";
    } else {
        // Retrieve the existing user information from the "register" table
        $row = $result->fetch_assoc();
        $existingFirstName = $row['fname'];
        $existingLastName = $row['lname'];
        $existingEmail = $row['email'];
        $existingPassword = $row['password'];
        $existingconfirmPassword = $row['re_password'];

        // Check if the profile information matches the existing user information
        if (
            $firstName !== $existingFirstName ||
            $lastName !== $existingLastName ||
            $email !== $existingEmail ||
            $password !== $existingPassword ||
            $confirmPassword !== $existingconfirmPassword
        ) {
            echo "<script>alert('Profile information does not match the register information.');</script>";
        } else {
            // Update the profile information in the "register" table
            $updateQuery = "UPDATE register SET 
            fname = '$firstName',
            lname = '$lastName',
            password = '$password',
            re_password ='$confirmPassword'
            WHERE email = '$email'";

            if ($conn->query($updateQuery) === TRUE) {
                echo "<script>alert('Profile information updated successfully!');</script>";
            } else {
                echo "<script>alert('Error updating profile information: " . $conn->error . "');</script>";
            }
        }
    }
}

// Close the database connection
$conn->close();
?>

<html>

<head>
    <link rel="stylesheet" href="css/profile.css" type="text/css">
    <title>Profile</title>
</head>

<body>
    <div class="homepage">
        <div class="header">
            <div class="header-left"><img src="pictures/logo.png" width="300" height="150" alt="Logo"></div>
            <div class="header-right">
                <ul>
                    <li><a href="homepage.php">Homepage</a></li>
                    <li><a href="reservation.php">Reservation</a></li>
                    <li><a href="menu.php">Menu</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="Login.php">Log out</a></li>
                </ul>
            </div>
            <div class="header-bottom">
                <div class="header-font">Eat healthy food &amp; Enjoy your life.</div>
                <div class="p50_0" align="center">
                    <a href="reservation.php" class="header-btns">BOOK A TABLE</a>&nbsp; &nbsp; &nbsp; &nbsp;</a>
                </div>
            </div>
        </div>

        <div class="card-header">Account Details</div>
        <div class="card-body">
            <form method="POST" action="profile.php" enctype="multipart/form-data">
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label class="mb-1" for="inputFirstName">First name</label>
                        <input class="form-control" id="inputFirstName" name="inputFirstName" type="text"
                            placeholder="Enter your first name" value="Valerie">
                    </div>

                    <div class="col-md-6">
                        <label class="mb-1" for="inputLastName">Last name</label>
                        <input class="form-control" id="inputLastName" name="inputLastName" type="text"
                            placeholder="Enter your last name">
                    </div>
                </div>

                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label class="mb-1" for="inputEmailAddress">Email address</label>
                        <input class="form-control" id="inputEmailAddress" name="inputEmailAddress" type="email"
                            placeholder="Enter your email address">
                    </div>

                    <div class="col-md-6">
                        <label class="mb-1" for="profilePicture">Profile Picture</label>
                        <input class="form-control" type="file" name="profilePicture" id="profilePicture">
                    </div>

                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label class="mb-1" for="inputPassword">Password</label>
                        <input class="form-control" id="inputPassword" name="inputPassword" type="password"
                            placeholder="Enter password">
                    </div>

                    <div class="col-md-6">
                        <label class="mb-1" for="inputConfPassword">Confirm password</label>
                        <input class="form-control" id="inputConfPassword" name="inputConfPassword" type="password"
                            placeholder="Reenter your password">
                    </div>
                </div>

                <button class="btn btn-primary" type="submit" name="saveChanges">Save changes</button>
            </form>
        </div>
        <footer>
            <p>&copy; 2023 Restaurant Name. All rights reserved.</p>
        </footer>
    </div>

</body>