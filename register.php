<?php
session_start();
require 'config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $company = $_POST['company'];
    $position = $_POST['position'];
    $emp_no = $_POST['emp_no'];
    $basic_salary = $_POST['basic_salary'];

    $query = "INSERT INTO users (first_name, last_name, username, password, company, position, emp_no, basic_salary) VALUES (:first_name, :last_name, :username, :password, :company, :position, :emp_no, :basic_salary)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':company', $company);
    $stmt->bindParam(':position', $position);
    $stmt->bindParam(':emp_no', $emp_no);
    $stmt->bindParam(':basic_salary', $basic_salary);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $db->lastInsertId();
        header('Location: dashboard.php');
        exit();
    } else {
        $error_message = "Failed to register user.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container mt-5">
    <h2>Register</h2>
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <form action="register.php" method="POST">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" name="first_name" required>
            </div>
            <div class="form-group col-md-6">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" name="last_name" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="form-group col-md-6">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="company">Company</label>
                <input type="text" class="form-control" name="company" required>
            </div>
            <div class="form-group col-md-6">
                <label for="position">Position</label>
                <input type="text" class="form-control" name="position" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="emp_no">Employee Number</label>
                <input type="text" class="form-control" name="emp_no" required>
            </div>
            <div class="form-group col-md-6">
                <label for="basic_salary">Basic Salary</label>
                <input type="number" class="form-control" name="basic_salary" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>
</body>
</html>
