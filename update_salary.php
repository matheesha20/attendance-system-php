<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fields = [];
    if (isset($_POST['basic_salary'])) {
        $fields['basic_salary'] = $_POST['basic_salary'];
    }
    if (isset($_POST['epf_rate'])) {
        $fields['epf_rate'] = $_POST['epf_rate'];
    }
    if (isset($_POST['loan_amount'])) {
        $fields['loan_amount'] = $_POST['loan_amount'];
    }
    if (isset($_POST['loan_deduction'])) {
        $fields['loan_deduction'] = $_POST['loan_deduction'];
    }
    if (isset($_POST['loan_start_date'])) {
        $fields['loan_start_date'] = $_POST['loan_start_date'];
    }
    if (isset($_POST['loan_duration'])) {
        $fields['loan_duration'] = $_POST['loan_duration'];
    }

    $setPart = [];
    foreach ($fields as $key => $value) {
        $setPart[] = "$key = :$key";
    }
    $setPart = implode(', ', $setPart);

    $query = "UPDATE users SET $setPart WHERE id = :user_id";
    $stmt = $db->prepare($query);

    foreach ($fields as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header('Location: salary_settings.php');
    } else {
        echo "Error updating record: " . $stmt->errorInfo();
    }
} else {
    header('Location: salary_settings.php');
}
?>
