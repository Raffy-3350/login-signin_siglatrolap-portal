<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "siglatrolap_db") or die("Couldn't Connect");

$employee_id = 1; // Static for now, replace this with logged-in user ID
$date = date("Y-m-d");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["checkIn"])) {
        // Check if already checked in
        $sql = "SELECT * FROM attendance WHERE employee_id = ? AND date = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("is", $employee_id, $date);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row && $row['check_in_time']) {
            echo json_encode(["error" => "You have already checked in today!"]);
            exit;
        }

        // Insert Check-In
        $sql = "INSERT INTO attendance (employee_id, date, check_in_time) VALUES (?, ?, NOW())";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("is", $employee_id, $date);
        $stmt->execute();

        echo json_encode(["success" => "Checked in successfully!"]);
        exit;
    }

    if (isset($_POST["checkOut"])) {
        $sql = "SELECT * FROM attendance WHERE employee_id = ? AND date = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("is", $employee_id, $date);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row['check_in_time']) {
            echo json_encode(["error" => "You need to check in first!"]);
            exit;
        }

        if ($row['check_out_time']) {
            echo json_encode(["error" => "You have already checked out today!"]);
            exit;
        }

        // Update Check-Out
        $sql = "UPDATE attendance SET check_out_time = NOW() WHERE employee_id = ? AND date = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("is", $employee_id, $date);
        $stmt->execute();

        echo json_encode(["success" => "Checked out successfully!"]);
        exit;
    }
}
?>
<?php
$employee_id = 1; // Static for now, later replace with actual logged-in user ID
$date = date("Y-m-d");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["checkIn"])) {
        // Check if already checked in today
        $sql = "SELECT * FROM attendance WHERE employee_id = ? AND date = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("is", $employee_id, $date);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row && $row['check_in_time']) {
            echo json_encode(["error" => "You have already checked in today!"]);
            exit;
        }

        // Insert check-in record
        $sql = "INSERT INTO attendance (employee_id, date, check_in_time) VALUES (?, ?, NOW())";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("is", $employee_id, $date);
        if ($stmt->execute()) {
            echo json_encode(["success" => "Checked in successfully!"]);
        } else {
            echo json_encode(["error" => "Error checking in!"]);
        }
        exit;
    }

    if (isset($_POST["checkOut"])) {
        // Re-fetch attendance record to ensure the latest data
        $sql = "SELECT * FROM attendance WHERE employee_id = ? AND date = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("is", $employee_id, $date);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row || !$row['check_in_time']) {
            echo json_encode(["error" => "You need to check in first!"]);
            exit;
        }

        if ($row['check_out_time']) {
            echo json_encode(["error" => "You have already checked out today!"]);
            exit;
        }

        // Update check-out time
        $sql = "UPDATE attendance SET check_out_time = NOW() WHERE employee_id = ? AND date = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("is", $employee_id, $date);
        if ($stmt->execute()) {
            echo json_encode(["success" => "Checked out successfully!"]);
        } else {
            echo json_encode(["error" => "Error checking out!"]);
        }
        exit;
    }
}
if (isset($_GET["fetchRecords"])) {
    $sql = "SELECT date, check_in_time, check_out_time FROM attendance WHERE employee_id = ? ORDER BY date DESC";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $records = [];
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
    
    echo json_encode($records);
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Employee Attendance</h2>
        <p id="currentDate"></p>
        <div class="clock">
            <p id="liveClock">00:00:00 AM</p>
        </div>

        <div class="attendance-box">
            <button id="checkInBtn">Time In</button>
            <button id="checkOutBtn" disabled>Time Out</button>
        </div>

        <p id="statusMessage"></p>

       
    </div>

    <script>
        // Live Digital Clock
        function updateClock() {
            const now = new Date();
            document.getElementById("liveClock").innerText = now.toLocaleTimeString("en-US", { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
        }
        setInterval(updateClock, 1000);
        updateClock();

        function sendAttendance(action) {
            let formData = new FormData();
            formData.append(action, true);

            fetch("attendance.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById("statusMessage").innerHTML = `<span style="color: green;">${data.success}</span>`;
                    if (action === "checkIn") {
                        document.getElementById("checkInBtn").disabled = true;
                        document.getElementById("checkOutBtn").disabled = false;
                    } else {
                        document.getElementById("checkOutBtn").disabled = true;
                    }
                } else {
                    document.getElementById("statusMessage").innerHTML = `<span style="color: red;">${data.error}</span>`;
                }
            });
        }

        document.getElementById("checkInBtn").addEventListener("click", function() {
            sendAttendance("checkIn");
        });

        document.getElementById("checkOutBtn").addEventListener("click", function() {
            sendAttendance("checkOut");
        });

        function fetchAttendanceRecords() {
    fetch("attendance.php?fetchRecords=true")
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById("attendanceTable");
            tableBody.innerHTML = ""; // Clear previous records

            data.forEach(record => {
                let checkInTime = record.check_in_time ? formatTime(record.check_in_time) : "—";
                let checkOutTime = record.check_out_time ? formatTime(record.check_out_time) : "—";

                let row = `<tr>
                    <td>${record.date}</td>
                    <td>${checkInTime}</td>
                    <td>${checkOutTime}</td>
                </tr>`;
                tableBody.innerHTML += row;
            });
        });
}

// Function to format time into 12-hour format with AM/PM
function formatTime(time) {
    let [hour, minute, second] = time.split(":");
    hour = parseInt(hour);
    let period = hour >= 12 ? "PM" : "AM";
    hour = hour % 12 || 12; // Convert to 12-hour format
    return `${hour}:${minute} ${period}`;
}

// Call fetchAttendanceRecords() when page loads
document.addEventListener("DOMContentLoaded", fetchAttendanceRecords);



function sendAttendance(action) {
    let formData = new FormData();
    formData.append(action, true);

    fetch("attendance.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById("statusMessage").innerHTML = `<span style="color: green;">${data.success}</span>`;
            if (action === "checkIn") {
                document.getElementById("checkInBtn").disabled = true;
                document.getElementById("checkOutBtn").disabled = false;
            } else {
                document.getElementById("checkOutBtn").disabled = true;
            }
            fetchAttendanceRecords(); // Reload attendance records after action
        } else {
            document.getElementById("statusMessage").innerHTML = `<span style="color: red;">${data.error}</span>`;
        }
    });
}

    </script>
</body>
</html>

