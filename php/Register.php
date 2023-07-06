<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the form data using filter_input()
    $fname = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
    $lname = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
    $reenter_password = filter_input(INPUT_POST, 'reenter_password', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $recaptcha_response = filter_input(INPUT_POST, 'g-recaptcha-response', FILTER_SANITIZE_STRING);

    // Perform validation
    $errors = [];

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    if (empty($fname)) {
        $errors[] = "First name is required.";
    }

    if (empty($lname)) {
        $errors[] = "Last name is required.";
    }

    if ($password !== $reenter_password) {
        $errors[] = "Password and re-entered password do not match.";
    }

    // Validate reCAPTCHA
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = '6Lf5_eomAAAAAEPPnG9Yt_oHVd580vwtVE0BdAUG';

    $recaptcha_data = [
        'secret' => $recaptcha_secret,
        'response' => $recaptcha_response
    ];

    $recaptcha_options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query($recaptcha_data)
        ]
    ];

    $recaptcha_context = stream_context_create($recaptcha_options);
    $recaptcha_result = file_get_contents($recaptcha_url, false, $recaptcha_context);
    $recaptcha_json = json_decode($recaptcha_result);

    if (!$recaptcha_json->success) {
        $errors[] = "reCAPTCHA verification failed.";
    }

    // Check if there are no errors
    if (empty($errors)) {
        // All validation passed

        // Perform MySQL validation
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "restauranttable.db";

        // Create a connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the INSERT statement
        $sql = "INSERT INTO register (email, fname, lname, password, re_password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $email, $fname, $lname, $password, $reenter_password);

        // Execute the statement
        if ($stmt->execute()) {
            // Registration successful, redirect to the login page
            header("Location: Login.php");
            exit();
        } else {
            // Registration failed, display an error message
            $errors[] = "Registration failed. Please try again.";
        }

        // Close the statement and database connection
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Registration</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/register.css" type="text/css">
</head>

<body>
    <div class="container">
        <h2>Registration</h2>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li>
                            <?php echo $error; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" class="form-control" id="firstName" name="firstName" required>
            </div>
            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" class="form-control" id="lastName" name="lastName" required>
            </div>
            <div class="form-group">
                <label for="reenterPassword">Re-enter Password:</label>
                <input type="password" class="form-control" id="reenterPassword" name="reenter_password" required>

            </div>
            <div class="form-group">
                <div class="g-recaptcha" data-sitekey="6Lf5_eomAAAAAMRGI7YALNJIBcf-x9XtnTBZDUm4"></div>
            </div>

            <input type="submit" value="Register" class="btn btn-primary">
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</body>

</html>