<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit(json_encode(['error' => 'Forbidden']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_POST['project_id'];
    $property_types = [];

    // جمع النماذج والعدد من الطلب
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'property_type_') === 0) {
            $property_type_id = str_replace('property_type_', '', $key);
            $quantity = (int)$value;

            if ($quantity > 0) {
                $property_types[] = [
                    'property_type_id' => $property_type_id,
                    'quantity' => $quantity
                ];
            }
        }
    }

    // إضافة النماذج إلى المشروع
    foreach ($property_types as $property_type) {
        add_property_type_to_project($pdo, $project_id, $property_type['property_type_id'], $property_type['quantity']);
    }

    echo json_encode(['success' => true]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}