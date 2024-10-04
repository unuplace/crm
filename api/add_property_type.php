<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit(json_encode(['error' => 'Forbidden']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $property_usage = $_POST['property_usage'] ?? '';
    $unit_type = $_POST['unit_type'] ?? '';
    $land_area = $_POST['land_area'] ?? 0;
    $building_area = $_POST['building_area'] ?? 0;
    $floors = $_POST['floors'] ?? 0;
    $bedrooms = $_POST['bedrooms'] ?? 0;
    $halls = $_POST['halls'] ?? 0;
    $living_rooms = $_POST['living_rooms'] ?? 0;
    $bathrooms = $_POST['bathrooms'] ?? 0;
    $kitchen = $_POST['kitchen'] ?? '';

    // التعامل مع تحميل الصورة
    $plan_image = '';
    if (isset($_FILES['plan_image']) && $_FILES['plan_image']['error'] == 0) {
        $upload_dir = '../uploads/property_plans/';
        $file_name = uniqid() . '_' . $_FILES['plan_image']['name'];
        $upload_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['plan_image']['tmp_name'], $upload_path)) {
            $plan_image = $file_name;
        } else {
            http_response_code(500);
            exit(json_encode(['error' => 'Failed to upload image']));
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO property_types (name, property_usage, unit_type, land_area, building_area, floors, bedrooms, halls, living_rooms, bathrooms, kitchen, plan_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([$name, $property_usage, $unit_type, $land_area, $building_area, $floors, $bedrooms, $halls, $living_rooms, $bathrooms, $kitchen, $plan_image]);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to add property type']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}