<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Retrieve the form data
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

  // Perform MySQL validation
  $servername = "localhost";
  $username = "root";
  $dbpassword = "";
  $dbname = "restauranttable.db";

  // Create a connection
  $conn = new mysqli($servername, $username, $dbpassword, $dbname);

  // Check the connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute a parameterized SQL query
  $stmt = $conn->prepare("SELECT * FROM register WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    // Valid credentials
    $row = $result->fetch_assoc();
    $email = $row['email'];
    $password = $row['password'];

    // Redirect to the home page or desired location upon successful login
    session_start();
    $_SESSION['email'] = $email; // Store email in session for later use if needed
    header("Location: homepage.php");
    exit();
  } else {
    // Invalid credentials
    header("Location: login.php"); // Redirect back to the login page
    exit();
  }
}

// Filter REMOTE_ADDR
$remoteAddr = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP);

// Filter HTTP_USER_AGENT
$userAgent = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT', FILTER_SANITIZE_STRING);
?>

<!-- Display the login form -->
<!DOCTYPE html>
<html>

<head>
  <title>Login</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <style>
    body {
      --color-primary: #009579;
      --color-primary-dark: #007f67;
      --color-secondary: #252c6a;
      --color-error: #cc3333;
      --color-success: #4bb544;
      --border-radius: 4px;
      margin: 0;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      background: url(./background.jpg);
      background-size: cover;
      font-family: 'Arial', sans-serif;
    }

    .container {
      max-width: 400px;
      margin: 0 auto;
      padding: 20px;
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: var(--border-radius);
      margin-top: 100px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .logo {
      text-align: center;
      margin-bottom: 20px;
    }

    .logo img {
      width: 150px;
      height: auto;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: var(--color-secondary);
    }

    form {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      color: var(--color-secondary);
      font-weight: bold;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: var(--border-radius);
      margin-bottom: 10px;
      color: var(--color-secondary);
    }

    input[type="submit"] {
      width: 100%;
      padding: 10px;
      background-color: var(--color-primary);
      color: #fff;
      border: none;
      border-radius: var(--border-radius);
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
      background-color: var(--color-primary-dark);
    }

    .text-center {
      text-align: center;
    }

    .alert {
      padding: 10px;
      margin-bottom: 10px;
      border-radius: var(--border-radius);
      color: #fff;
    }

    .alert-danger {
      background-color: var(--color-error);
    }

    .register-link {
      display: block;
      text-align: center;
      color: var(--color-secondary);
      margin-top: 10px;
    }

    .register-link a {
      color: var(--color-primary);
      font-weight: bold;
      text-decoration: none;
    }

    .register-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="logo">
      <img src="pictures/logo.png" alt="Logo" width="150">
    </div>
    <h2>Login</h2>
    <?php if (isset($errorMessage)): ?>
      <div class="alert alert-danger">
        <?php echo $errorMessage; ?>
      </div>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <label for="email">Email:</label>
      <input type="text" id="email" name="email" required>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>

      <input type="submit" value="Login">

      <p class="text-center register-link">Don't have an account? <a href="Register.php">Register</a></p>
    </form>
  </div>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>

</html>