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
    <title>Profile Settings</title>
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
            <li class="nav-item"><a class="nav-link" href="salary_settings.php">Salary Settings</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2>Profile Settings</h2>
    <form method="POST" action="update_profile.php">
        <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>">
        </div>
        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>">
        </div>
        <div class="form-group">
            <label for="company">Company:</label>
            <input type="text" class="form-control" id="company" name="company" value="<?php echo htmlspecialchars($user['company']); ?>">
        </div>
        <div class="form-group">
            <label for="position">Position:</label>
            <input type="text" class="form-control" id="position" name="position" value="<?php echo htmlspecialchars($user['position']); ?>">
        </div>
        <div class="form-group">
            <label for="emp_no">Employee Number:</label>
            <input type="text" class="form-control" id="emp_no" name="emp_no" value="<?php echo htmlspecialchars($user['emp_no']); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
