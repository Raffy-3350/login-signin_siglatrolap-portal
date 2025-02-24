<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "siglatrolap") or die("Couldn't Connect");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        die("Error: All fields are required!");
    }

    // Check user in database
    $stmt = $con->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $username;
            header("Location: home.php"); // Redirect to dashboard
            exit();
        } else {
            echo '<p style="color: red;">"Error: Incorrect password!"</p>';
        }
    } else {
        echo "Error: User not found!";
    }

    $stmt->close();
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
    <div class="logo-section">
        <img src="Logo.png" alt="">
    </div>

    <section class="form-login">
        <form action="login.php" method="post">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter Username..." autocomplete="off" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter Password..." autocomplete="off" required>
                <a href="#" class="forgot-password">Forgot password?</a>
            </div>

            <div class="form-button">
                <button type="submit" name="submit" value="Login">Log In</button>
                <br><br>
                <hr><br>
                <button type="button">Connect to Gmail</button>
                <p>Not Registered? <a href="index.php">&nbsp;Create Account</a></p>
            </div>
        </form>
    </section>
</body>
</html>
