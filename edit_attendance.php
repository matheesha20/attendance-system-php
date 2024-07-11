<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $attendance_id = $_POST['attendance_id'];
    $date = $_POST['date'];
    $in_time = $_POST['in_time'];
    $off_time = $_POST['off_time'];
    $type = $_POST['type'];

    $query = "UPDATE attendance SET date = :date, in_time = :in_time, off_time = :off_time, type = :type WHERE id = :attendance_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':in_time', $in_time);
    $stmt->bindParam(':off_time', $off_time);
    $stmt->bindParam(':type', $type);
    $stmt->bindParam(':attendance_id', $attendance_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Attendance record updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update attendance record.";
    }
}

header('Location: view_monthly.php');
exit();
?>
