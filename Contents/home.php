<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Error: Login First"; // Store error message in session
    header("Location: login-ad-use.php"); // Redirect to login page
    exit();
}

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
    <title>Dashboard</title>
</head>

<body>
    <div class="logo-left">
        <img src="Logo.png" alt="Siglatrolap">
    </div>
    <nav class="navbar">
        <?php
        // Check the role stored in the session
        if ($_SESSION['role'] == 'employee') {
        ?>
            <!-- Employee Navigation -->
            <ul>
                <li><a href="home.php">HOME</a></li>
                <li><a href="attendance.php">ATTENDANCE</a></li>
                <li><a href="payroll.php">PAYSLIP</a></li>
                <li><a href="logout.php">LOGOUT</a></li>
                <li><a href="profile.php"><img src=""></a></li>
            </ul>
        <?php } else { ?>
            <!-- Admin Navigation -->
            <ul>
                <li><a href="home.php">HOME</a></li>
                <li><a href="records.php">RECORDS</a></li>
                <li><a href="payroll.php">PAYSLIP</a></li>
                <li><a href="logout.php">LOGOUT</a></li>
                <li><a href="profile.php"><img src=""></a></li>
            </ul>
        <?php } ?>
    </nav>

    <div class="land-text">
        <p>HI <span class="one-name">
                <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>
            </span>!, WELCOME TO SIGLATROLAP</p>
        <h1>THE SPARKS THAT<span class="solo-one">&nbsp;TRANSFORMS</span> IDEAS INTO <span class="solo-two">REALITY</span></h1>
        <button class="land-page-btn">
            <a href="#">LEARN MORE</a>
        </button>
    </div>
</body>

</html>

