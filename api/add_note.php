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
    $note = $_POST['note'];

    $result = add_note_to_client($pdo, $client_id, $note);
    if ($result) {
        header("Location: client_details.php?id=$client_id");
        exit();
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to add note']);
    }
}