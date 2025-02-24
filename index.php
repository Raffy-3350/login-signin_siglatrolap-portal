<?php
// Database connection
$con = mysqli_connect("localhost", "root", "", "siglatrolap_db") or die("Couldn't Connect");

session_start();

// Register
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : '';

    // Ensure no empty values
    if (empty($name) || empty($username) || empty($_POST['password'])) {
        die("Error: All fields are required!");
    }

    // Check if username already exists
    $stmt = $con->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Error: Username already taken!");
    }

    // Insert new user
    $stmt = $con->prepare("INSERT INTO users (name, username, password, email) VALUES (?, ?, ?, NULL)");
    $stmt->bind_param("sss", $name, $username, $password);

    try {
      if (!$stmt->execute()) {
          throw new Exception("Error: " . $stmt->error);
      }
      echo "Registration successful!";
  } catch (Exception $e) {
      echo $e->getMessage();
  }
  
    
    $con->close();
}
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
                        <input type="text" id="name" name="name" placeholder="Name..." autocomplete="off" required>
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" placeholder="Enter Username..." autocomplete="off" required>
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" placeholder="Enter Password..." autocomplete="off" required>
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
                        <p>Already Have An Account?<a href="login-ad-use.php">&nbsp; Log In</a></p>
                  </div>
            </form>
            </div>
      </section>
</body>

</html>