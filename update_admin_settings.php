<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$working_days_per_month = $_POST['working_days_per_month'];
$work_days = implode(',', $_POST['work_days']);
$work_start_time = $_POST['work_start_time'];
$work_end_time = $_POST['work_end_time'];
$salary_start_date = $_POST['salary_start_date'];
$salary_end_date = $_POST['salary_end_date'];
$ot_interval = $_POST['ot_interval'];
$regular_ot_days = implode(',', $_POST['regular_ot_days']);
$double_ot_days = implode(',', $_POST['double_ot_days']);

$stmt = $db->prepare("UPDATE admin SET working_days_per_month = :working_days_per_month, work_days = :work_days, work_start_time = :work_start_time, work_end_time = :work_end_time, salary_start_date = :salary_start_date, salary_end_date = :salary_end_date, ot_interval = :ot_interval, regular_ot_days = :regular_ot_days, double_ot_days = :double_ot_days WHERE id = 1");
$stmt->bindParam(':working_days_per_month', $working_days_per_month);
$stmt->bindParam(':work_days', $work_days);
$stmt->bindParam(':work_start_time', $work_start_time);
$stmt->bindParam(':work_end_time', $work_end_time);
$stmt->bindParam(':salary_start_date', $salary_start_date);
$stmt->bindParam(':salary_end_date', $salary_end_date);
$stmt->bindParam(':ot_interval', $ot_interval);
$stmt->bindParam(':regular_ot_days', $regular_ot_days);
$stmt->bindParam(':double_ot_days', $double_ot_days);

if ($stmt->execute()) {
    $_SESSION['message'] = 'Settings updated successfully.';
} else {
    $_SESSION['error'] = 'Failed to update settings.';
}

header('Location: admin_panel.php');
exit();
?>
