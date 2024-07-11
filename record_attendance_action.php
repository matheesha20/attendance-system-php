<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $type = $_POST['type'];
    $in_time = !empty($_POST['in_time']) ? $_POST['in_time'] : null;
    $off_time = !empty($_POST['off_time']) ? $_POST['off_time'] : null;

    try {
        // Check if a record for the same date already exists
        $checkQuery = "SELECT * FROM attendance WHERE user_id = :user_id AND date = :date";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $checkStmt->bindParam(':date', $date);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            // Record exists, update it
            if ($type == 'Present') {
                $existingRecord = $checkStmt->fetch(PDO::FETCH_ASSOC);
                if (is_null($in_time)) {
                    $in_time = $existingRecord['in_time'];
                }
                if (is_null($off_time)) {
                    $off_time = $existingRecord['off_time'];
                }
                $query = "UPDATE attendance SET type = :type, in_time = :in_time, off_time = :off_time WHERE user_id = :user_id AND date = :date";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':date', $date);
                $stmt->bindParam(':type', $type);
                $stmt->bindParam(':in_time', $in_time);
                $stmt->bindParam(':off_time', $off_time);
            } else {
                $query = "UPDATE attendance SET type = :type WHERE user_id = :user_id AND date = :date";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':date', $date);
                $stmt->bindParam(':type', $type);
            }
        } else {
            // No record exists, insert a new one
            if ($type == 'Present') {
                $query = "INSERT INTO attendance (user_id, date, type, in_time, off_time) VALUES (:user_id, :date, :type, :in_time, :off_time)";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':date', $date);
                $stmt->bindParam(':type', $type);
                $stmt->bindParam(':in_time', $in_time);
                $stmt->bindParam(':off_time', $off_time);
            } else {
                $query = "INSERT INTO attendance (user_id, date, type) VALUES (:user_id, :date, :type)";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':date', $date);
                $stmt->bindParam(':type', $type);
            }
        }

        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Attendance recorded successfully';
        } else {
            $_SESSION['error_message'] = 'Failed to record attendance';
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Database error: ' . $e->getMessage();
    }

    header('Location: record_attendance.php');
    exit();
} else {
    $_SESSION['error_message'] = 'Invalid request method';
    header('Location: record_attendance.php');
    exit();
}
?>
