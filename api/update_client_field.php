<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'employee' && $_SESSION['role'] !== 'admin')) {
    http_response_code(403);
    exit('Forbidden');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_POST['pk'];
    $field = $_POST['name'];
    $value = $_POST['value'];
    $employee_id = $_SESSION['user_id'];

    if ($_SESSION['role'] === 'admin' || ($_SESSION['role'] === 'employee' && is_client_assigned_to_employee($pdo, $client_id, $employee_id))) {
        if (update_client_field($pdo, $client_id, $field, $value)) {
            // إذا تم تحديث الحالة إلى "تم البيع"، قم بنقل البيانات إلى جدول العملاء
            if ($field === 'status' && $value === 'تم البيع') {
                transfer_to_customers($pdo, $client_id, $employee_id);
            }
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update client field']);
        }
    } else {
        http_response_code(403);
        echo json_encode(['error' => 'You are not authorized to edit this client']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}

function transfer_to_customers($pdo, $client_id, $employee_id) {
    // الحصول على بيانات العميل المحتمل
    $stmt = $pdo->prepare("SELECT name, email, phone, monthly_commitment, bank, sector, status, notes, contact_date, suorce FROM potential_clients WHERE id = ?");
    $stmt->execute([$client_id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($client) {
        // إدخال البيانات في جدول العملاء
        $stmt = $pdo->prepare("INSERT INTO customers (name, email, phone, monthly_commitment, bank, sector, status, notes, contact_date, suorce, assigned_to) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $client['name'],
            $client['email'],
            $client['phone'],
            $client['monthly_commitment'],
            $client['bank'],
            $client['sector'],
            $client['status'],
            $client['notes'],
            $client['contact_date'],
            $client['suorce'],
            $client['assigned_to'],
            $employee_id
        ]);
    }
    return false;
}