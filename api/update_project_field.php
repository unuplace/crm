<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit('Forbidden');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_POST['pk'];
    $field = $_POST['name'];
    $value = $_POST['value'];

    // التحقق من أن الحقل قابل للتعديل
    $editable_fields = ['total_units', 'sold_units', 'design_count', 'start_date', 'end_date', 'status', 'description'];
    if (!in_array($field, $editable_fields)) {
        http_response_code(400);
        exit(json_encode(['error' => 'This field cannot be edited']));
    }

    if (update_project_field($pdo, $project_id, $field, $value)) {
        // إذا تم تحديث الوحدات المباعة، قم بتحديث الوحدات المتبقية
        if ($field === 'sold_units' || $field === 'total_units') {
            update_remaining_units($pdo, $project_id);
        }
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update project field']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}