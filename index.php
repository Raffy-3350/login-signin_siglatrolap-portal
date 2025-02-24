<?php
// Database connection
$con = new mysqli("localhost", "root", "", "siglatrolap");

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
        echo '<p style="color: red;">Password must be at least 8 characters long!</p>';
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Check if username already exists
        $stmt = $con->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            echo '<p style="color: red;">Username already taken!</p>';
            $stmt->close();
        } else {
            $stmt->close();

            // Check if email already exists
            $stmt = $con->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                echo '<p style="color: red;">Email already in use!</p>';
            } else {
                $stmt->close();

                // Insert new user
                $stmt = $con->prepare("INSERT INTO users (name, username, password, email) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $name, $username, $hashed_password, $email);

                if ($stmt->execute()) {
                    echo '<p style="color: green;">Registration successful! You can now login.</p>';
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
      <link rel="stylesheet" href="style.css">
      <link rel="shortcut icon" href="Icon.png" type="image/x-icon">
</head>

<body>
      <div class="logo-sections">
            <img src="Logo.png" alt="">
      </div>

      
      <!-- Form Sign in -->
      <section class="form-signup">
            <form action="index.php" method="POST">
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
                        <p>Already Have An Account?<a href="login.php">&nbsp; Log In Here</a></p>
                  </div>
            </form>
            </div>
      </section>
</body>

</html>