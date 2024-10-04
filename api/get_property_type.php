<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

// التحقق من تسجيل دخول المستخدم وأنه مسؤول
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit(json_encode(['error' => 'Forbidden']));
}

// التحقق من وجود معرف النموذج في الطلب
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit(json_encode(['error' => 'Missing property type ID']));
}

$property_type_id = $_GET['id'];

// استرجاع بيانات نموذج العقار
try {
    $stmt = $pdo->prepare("SELECT * FROM property_types WHERE id = ?");
    $stmt->execute([$property_type_id]);
    $property_type = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$property_type) {
        http_response_code(404);
        exit(json_encode(['error' => 'Property type not found']));
    }

    // إرسال البيانات كاستجابة JSON
    header('Content-Type: application/json');
    echo json_encode($property_type);
} catch (PDOException $e) {
    http_response_code(500);
    exit(json_encode(['error' => 'Database error: ' . $e->getMessage()]));
}