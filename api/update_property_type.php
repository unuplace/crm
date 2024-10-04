<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit('Forbidden');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $property_id = $_POST['property_id'];
    $name = $_POST['name'];
    $property_usage = $_POST['property_usage'];
    $unit_type = $_POST['unit_type'];
    $land_area = $_POST['land_area'];
    $building_area = $_POST['building_area'];
    $floors = $_POST['floors'];
    $bedrooms = $_POST['bedrooms'];
    $living_rooms = $_POST['living_rooms'];
    $halls = $_POST['halls'];
    $bathrooms = $_POST['bathrooms'];
    $kitchen = $_POST['kitchen'];

    // التعامل مع تحميل الصورة الجديدة إذا تم تقديمها
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
    } else {
        // إذا لم يتم تقديم صورة جديدة، استخدم الصورة الحالية
        $plan_image = $_POST['current_plan_image'];
    }

    $result = update_property_type($pdo, $property_id, $name, $property_usage, $unit_type, $land_area, $building_area, $floors, $bedrooms, $living_rooms, $halls, $bathrooms, $kitchen, $plan_image);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update property type']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}