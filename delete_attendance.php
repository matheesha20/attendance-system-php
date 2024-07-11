<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $attendance_id = $_POST['attendance_id'];

    $query = "DELETE FROM attendance WHERE id = :attendance_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':attendance_id', $attendance_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Attendance record deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete attendance record.";
    }
}

header('Location: view_monthly.php');
exit();
?>
