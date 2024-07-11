<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Salary Settings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="dashboard.php">Attendance System</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="record_attendance.php">Record Attendance</a></li>
            <li class="nav-item"><a class="nav-link" href="view_monthly.php">View Monthly Attendance</a></li>
            <li class="nav-item"><a class="nav-link" href="view_estimated_salary.php">View Estimated Salary</a></li>
            <li class="nav-item"><a class="nav-link" href="profile.php">Profile Settings</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2>Salary Settings</h2>
    <form method="POST" action="update_salary.php">
        <div class="form-group">
            <label for="basic_salary">Basic Salary:</label>
            <input type="number" class="form-control" id="basic_salary" name="basic_salary" value="<?php echo htmlspecialchars($user['basic_salary']); ?>">
        </div>
        <div class="form-group">
            <label for="epf_rate">EPF Rate (%):</label>
            <input type="number" class="form-control" id="epf_rate" name="epf_rate" value="<?php echo htmlspecialchars($user['epf_rate']); ?>">
        </div>
        <div class="form-group">
            <label for="loan_amount">Loan Amount:</label>
            <input type="number" class="form-control" id="loan_amount" name="loan_amount" value="<?php echo htmlspecialchars($user['loan_amount']); ?>">
        </div>
        <div class="form-group">
            <label for="loan_deduction">Monthly Loan Deduction:</label>
            <input type="number" class="form-control" id="loan_deduction" name="loan_deduction" value="<?php echo htmlspecialchars($user['loan_deduction']); ?>">
        </div>
        <div class="form-group">
            <label for="loan_start_date">Loan Start Date:</label>
            <input type="date" class="form-control" id="loan_start_date" name="loan_start_date" value="<?php echo htmlspecialchars($user['loan_start_date']); ?>">
        </div>
        <div class="form-group">
            <label for="loan_duration">Loan Duration (months):</label>
            <input type="number" class="form-control" id="loan_duration" name="loan_duration" value="<?php echo htmlspecialchars($user['loan_duration']); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update Salary Settings</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
