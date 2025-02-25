<?php
// Database connection
$con = new mysqli("localhost", "root", "", "siglatrolap_db");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

session_start();

// Register
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validate input fields
    if (empty($name) || empty($email) || empty($username) || empty($password)) {
        echo '<p style="color: red;">All fields are required!</p>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<p style="color: red;">Invalid email format!</p>';
    } elseif (strlen($password) > 8) {
        echo '<p style="color: red; position: absolute; margin-left: 45.3%; margin-top: 29%;">Must at least 8 characters!</p>';
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Check if username already exists
        $stmt = $con->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            echo '<p style="color: red;position: absolute; margin-left: 49.6%; margin-top: 24%;">Username taken!</p>';
            $stmt->close();
        } else {
            $stmt->close();

            // Check if email already exists
            $stmt = $con->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                echo '<p style="color: red; position: absolute; margin-left: 47.8%; margin-top: 19%;">Email already in use!</p>';
            } else {
                $stmt->close();

                // Insert new user
                $stmt = $con->prepare("INSERT INTO users (name, username, password, email) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $name, $username, $hashed_password, $email);

                if ($stmt->execute()) {
                    echo '<p style="color: green; position: absolute; margin-left: 50.6%; margin-top: 18%;">Succseful Signin</p>';
                } else {
                    echo '<p style="color: red;">Error: ' . $stmt->error . '</p>';
                }
                $stmt->close();
            }
        }
    }
}

$con->close();
?>




<!DOCTYPE html>
<html lang="en">

<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Siglatrolap Innovation Login</title>
      <link rel="stylesheet" href="css/styled.css">
      <link rel="shortcut icon" href="Icon.png" type="image/x-icon">
</head>

<body>
      <div class="logo-sections">
            <img src="Logo.png" alt="">
      </div>

      
      <!-- Form Sign in -->
      <section class="form-signup">
            <form action="signup-ad-use.php" method="POST">
                  <div>
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" placeholder="Name..." autocomplete="off" >
                        <label for="username">Email:</label>
                        <input type="email" id="email" name="email" placeholder="Enter email..." autocomplete="off" >
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" placeholder="Enter Username..." autocomplete="off">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" placeholder="Enter Password..." autocomplete="off">
                  </div>
                  <div class="signup-button">
                        <button type="submit" name="register">
                         Sign Up
                        </button>
                        <br><br>
                        <hr>
                        <br>
                        <button type="submit">
                              Connect to Gmail
                        </button>
                        <p>&nbsp;Have An Account?<a href="login-ad-use.php">&nbsp;Log In Here</a></p>
                  </div>
            </form>
            </div>
      </section>
</body>

</html>