<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit(json_encode(['error' => 'Forbidden']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $property_id = $_POST['id'];
    $field = $_POST['field'];
    $value = $_POST['value'];

    // Validate field
    $allowed_fields = ['serial_number', 'price', 'status', 'readiness'];
    if (!in_array($field, $allowed_fields)) {
        http_response_code(400);
        exit(json_encode(['error' => 'Invalid field']));
    }

    // Update property
    $stmt = $pdo->prepare("UPDATE properties SET $field = :value WHERE id = :id");
    $result = $stmt->execute([':value' => $value, ':id' => $property_id]);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update property']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}