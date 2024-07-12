<?php
$dsn = 'mysql:host=localhost;dbname=atte_db';
$username = 'atte_db';
$password = 'Universal@2024';
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
);

try {
    $db = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
