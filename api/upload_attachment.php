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
    $attachment_name = $_POST['attachment_name'];
    $attachment = $_FILES['attachment'];

    // تحقق من تحميل الملف
    if ($attachment['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        $upload_file = $upload_dir . basename($attachment['name']);

        // نقل الملف إلى المجلد
        if (move_uploaded_file($attachment['tmp_name'], $upload_file)) {
            // إدراج معلومات المرفق في قاعدة البيانات
            $result = add_attachment($pdo, $client_id, $attachment_name, $upload_file);
            if ($result) {
                header("Location: client_details.php?id=$client_id");
                exit();
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to add attachment']);
            }
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to upload file']);
        }
    }
}