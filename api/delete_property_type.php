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

    // احصل على معلومات النموذج قبل حذفه لحذف الصورة المرتبطة به
    $property = get_property_type_by_id($pdo, $property_id);

    $result = delete_property_type($pdo, $property_id);

    if ($result) {
        // إذا تم الحذف بنجاح، قم بحذف ملف الصورة المرتبط
        if ($property && $property['plan_image']) {
            $image_path = '../uploads/property_plans/' . $property['plan_image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete property type']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}