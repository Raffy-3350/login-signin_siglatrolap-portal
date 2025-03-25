<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "siglatrolap_db") or die("Couldn't Connect");

// Function to confirm password
function confirmPassword($password, $confirmPassword) {
    return $password === $confirmPassword;
}

// Display error message if exists
if (isset($_SESSION['error'])) {
    echo '<p style="color: red; text-align: center; font-weight: bold;">' . htmlspecialchars($_SESSION['error']) . '</p>';
    unset($_SESSION['error']); // Clear after displaying
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // ✅ Validate empty fields
    if (empty($username) || empty($password) || empty($confirmPassword)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: login-ad-use.php"); // Redirect back to login page
        exit();
    }

    // ✅ Check if passwords match
    if (!confirmPassword($password, $confirmPassword)) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: login-ad-use.php"); // Redirect back to login page
        exit();
    }

    // ✅ Check user in database
    $stmt = $con->prepare("SELECT id, full_name, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $full_name, $hashed_password, $role);
        $stmt->fetch();

        // ✅ Verify password
        if (password_verify($password, $hashed_password)) {
            // ✅ Set Session Variables
            $_SESSION["user_id"] = $id;
            $_SESSION["full_name"] = $full_name;
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $role; // Store the role

            // ✅ Redirect to dashboard after login
            header("Location: home.php");
            exit();
        } else {
            $_SESSION['error'] = "Incorrect password!";
        }
    } else {
        $_SESSION['error'] = "User not found!";
    }

    $stmt->close();
    $con->close();
    header("Location: login-ad-use.php"); // Redirect back to login page
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siglatrolap Innovation Login</title>
    <link rel="stylesheet" href="styled.css">
    <link rel="shortcut icon" href="Icon.png" type="image/x-icon">
</head>
<body>
    <div class="logo-section">
        <img src="Logo.png" alt="">
    </div>

    <section class="form-login">
        <form action="login-ad-use.php" method="post">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter Username..." autocomplete="off" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter Password..." autocomplete="off" required>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="password" name="confirm_password" placeholder="Enter Password..." autocomplete="off" required>
                <a href="forgot.php" class="forgot-password">Forgot password?</a>
            </div>

            <div class="form-button">
                <button type="submit" name="submit" value="Login">Log In</button>
                <br><br>
                <hr><br>
                <button type="button">Connect to Gmail</button>
                <p>Not Registered? <a href="signup-ad-use.php">&nbsp;Create Account</a></p>
            </div>
        </form>
    </section>
</body>
</html>
