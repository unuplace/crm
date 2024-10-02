<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit('Forbidden');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_POST['client_id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $result = update_potential_client($pdo, $client_id, $name, $phone, $email);
    if ($result) {
        header("Location: client_details.php?id=$client_id");
        exit();
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update client']);
    }
}