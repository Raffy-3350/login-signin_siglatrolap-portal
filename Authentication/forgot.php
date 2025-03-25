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
        echo "<p style='color: green; position: absolute; margin-left: 36%; margin-top: 15%;'>A reset link has been sent!! click: <a href='reset.php?email=$email'>Reset Password</a></p>";
    } else {
        echo "<p style='color: red; position: absolute; margin-left: 45%; margin-top: 15%;'>Email not found!</p>";
    }
    
    $con->close();
}
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
   

 <section class="form-signup">
 <form method="post">
    <input type="email" name="email" placeholder="Enter your email" required>
  <div class="signup-button"><button type="submit">Send Reset Link</button></form></div>
 </section>
</body>
</html>


