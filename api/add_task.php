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
    $task_type = $_POST['task_type'];
    $task_date = $_POST['task_date'];
    $task_time = $_POST['task_time'];
    $task_description = $_POST['task_description'];

    $result = add_task_to_client($pdo, $client_id, $task_type, $task_date, $task_time, $task_description);
    if ($result) {
        header("Location: client_details.php?id=$client_id");
        exit();
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to add task']);
    }
}