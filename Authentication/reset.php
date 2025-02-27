<?php
session_start();
$con = new mysqli("localhost", "root", "", "siglatrolap_db");

// Check for connection errors
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Handle password reset request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'], $_POST['old_password'], $_POST['new_password'])) {
        $email = $_POST['email'];
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        
        // Check if new password is at least 8 characters
        if (strlen($new_password) < 8) {
            echo '<p style="color: red; position: absolute; margin-left: 45.3%; margin-top: 29%;">Password must be at least 8 characters!</p>';
        } else {
            // Fetch the stored hashed password for this user
            $stmt = $con->prepare("SELECT password FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($hashed_password);
                $stmt->fetch();

                // Verify the old password
                if (password_verify($old_password, $hashed_password)) {
                    // Hash the new password before saving
                    $new_hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                    // Update the password in the database
                    $update_stmt = $con->prepare("UPDATE users SET password = ? WHERE email = ?");
                    $update_stmt->bind_param("ss", $new_hashed_password, $email);

                    if ($update_stmt->execute()) {
                        echo "<p style='color: green;'>Password updated successfully. <a href='login-ad-use.php'>Login</a></p>";
                    } else {
                        echo "<p style='color: red;'>Error updating password.</p>";
                    }

                    $update_stmt->close();
                } else {
                    echo "<p style='color: red;'>Incorrect old password!</p>";
                }
            } else {
                echo "<p style='color: red;'>Email not found!</p>";
            }

            $stmt->close();
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
      <link rel="stylesheet" href="styled.css">
      <link rel="shortcut icon" href="Icon.png" type="image/x-icon">
      <title></title>
</head>

<body>
      <div class="logo-left">
            <img src="Logo.png" alt="Siglatrolap">
      </div>
      <nav class="navbar">
            <ul>
                  <li><a href="home.php">HOME</a></li>
                  <li><a href="dashboard.php">DASHBOARD</a></li>
                  <li><a href="task.php">TASK</a></li>
                  <li><a href="#">MANAGE</a></li>
                  <li><a href="#"><img src="#" alt="profile"></a></li>
            </ul>
      </nav>

<!-- Password Reset Form -->
<section class="form-signup">
<form method="post">
    <input type="hidden" name="email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
    <input type="password" name="old_password" placeholder="Enter old password" required>
    <input type="password" name="new_password" placeholder="Enter new password " required>
 <div class="signup-button"><button type="submit">Reset Password</button></div>   
</form>
</section>
</body>
</html>
