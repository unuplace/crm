<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit('Forbidden');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['logo'])) {
    $project_id = $_POST['project_id'];
    $upload_dir = '../uploads/project_logos/';
    $file_name = uniqid() . '_' . $_FILES['logo']['name'];
    $upload_path = $upload_dir . $file_name;

    if (move_uploaded_file($_FILES['logo']['tmp_name'], $upload_path)) {
        if (update_project_logo($pdo, $project_id, $file_name)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update project logo in database']);
        }
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to upload logo file']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}