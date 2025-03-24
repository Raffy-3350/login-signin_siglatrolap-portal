<?php
session_start();

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

// Assuming the user_id is stored in the session
$user_id = $_SESSION['user_id'];
$date = '2025-03-19'; // Replace with dynamic date (e.g., current date or user-selected date)

// Step 1: Query the database to get check-in and check-out times for the user on the specified date
$query = "SELECT check_in_time, check_out_time FROM users WHERE user_id = '$user_id' AND date = '$date'";
$result = $con->query($query);

// Step 2: Check if data exists for the user on that date
if ($result->num_rows > 0) {
    // Fetch check-in and check-out times
    $row = $result->fetch_assoc();
    $check_in_time = $row['check_in_time'];
    $check_out_time = $row['check_out_time'];

    // Define regular working hours and overtime rate
    $regular_end_time = '17:00:00'; // 5:00 PM
    $overtime_rate = 70;  // 70 units for every 30 minutes of overtime

    // Step 3: Convert check-in and check-out times to DateTime objects
    $check_in = new DateTime($check_in_time);
    $check_out = new DateTime($check_out_time);

    // Step 4: Calculate overtime pay if applicable
    $overtime_pay = 0; // Initialize overtime pay to 0

    // Check if overtime exists (work beyond 5:00 PM)
    if ($check_out > new DateTime($regular_end_time)) {
        // Calculate overtime duration (from 5:00 PM onward)
        $overtime_start = new DateTime($regular_end_time);
        $overtime_duration = $overtime_start->diff($check_out);

        // Convert overtime duration to minutes
        $overtime_minutes = $overtime_duration->h * 60 + $overtime_duration->i;

        // Calculate the number of 30-minute blocks of overtime
        $overtime_blocks = ceil($overtime_minutes / 30);

        // Calculate overtime pay
        $overtime_pay = $overtime_blocks * $overtime_rate;
    }
} else {
    $overtime_pay = 0; // No overtime data for this user and date
}

// Calculate basic salary (just an example, you may replace it with actual values from your database)
$basic_salary = 50000; // Example: basic salary (replace with your actual query or value)

// Step 5: Calculate other deductions or additions (example values)
$late_deduction = 300; // Example deduction for being late
$sss_deduction = 300;  // Example SSS deduction
$insurance_deduction = 300; // Example insurance deduction

// Calculate gross pay (basic salary + overtime pay - deductions)
$gross_pay = $basic_salary + $overtime_pay - $late_deduction - $sss_deduction - $insurance_deduction;

// Calculate net salary (gross pay - deductions)
$net_salary = $gross_pay - ($late_deduction + $sss_deduction + $insurance_deduction);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .payslip-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            width: 600px;
            margin: auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #e0e0e0;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 22px;
        }

        .employee-details {
            margin-bottom: 20px;
        }

        .employee-details p {
            margin: 5px 0;
        }

        .salary-breakdown {
            width: 100%;
            border-collapse: collapse;
        }

        .salary-breakdown th, .salary-breakdown td {
            border: 1px solid #e0e0e0;
            padding: 10px;
            text-align: left;
        }

        .salary-breakdown th {
            background-color: #f8f8f8;
        }

        .total {
            text-align: right;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="payslip-container">
    <div class="header">
        <h2>Employee Payslip</h2>
        <p>Month: February 2025</p>
    </div>

    <div class="employee-details">
        <p><strong>Employee Name:</strong> <?php echo isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'Guest'; ?></p>
        <p><strong>Employee ID:</strong> <?php echo $user_id; ?></p>
        <p><strong>Position:</strong> Web Developer</p>
        <p><strong>Date Issued:</strong> March 10, 2025</p>
    </div>

    <table class="salary-breakdown">
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount (₱)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Basic Salary</td>
                <td>₱<?php echo number_format($basic_salary, 2); ?></td>
            </tr>
            <tr>
                <td>Attendance</td>
                <td>₱<?php echo number_format($overtime_pay, 2); ?></td>
            </tr>
            <tr>
                <td>Overtime Pay</td>
                <td>₱<?php echo number_format($overtime_pay, 2); ?></td>
            </tr>
            <tr>
                <td>Late Deduction</td>
                <td>-₱<?php echo number_format($late_deduction, 2); ?></td>
            </tr>
            <tr>
                <td>SSS Deduction</td>
                <td>-₱<?php echo number_format($sss_deduction, 2); ?></td>
            </tr>
            <tr>
                <td>Insurance</td>
                <td>-₱<?php echo number_format($insurance_deduction, 2); ?></td>
            </tr>
            <tr>
                <td><strong>Gross Pay</strong></td>
                <td><strong>₱<?php echo number_format($gross_pay, 2); ?></strong></td>
            </tr>
            <tr>
                <td><strong>Total Deductions</strong></td>
                <td><strong>-₱<?php echo number_format($late_deduction + $sss_deduction + $insurance_deduction, 2); ?></strong></td>
            </tr>
            <tr>
                <td><strong>Net Salary</strong></td>
                <td><strong>₱<?php echo number_format($net_salary, 2); ?></strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>**This is a system-generated payslip and does not require a signature.**</p>
    </div>
</div>

</body>
</html>
