<?php
// Start the session
session_start();

// Check if the user is logged in and if the full_name session variable is set
if (!isset($_SESSION['full_name']) || empty($_SESSION['full_name'])) {
    die("Error: User is not logged in or full name is not set.");
}

// Fetch the full name from the session
$full_name = $_SESSION['full_name'];

// Database Connection
$con = new mysqli("localhost", "root", "", "siglatrolap_db");
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Handling Time In
if (isset($_POST['time_in'])) {
    $date = date('Y-m-d'); // Current date
    $check_in_time = date('H:i:s'); // Current time

    // Check if the user is already checked in for today
    $check_in_query = "SELECT * FROM users WHERE full_name = '$full_name' AND date = '$date' AND check_in_time IS NOT NULL";
    $check_in_result = $con->query($check_in_query);
    
    if ($check_in_result->num_rows > 0) {
        // User has already checked in today, so check if they are checking out
    
        // Step 2: Check if the user is trying to check out
        $check_out_result = $con->query("SELECT * FROM users WHERE full_name = '$full_name' AND date = '$date' AND check_out_time IS NOT NULL");
    
        if ($check_out_result->num_rows > 0) {
            echo "You have already checked out today!";
        } else {
            // User has not checked out yet, check if it's after 5:00 PM (time out logic)
            $check_out_time = '17:00:00'; // The expected check-out time
            $status = "On Time";
    
            if ($check_out_time > $check_in_time && $check_in_time <= '17:30:00') {
                // Check-out time is valid (before 5:30 PM)
                $status = "On Time";
            } else if ($check_out_time > $check_in_time && $check_out_time > '17:30:00') {
                // Check-out time logic: after 5:30 PM (overtime)
                $status = "Overtime";
                $update_overtime = "UPDATE user_time_records SET check_out_time = '$check_out_time', status = '$status' WHERE user_id = '$user_id' AND date = '$date'";
                $con->query($update_overtime);
                echo "You are checking out overtime!";
            }
        }
    } else {
        // No check-in record found, so allow check-in
        if ($check_in_time == '08:00:00') {
            // Time-in is exactly 8:00 AM
            $status = 'On Time';
        } else if ($check_in_time < '08:00:00') {
            // Time-in is before 8:00 AM (Under Time)
            $status = 'Under Time';
        } else if ($check_in_time > '08:00:00') {
            // If check-in is later than 8:00 AM, it is an invalid time
            echo "Check-in time must be exactly at 8:00 AM.";
            exit; // Exit the script as check-in is not valid
        }
    
        // Insert the check-in record into the database
        $insert_time_in = "INSERT INTO user_time_records (user_id, full_name, date, check_in_time, status)
                           VALUES ('$user_id', '$full_name', '$date', '$check_in_time', '$status')";
    
        if ($con->query($insert_time_in) === TRUE) {
            echo "Check-in recorded successfully!";
        } else {
            echo "Error: " . $con->error; // Output error message if query fails
        }
    }
    
}

// Handling Time Out
if (isset($_POST['time_out'])) {
    $date = date('Y-m-d'); // Current date
    $check_out_time = date('H:i:s'); // Current time

    // Check if the user has already checked out today
    $check_out_query = "SELECT * FROM users WHERE full_name = '$full_name' AND date = '$date' AND check_out_time IS NULL";
    $check_out_result = $con->query($check_out_query);

    if ($check_out_result->num_rows == 0) {
        echo "You haven't checked in today or already checked out!";
    } else {
        // Update check-out time
        $update_time_out = "UPDATE users SET check_out_time = '$check_out_time' WHERE full_name = '$full_name' AND date = '$date'";
        if ($con->query($update_time_out) === TRUE) {
            echo "Time Out recorded successfully!";
        } else {
            echo "Error: " . $con->error; // Output error message if query fails
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e9ecef;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            color: #333;
            font-size: 2rem;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .form-container {
            margin-bottom: 30px;
        }
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            font-size: 16px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            border: none;
            background-color: #007BFF;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .live-clock {
            font-size: 18px;
            margin-top: 20px;
            color: #444;
        }
        .footer {
            margin-top: 30px;
            color: #777;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Attendance Tracking</h2>


    <form method="POST" action="attendance.php">
    <div class="live-clock" id="liveClock"></div>

    <!-- Time In Button -->
    <button type="submit" name="time_in" id="time_in_btn">Time In</button>

    <!-- Time Out Button -->
    <button type="submit" name="time_out" id="time_out_btn">Time Out</button>

    <div class="footer">
        <p>Powered by Siglatrolap Inc.</p>
    </div>
</form>
   
    
</div>

<script>
    // Live clock functionality with AM/PM format
    function updateClock() {
        const now = new Date();
        let hours = now.getHours();
        let minutes = now.getMinutes();
        let seconds = now.getSeconds();
        const ampm = hours >= 12 ? 'PM' : 'AM';

        // Convert 24-hour format to 12-hour format
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        const timeString = `${hours}:${minutes}:${seconds} ${ampm}`;
        document.getElementById('liveClock').innerHTML = timeString;
    }

    // Update clock every second
    setInterval(updateClock, 1000);

    // Initial clock update
    updateClock();
</script>

</body>
</html>
