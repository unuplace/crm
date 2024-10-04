<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit('Forbidden');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['client_id'])) {
    $client_id = $_POST['client_id'];
    $result = delete_client($pdo, $client_id);
    if ($result) {
        header('Location: clients_list.php');
        exit();
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete client']);
    }
}