<!DOCTYPE html>
<html>
<head>
    <title>Record Attendance</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#type').change(function() {
                var type = $(this).val();
                if (type === 'Paid Leave' || type === 'Unpaid Leave' || type === 'Absent') {
                    $('#in_time').prop('disabled', true).val('');
                    $('#off_time').prop('disabled', true).val('');
                } else {
                    $('#in_time').prop('disabled', false);
                    $('#off_time').prop('disabled', false);
                }
            });
        });
    </script>
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
            <li class="nav-item"><a class="nav-link" href="view_monthly.php">View Monthly Attendance</a></li>
            <li class="nav-item"><a class="nav-link" href="view_estimated_salary.php">View Estimated Salary</a></li>
            <li class="nav-item"><a class="nav-link" href="salary_settings.php">Salary Settings</a></li>
            <li class="nav-item"><a class="nav-link" href="profile.php">Profile Settings</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2>Record Attendance</h2>
    <?php
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);
    }
    ?>
    <form action="record_attendance_action.php" method="post">
        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>
        <div class="form-group">
            <label for="type">Type:</label>
            <select class="form-control" id="type" name="type" required>
                <option value="Present">Present</option>
                <option value="Paid Leave">Paid Leave</option>
                <option value="Unpaid Leave">Unpaid Leave</option>
                <option value="Half Day">Half Day</option>
                <option value="Short Leave">Short Leave</option>
                <option value="Absent">Absent</option>
            </select>
        </div>
        <div class="form-group">
            <label for="in_time">In Time:</label>
            <input type="time" class="form-control" id="in_time" name="in_time">
        </div>
        <div class="form-group">
            <label for="off_time">Off Time:</label>
            <input type="time" class="form-control" id="off_time" name="off_time">
        </div>
        <button type="submit" class="btn btn-primary">Record Attendance</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
