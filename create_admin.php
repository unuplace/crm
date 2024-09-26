<?php
require_once 'config/database.php';

$username = 'Asim';
$password = password_hash('12345', PASSWORD_DEFAULT);
$full_name = 'Asim Admin';
$email = 'asim@talad.com';
$phone = '1234567890';
$role = 'admin';

$stmt = $pdo->prepare("INSERT INTO users (username, password, full_name, email, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([$username, $password, $full_name, $email, $phone, $role]);

echo "Admin user created successfully.";
?>