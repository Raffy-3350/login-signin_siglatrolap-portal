<?php
// Start session
session_start();

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
    <title>Siglatrolap Innovation Inc. PORTAL</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="Icon.png" type="image/x-icon">

    <style>
        body {
            background-color: black;
            color: white;
            font-family: Arial, sans-serif;
        }
        .username {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
<p>Hello, <span class="username"><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?></span>!</p>
</body>

</html>
