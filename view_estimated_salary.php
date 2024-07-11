<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT first_name, last_name, basic_salary, work_days, work_start_time, work_end_time, salary_start_date, salary_end_date, ot_interval, regular_ot_days, double_ot_days, epf_rate, loan_deduction, loan_start_date, loan_duration FROM users WHERE id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$work_days_array = explode(',', $user['work_days']);
$regular_ot_days_array = explode(',', $user['regular_ot_days']);
$double_ot_days_array = explode(',', $user['double_ot_days']);

$basic_salary = $user['basic_salary'];
$epf_rate = $user['epf_rate'];
$loan_deduction = $user['loan_deduction'];
$work_start_time = new DateTime($user['work_start_time']);
$work_end_time = new DateTime($user['work_end_time']);
$work_hours_per_day = $work_start_time->diff($work_end_time)->h + ($work_start_time->diff($work_end_time)->i / 60);

$salary_start_date = $user['salary_start_date'];
$salary_end_date = $user['salary_end_date'];
$ot_interval = $user['ot_interval'] / 60; // Convert OT interval to hours

$regular_ot_rate = $basic_salary / 200 * 1.5;
$double_ot_rate = $basic_salary / 200 * 2;

$estimated_salary = 0;
$total_regular_ot_hours = 0;
$total_double_ot_hours = 0;
$days_present = 0;

if (isset($_POST['month'])) {
    $selected_month = $_POST['month'];
} else {
    $selected_month = date('Y-m');
}

// Determine the correct start and end date for the selected month
$start_date = (new DateTime($selected_month . '-' . $salary_start_date))->modify('-1 month')->format('Y-m-d');
$end_date = (new DateTime($selected_month . '-' . $salary_end_date))->format('Y-m-d');

$query = "SELECT date, type, TIME_TO_SEC(TIMEDIFF(off_time, in_time)) / 3600 AS hours_worked FROM attendance WHERE user_id = :user_id AND (date BETWEEN :start_date AND :end_date)";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindParam(':start_date', $start_date);
$stmt->bindParam(':end_date', $end_date);
$stmt->execute();
$attendance_records = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($attendance_records as $record) {
    $date = new DateTime($record['date']);
    $day_of_week = $date->format('l');
    $hours_worked = $record['hours_worked'];

    if (in_array($record['type'], ['Paid Leave', 'Present'])) {
        if (in_array($day_of_week, $work_days_array)) {
            $estimated_salary += ($basic_salary / 30);
            $days_present++;
        }
    }

    if (in_array($day_of_week, $regular_ot_days_array)) {
        $ot_hours = max(0, $hours_worked);
        $ot_hours = floor($ot_hours / $ot_interval) * $ot_interval; // Calculate OT in intervals
        $total_regular_ot_hours += $ot_hours;
    } elseif (in_array($day_of_week, $double_ot_days_array)) {
        $ot_hours = max(0, $hours_worked);
        $ot_hours = floor($ot_hours / $ot_interval) * $ot_interval; // Calculate OT in intervals
        $total_double_ot_hours += $ot_hours;
    } elseif (in_array($day_of_week, $work_days_array)) {
        if ($hours_worked > $work_hours_per_day) {
            $ot_hours = max(0, $hours_worked - $work_hours_per_day);
            $ot_hours = floor($ot_hours / $ot_interval) * $ot_interval; // Calculate OT in intervals
            $total_regular_ot_hours += $ot_hours;
        }
    }
}

$loan_start_date = new DateTime($user['loan_start_date']);
$loan_duration = $user['loan_duration'];
$current_date = new DateTime($selected_month . '-01');
$loan_end_date = clone $loan_start_date;
$loan_end_date->modify("+$loan_duration months");

$is_loan_active = $current_date >= $loan_start_date && $current_date < $loan_end_date;

$loan_deduction = $is_loan_active ? $user['loan_deduction'] : 0;

$total_regular_ot_amount = $total_regular_ot_hours * $regular_ot_rate;
$total_double_ot_amount = $total_double_ot_hours * $double_ot_rate;
$estimated_salary += $total_regular_ot_amount + $total_double_ot_amount;
$epf_deduction = $estimated_salary * ($epf_rate / 100);
$net_salary = $estimated_salary - $epf_deduction - $loan_deduction;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Estimated Salary</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
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
            <li class="nav-item"><a class="nav-link" href="view_monthly.php">View Monthly Attendance</a></li>
            <li class="nav-item"><a class="nav-link" href="salary_settings.php">Salary Settings</a></li>
            <li class="nav-item"><a class="nav-link" href="profile.php">Profile Settings</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2>Estimated Salary for <?php echo date('F Y', strtotime($selected_month)); ?></h2>
    <form method="POST" class="mb-4">
        <div class="form-group">
            <label for="month">Select Month:</label>
            <input type="month" class="form-control" id="month" name="month" value="<?php echo htmlspecialchars($selected_month); ?>">
        </div>
        <button type="submit" class="btn btn-primary">View</button>
    </form>
    <h3>Salary Details</h3>
    <table class="table table-bordered">
        <tr>
            <th>Basic Salary</th>
            <td>LKR <?php echo number_format($basic_salary, 2); ?></td>
        </tr>
        <tr>
            <th>Regular OT Rate (per hour)</th>
            <td>LKR <?php echo number_format($regular_ot_rate, 2); ?></td>
        </tr>
        <tr>
            <th>Double OT Rate (per hour)</th>
            <td>LKR <?php echo number_format($double_ot_rate, 2); ?></td>
        </tr>
        <tr>
            <th>Total Regular OT Hours</th>
            <td><?php echo number_format($total_regular_ot_hours, 2); ?></td>
        </tr>
        <tr>
            <th>Total Double OT Hours</th>
            <td><?php echo number_format($total_double_ot_hours, 2); ?></td>
        </tr>
        <tr>
            <th>Total Regular OT Amount</th>
            <td>LKR <?php echo number_format($total_regular_ot_amount, 2); ?></td>
        </tr>
        <tr>
            <th>Total Double OT Amount</th>
            <td>LKR <?php echo number_format($total_double_ot_amount, 2); ?></td>
        </tr>
        <tr>
            <th>EPF Deduction</th>
            <td>LKR <?php echo number_format($epf_deduction, 2); ?></td>
        </tr>
        <tr>
            <th>Loan Deduction</th>
            <td>LKR <?php echo number_format($loan_deduction, 2); ?></td>
        </tr>
        <tr>
            <th>Net Salary</th>
            <td>LKR <?php echo number_format($net_salary, 2); ?></td>
        </tr>
        <tr>
            <th>Number of Worked Days</th>
            <td><?php echo $days_present; ?></td>
        </tr>
    </table>
    <h3>Attendance Details</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Hours Worked</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($attendance_records as $record): ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['date']); ?></td>
                    <td><?php echo htmlspecialchars($record['type']); ?></td>
                    <td><?php echo number_format($record['hours_worked'], 2); ?></td>
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
