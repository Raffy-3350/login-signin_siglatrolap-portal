<?php
session_start();

if (!isset($_SESSION['user_id'])) {
      $_SESSION['error'] = "Error: Login First"; // Store error message in session
      header("Location: login-ad-use.php"); // Redirect to login page
      exit();
  }
?>
<?php
// Database connection
$con = new mysqli("localhost", "root", "", "siglatrolap_db");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
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
      <nav class="navbar">
            <ul>
                  <li><a href="home.php">HOME</a></li>
                  <li><a href="dashboard.php">DASHBOARD</a></li>
                  <li><a href="task.php">TASK</a></li>
                  <li><a href="#">MANAGE</a></li>
                 
            </ul>
      </nav>
</body>
</html>