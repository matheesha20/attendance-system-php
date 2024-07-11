<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

$stmt = $db->prepare("SELECT * FROM admin WHERE id = 1");
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

$work_days_array = explode(',', $admin['work_days']);
$regular_ot_days_array = explode(',', $admin['regular_ot_days']);
$double_ot_days_array = explode(',', $admin['double_ot_days']);
$days_of_week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$unselected_days = array_diff($days_of_week, $work_days_array);

// Check for session messages
$success_message = $_SESSION['message'] ?? null;
$error_message = $_SESSION['error'] ?? null;

// Clear session messages
unset($_SESSION['message'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
        }
        .form-check-inline {
            margin-right: 10px;
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
            <?php if (isset($_SESSION['username']) && $_SESSION['username'] === 'admin'): ?>
                <li class="nav-item"><a class="nav-link" href="admin_panel.php">Admin Panel</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2>Admin Panel</h2>
    <?php if ($success_message): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <form action="update_admin_settings.php" method="POST" class="mt-4">
        <div class="form-group">
            <label for="working_days_per_month">Working Days per Month</label>
            <input type="number" class="form-control" name="working_days_per_month" value="<?php echo htmlspecialchars($admin['working_days_per_month']); ?>" required>
        </div>
        <div class="form-group">
            <label for="work_days">Work Days</label><br>
            <?php
            foreach ($days_of_week as $day) {
                $checked = in_array($day, $work_days_array) ? 'checked' : '';
                echo "<div class='form-check form-check-inline'>
                        <input class='form-check-input' type='checkbox' name='work_days[]' value='$day' $checked>
                        <label class='form-check-label'>$day</label>
                      </div>";
            }
            ?>
        </div>
        <div class="form-group">
            <label for="regular_ot_days">Regular OT Days</label><br>
            <?php
            foreach ($unselected_days as $day) {
                $checked = in_array($day, $regular_ot_days_array) ? 'checked' : '';
                echo "<div class='form-check form-check-inline'>
                        <input class='form-check-input' type='checkbox' name='regular_ot_days[]' value='$day' $checked>
                        <label class='form-check-label'>$day</label>
                      </div>";
            }
            ?>
        </div>
        <div class="form-group">
            <label for="double_ot_days">Double OT Days</label><br>
            <?php
            foreach ($unselected_days as $day) {
                $checked = in_array($day, $double_ot_days_array) ? 'checked' : '';
                echo "<div class='form-check form-check-inline'>
                        <input class='form-check-input' type='checkbox' name='double_ot_days[]' value='$day' $checked>
                        <label class='form-check-label'>$day</label>
                      </div>";
            }
            ?>
        </div>
        <div class="form-group">
            <label for="work_start_time">Work Start Time</label>
            <input type="time" class="form-control" name="work_start_time" value="<?php echo htmlspecialchars($admin['work_start_time']); ?>" required>
        </div>
        <div class="form-group">
            <label for="work_end_time">Work End Time</label>
            <input type="time" class="form-control" name="work_end_time" value="<?php echo htmlspecialchars($admin['work_end_time']); ?>" required>
        </div>
        <div class="form-group">
            <label for="salary_start_date">Salary Start Date</label>
            <input type="number" class="form-control" name="salary_start_date" value="<?php echo htmlspecialchars($admin['salary_start_date']); ?>" required>
        </div>
        <div class="form-group">
            <label for="salary_end_date">Salary End Date</label>
            <input type="number" class="form-control" name="salary_end_date" value="<?php echo htmlspecialchars($admin['salary_end_date']); ?>" required>
        </div>
        <div class="form-group">
            <label for="ot_interval">OT Interval (minutes)</label>
            <input type="number" class="form-control" name="ot_interval" value="<?php echo htmlspecialchars($admin['ot_interval']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Settings</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
