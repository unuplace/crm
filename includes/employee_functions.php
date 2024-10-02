<?php

function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /auth/login.php");
        exit();
    }
}


function authenticate_user($pdo, $username, $password) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }

    return false;
}

function get_user_data($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}


function get_employee_statistics($pdo, $employee_id, $start_date, $end_date) {
    $stmt = $pdo->prepare("SELECT 
                               SUM(s.units_sold) as total_sales,
                               SUM(s.reservations) as total_reservations,
                               COUNT(DISTINCT v.id) as total_visits,
                               COUNT(DISTINCT c.id) as total_communications
                           FROM users u
                           LEFT JOIN sales s ON u.id = s.employee_id
                           LEFT JOIN visits v ON u.id = v.employee_id
                           LEFT JOIN communications c ON u.id = c.employee_id
                           WHERE u.id = ? AND (s.sale_date BETWEEN ? AND ? OR v.visit_date BETWEEN ? AND ? OR c.communication_date BETWEEN ? AND ?)");
    $stmt->execute([$employee_id, $start_date, $end_date, $start_date, $end_date, $start_date, $end_date]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_recent_activities($pdo, $page = 1, $per_page = 20, $filters = []) {
    $offset = ($page - 1) * $per_page;
    
    $sql = "SELECT * FROM communication WHERE 1=1 ";
    $params = [];
    
    if (!empty($filters['employee_id'])) {
        $sql .= "AND employee_id = :employee_id ";
        $params[':employee_id'] = $filters['employee_id'];
    }
    
    if (!empty($filters['start_date'])) {
        $sql .= "AND communication_date >= :start_date ";
        $params[':start_date'] = $filters['start_date'] . ' 00:00:00';
    }
    
    if (!empty($filters['end_date'])) {
        $sql .= "AND communication_date <= :end_date ";
        $params[':end_date'] = $filters['end_date'] . ' 23:59:59';
    }
    
    $sql .= "ORDER BY communication_date DESC LIMIT :offset, :per_page";
    
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':per_page', $per_page, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_total_activities_count($pdo, $filters = []) {
    $sql = "SELECT COUNT(*) FROM communication WHERE 1=1 ";
    $params = [];
    
    if (!empty($filters['employee_id'])) {
        $sql .= "AND employee_id = :employee_id ";
        $params[':employee_id'] = $filters['employee_id'];
    }
    
    if (!empty($filters['start_date'])) {
        $sql .= "AND communication_date >= :start_date ";
        $params[':start_date'] = $filters['start_date'] . ' 00:00:00';
    }
    
    if (!empty($filters['end_date'])) {
        $sql .= "AND communication_date <= :end_date ";
        $params[':end_date'] = $filters['end_date'] . ' 23:59:59';
    }
    
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    
    return $stmt->fetchColumn();
}

// الحصول على الأنشطة مع تطبيق التصفية والترقيم
function get_recent_visits($pdo, $page = 1, $per_page = 20, $filters = [], $employee_id) {
    $offset = ($page - 1) * $per_page;
    
    $sql = "SELECT v.*, u.full_name as employee_name, p.name as project_name 
            FROM visits v 
            JOIN users u ON v.employee_id = u.id 
            LEFT JOIN projects p ON v.project_id = p.id 
            WHERE 1=1 ";
    
    $params = [];
    
    if (!empty($employee_id)) {
        $sql .= "AND v.employee_id = :employee_id ";
        $params[':employee_id'] = $employee_id;
    }
    
    if (!empty($filters['project_id'])) {
        $sql .= "AND v.project_id = :project_id ";
        $params[':project_id'] = $filters['project_id'];
    }
    
    if (!empty($filters['start_date'])) {
        $sql .= "AND v.visit_date >= :start_date ";
        $params[':start_date'] = $filters['start_date'];
    }
    
    if (!empty($filters['end_date'])) {
        $sql .= "AND v.visit_date <= :end_date ";
        $params[':end_date'] = $filters['end_date'];
    }
    
    $sql .= "ORDER BY v.visit_date DESC LIMIT :offset, :per_page";
    
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':per_page', $per_page, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_total_visits_count($pdo, $filters = []) {
    $sql = "SELECT COUNT(*) FROM visits v WHERE 1=1 ";
    
    $params = [];
    
    if (!empty($filters['employee_id'])) {
        $sql .= "AND v.employee_id = :employee_id ";
        $params[':employee_id'] = $filters['employee_id'];
    }
    
    if (!empty($filters['project_id'])) {
        $sql .= "AND v.project_id = :project_id ";
        $params[':project_id'] = $filters['project_id'];
    }
    
    if (!empty($filters['start_date'])) {
        $sql .= "AND v.visit_date >= :start_date ";
        $params[':start_date'] = $filters['start_date'];
    }
    
    if (!empty($filters['end_date'])) {
        $sql .= "AND v.visit_date <= :end_date ";
        $params[':end_date'] = $filters['end_date'];
    }
    
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    
    return $stmt->fetchColumn();
}


// الحصول على عدد العملاء المحتملين للموظف المحدد
function get_total_potential_clients_count($pdo, $employee_id = null) {
    $sql = "SELECT COUNT(*) FROM potential_clients";
    
    if ($employee_id) {
        $sql .= " WHERE assigned_to = :employee_id"; // assuming 'assigned_to' is the column that links clients to employees
    }
    
    $stmt = $pdo->prepare($sql);
    
    if ($employee_id) {
        $stmt->bindValue(':employee_id', $employee_id, PDO::PARAM_INT);
    }
    
    $stmt->execute();
    return $stmt->fetchColumn();
}

function get_total_customers_count($pdo, $employee_id) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM customers WHERE assigned_to = ?");
    $stmt->execute([$employee_id]);
    return $stmt->fetchColumn();
}

function get_client_name($pdo, $client_id) {
    $stmt = $pdo->prepare("SELECT name FROM potential_clients WHERE id = ?");
    $stmt->execute([$client_id]);
    return $stmt->fetchColumn();
}