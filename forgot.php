<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "siglatrolap_db") or die("Couldn't Connect");
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Check if email exists
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        // Simulated reset link (no actual email sending)
        echo "A reset link has been sent!! click: <a href='reset.php?email=$email'>Reset Password</a>)";
    } else {
        echo "Email not found!";
    }
    
    $con->close();
}
?>

<form method="post">
    <input type="email" name="email" placeholder="Enter your email" required>
    <button type="submit">Send Reset Link</button>
</form>

