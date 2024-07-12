<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT first_name, last_name, company, position, emp_no, work_days, salary_start_date, salary_end_date FROM users WHERE id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$salary_start_date = $user['salary_start_date'];
$salary_end_date = $user['salary_end_date'];

if (isset($_POST['month'])) {
    $selected_month = $_POST['month'];
} else {
    $selected_month = date('Y-m');
}

$start_date = (new DateTime($selected_month . '-' . $salary_start_date))->modify('-1 month')->format('Y-m-d');
$end_date = (new DateTime($selected_month . '-' . $salary_end_date))->format('Y-m-d');

$query = "SELECT id, date, in_time, off_time, type FROM attendance WHERE user_id = :user_id AND date BETWEEN :start_date AND :end_date";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindParam(':start_date', $start_date);
$stmt->bindParam(':end_date', $end_date);
$stmt->execute();
$attendance_records = $stmt->fetchAll(PDO::FETCH_ASSOC);

$month = date('F Y', strtotime($selected_month));
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attendance Records</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        @media print {
            .navbar, .form-group, .btn, .edit-delete {
                display: none !important;
            }
            .print-header, .print-table {
                display: table !important;
            }
        }
        .print-header, .print-table {
            display: none;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="dashboard.php">Attendance System</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="record_attendance.php">Record Attendance</a></li>
            <li class="nav-item"><a class="nav-link" href="view_estimated_salary.php">View Estimated Salary</a></li>
            <li class="nav-item"><a class="nav-link" href="salary_settings.php">Salary Settings</a></li>
            <li class="nav-item"><a class="nav-link" href="profile.php">Profile Settings</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2>Attendance Records</h2>
    <form method="POST" class="mb-4">
        <div class="form-group">
            <label for="month">Select Month:</label>
            <input type="month" class="form-control" id="month" name="month" value="<?php echo htmlspecialchars($selected_month); ?>">
        </div>
        <button type="submit" class="btn btn-primary">View</button>
        <button type="button" class="btn btn-secondary" onclick="window.print();">Print</button>
    </form>
    <div class="print-header">
        <p>User: <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
        <p>Company: <?php echo htmlspecialchars($user['company']); ?></p>
        <p>Position: <?php echo htmlspecialchars($user['position']); ?></p>
        <p>Employee Number: <?php echo htmlspecialchars($user['emp_no']); ?></p>
        <p>Month: <?php echo $month; ?></p>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>In Time</th>
                <th>Off Time</th>
                <th>Type</th>
                <th class="edit-delete">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($attendance_records as $record): ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['date']); ?></td>
                    <td><?php echo htmlspecialchars($record['in_time']); ?></td>
                    <td><?php echo htmlspecialchars($record['off_time']); ?></td>
                    <td><?php echo htmlspecialchars($record['type']); ?></td>
                    <td class="edit-delete">
                        <form action="edit_attendance.php" method="POST" style="display:inline;">
                            <input type="hidden" name="attendance_id" value="<?php echo $record['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-warning">Edit</button>
                        </form>
                        <form action="delete_attendance.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this record?');">
                            <input type="hidden" name="attendance_id" value="<?php echo $record['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
