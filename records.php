<?php
session_start();

// Database Connection
$con = new mysqli("localhost", "root", "", "siglatrolap_db");
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Fetch Employees
$employees = $con->query("SELECT DISTINCT employee_name FROM attendance");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .employee-list {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
        }
        .employee-list h3 {
            margin-bottom: 10px;
        }
        .employee-list a {
            display: block;
            padding: 10px;
            border-bottom: 1px solid #f4f4f4;
            text-decoration: none;
            color: #333;
        }
        .employee-list a:hover {
            background-color: #f4f4f4;
        }
        .attendance-record {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        table th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Employee Attendance Record</h2>

    <!-- Employee List -->
    <div class="employee-list">
        <h3>Employee List</h3>
        <?php while ($row = $employees->fetch_assoc()): ?>
            <a href="?employee=<?php echo $row['employee_name']; ?>">
                <?php echo $row['employee_name']; ?>
            </a>
        <?php endwhile; ?>
    </div>

    <!-- Attendance Record -->
    <?php if (isset($_GET['employee'])): ?>
        <?php
        $employee_name = $_GET['employee'];
        $result = $con->query("SELECT * FROM attendance WHERE employee_name = '$employee_name'");
        ?>
        <div class="attendance-record">
            <h3>Attendance Record for <?php echo $employee_name; ?></h3>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($record = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $record['date']; ?></td>
                        <td><?php echo $record['check_in_time']; ?></td>
                        <td><?php echo $record['check_out_time']; ?></td>
                        <td><?php echo $record['status']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
