<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit('Forbidden');
}

if (isset($_GET['id'])) {
    $employee_id = $_GET['id'];
    $employee = get_employee_data($pdo, $employee_id);
    
    if ($employee) {
        echo json_encode($employee);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Employee not found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Missing employee ID']);
}

function get_employee_data($pdo, $employee_id) {
    $stmt = $pdo->prepare("SELECT id, username, full_name, email, phone, role, project_id, daily_call_target, monthly_call_target, monthly_sales_target, monthly_visit_target FROM users WHERE id = ?");
    $stmt->execute([$employee_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}