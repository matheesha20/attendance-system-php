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

    if (isset($_POST['first_name'])) {
        $fields['first_name'] = $_POST['first_name'];
    }
    if (isset($_POST['last_name'])) {
        $fields['last_name'] = $_POST['last_name'];
    }
    if (isset($_POST['company'])) {
        $fields['company'] = $_POST['company'];
    }
    if (isset($_POST['position'])) {
        $fields['position'] = $_POST['position'];
    }
    if (isset($_POST['emp_no'])) {
        $fields['emp_no'] = $_POST['emp_no'];
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
        header('Location: profile.php');
    } else {
        echo "Error updating record: " . $stmt->errorInfo();
    }
} else {
    header('Location: profile.php');
}
?>
