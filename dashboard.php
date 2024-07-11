<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .center-text {
            text-align: center;
        }
    </style>
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
            <?php if ($_SESSION['username'] === 'admin'): ?>
                <li class="nav-item"><a class="nav-link" href="admin_panel.php">Admin Panel</a></li>
            <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="record_attendance.php">Record Attendance</a></li>
                <li class="nav-item"><a class="nav-link" href="view_monthly.php">View Monthly Attendance</a></li>
                <li class="nav-item"><a class="nav-link" href="view_estimated_salary.php">View Estimated Salary</a></li>
                <li class="nav-item"><a class="nav-link" href="salary_settings.php">Salary Settings</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php">Profile Settings</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="center-text">Dashboard</h2>
    <p class="center-text">Welcome to the Attendance System. Use the links above to manage your attendance and profile settings.</p>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
