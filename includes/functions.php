<?php
// includes/functions.php

function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /auth/login.php");
        exit();
    }
}

function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
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

// function get_all_projects($pdo) {
//     $stmt = $pdo->query("SELECT * FROM projects");
//     return $stmt->fetchAll();
// }

function get_project_name($pdo, $project_id) {
    if ($project_id === null) {
        return 'غير محدد';
    }
    $stmt = $pdo->prepare("SELECT name FROM projects WHERE id = ?");
    $stmt->execute([$project_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['name'] : 'غير محدد';
}

function add_project($pdo, $data) {
    $stmt = $pdo->prepare("INSERT INTO projects (name, city, total_units, design_count, logo) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$data['name'], $data['city'], $data['total_units'], $data['design_count'], $data['logo']]);
}

function edit_project($pdo, $data) {
    $stmt = $pdo->prepare("UPDATE projects SET name = ?, city = ?, total_units = ?, design_count = ?, logo = ? WHERE id = ?");
    $stmt->execute([$data['name'], $data['city'], $data['total_units'], $data['design_count'], $data['logo'], $data['id']]);
}

function delete_project($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$id]);
}

// function get_all_employees($pdo) {
//     $stmt = $pdo->query("SELECT * FROM users WHERE role = 'employee'");
//     return $stmt->fetchAll();
// }

// function add_employee($pdo, $data) {
//     $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
//     $stmt = $pdo->prepare("INSERT INTO users (username, password, full_name, email, phone, role, project_id, daily_call_target, monthly_sales_target, monthly_visit_target) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
//     $stmt->execute([$data['username'], $hashed_password, $data['full_name'], $data['email'], $data['phone'], $data['role'], $data['project_id'], $data['daily_call_target'], $data['monthly_sales_target'], $data['monthly_visit_target']]);
// }

// function edit_employee($pdo, $data) {
//     $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, project_id = ?, daily_call_target = ?, monthly_sales_target = ?, monthly_visit_target = ? WHERE id = ?");
//     $stmt->execute([$data['full_name'], $data['email'], $data['phone'], $data['project_id'], $data['daily_call_target'], $data['monthly_sales_target'], $data['monthly_visit_target'], $data['id']]);
// }

function add_employee($pdo, $data) {
    $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, password, full_name, email, phone, role, project_id, daily_call_target, monthly_call_target, monthly_sales_target, monthly_visit_target) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['username'], $hashed_password, $data['full_name'], $data['email'], $data['phone'],
        $data['role'], $data['project_id'], $data['daily_call_target'], $data['monthly_call_target'],
        $data['monthly_sales_target'], $data['monthly_visit_target']
    ]);
}

function edit_employee($pdo, $data) {
    $sql = "UPDATE users SET 
            full_name = :full_name, 
            email = :email, 
            phone = :phone, 
            role = :role, 
            project_id = :project_id, 
            daily_call_target = :daily_call_target, 
            -- monthly_call_target = :monthly_call_target, 
            monthly_sales_target = :monthly_sales_target, 
            monthly_visit_target = :monthly_visit_target 
            WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':full_name' => $data['full_name'],
        ':email' => $data['email'],
        ':phone' => $data['phone'],
        ':role' => $data['role'],
        ':project_id' => $data['project_id'] ?: null,
        ':daily_call_target' => $data['daily_call_target'],
        // ':monthly_call_target' => $data['monthly_call_target'],
        ':monthly_sales_target' => $data['monthly_sales_target'],
        ':monthly_visit_target' => $data['monthly_visit_target'],
        ':id' => $data['id']
    ]);
}

function get_employee_progress($pdo, $employee_id, $start_date, $end_date) {
    // احسب التقدم في الأهداف
    $sql = "SELECT 
                (SELECT COUNT(*) FROM communication WHERE employee_id = ? AND communication_date BETWEEN ? AND ?) as total_calls,
                (SELECT COUNT(*) FROM sales WHERE employee_id = ? AND sale_date BETWEEN ? AND ?) as total_sales,
                (SELECT COUNT(*) FROM visits WHERE employee_id = ? AND visit_date BETWEEN ? AND ?) as total_visits,
                daily_call_target, monthly_sales_target, monthly_visit_target
            FROM users 
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$employee_id, $start_date, $end_date, $employee_id, $start_date, $end_date, $employee_id, $start_date, $end_date, $employee_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // احسب النسب المئوية للتقدم
    $days_in_period = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24) + 1;
    $call_target = $result['daily_call_target'] * $days_in_period;
    
    return [
        'call_progress' => ($result['total_calls'] / $call_target) * 100,
        'sales_progress' => ($result['total_sales'] / $result['monthly_sales_target']) * 100,
        'visit_progress' => ($result['total_visits'] / $result['monthly_visit_target']) * 100,
        'remaining_calls' => max(0, $call_target - $result['total_calls']),
        'remaining_sales' => max(0, $result['monthly_sales_target'] - $result['total_sales']),
        'remaining_visits' => max(0, $result['monthly_visit_target'] - $result['total_visits']),
        'total_calls' => $result['total_calls'],
        'total_sales' => $result['total_sales'],
        'total_visits' => $result['total_visits'],
        'call_target' => $call_target,
        'sales_target' => $result['monthly_sales_target'],
        'visit_target' => $result['monthly_visit_target']
    ];
}


function delete_employee($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
}

// function get_all_potential_clients($pdo) {
//     $stmt = $pdo->query("SELECT * FROM potential_clients");
//     return $stmt->fetchAll();
// }

// function add_potential_client($pdo, $data) {
//     $stmt = $pdo->prepare("INSERT INTO potential_clients (name, phone, salary, monthly_commitment, bank, sector, status, notes, contact_date, assigned_to) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
//     $stmt->execute([$data['name'], $data['phone'], $data['salary'], $data['monthly_commitment'], $data['bank'], $data['sector'], $data['status'], $data['notes'], $data['contact_date'], $data['assigned_to']]);
// }

// function edit_potential_client($pdo, $data) {
//     $stmt = $pdo->prepare("UPDATE potential_clients SET name = ?, phone = ?, salary = ?, monthly_commitment = ?, bank = ?, sector = ?, status = ?, notes = ?, contact_date = ?, assigned_to = ? WHERE id = ?");
//     $stmt->execute([$data['name'], $data['phone'], $data['salary'], $data['monthly_commitment'], $data['bank'], $data['sector'], $data['status'], $data['notes'], $data['contact_date'], $data['assigned_to'], $data['id']]);
// }

// function delete_potential_client($pdo, $id) {
//     $stmt = $pdo->prepare("DELETE FROM potential_clients WHERE id = ?");
//     $stmt->execute([$id]);
// }

// function add_potential_client($pdo, $data) {
//     $sql = "INSERT INTO potential_clients (name, phone, email, salary, monthly_commitment, bank, sector, status, notes, contact_date, assigned_to) 
//             VALUES (:name, :phone, :email, :salary, :monthly_commitment, :bank, :sector, :status, :notes, :contact_date, :assigned_to)";
//     $stmt = $pdo->prepare($sql);
//     return $stmt->execute([
//         ':name' => $data['name'],
//         ':phone' => $data['phone'],
//         ':email' => $data['email'],
//         ':salary' => $data['salary'],
//         ':monthly_commitment' => $data['monthly_commitment'],
//         ':bank' => $data['bank'],
//         ':sector' => $data['sector'],
//         ':status' => $data['status'],
//         ':notes' => $data['notes'],
//         ':contact_date' => $data['contact_date'],
//         ':assigned_to' => $data['assigned_to']
//     ]);
// }

function add_potential_client($pdo, $data) {
    $sql = "INSERT INTO potential_clients (name, phone, email, salary, monthly_commitment, bank, sector, status, notes, contact_date, assigned_to) 
            VALUES (:name, :phone, :email, :salary, :monthly_commitment, :bank, :sector, :status, :notes, :contact_date, :assigned_to)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':name' => $data['name'],
        ':phone' => $data['phone'],
        ':email' => $data['email'],
        ':salary' => $data['salary'] ?? null,
        ':monthly_commitment' => $data['monthly_commitment'] ?? null,
        ':bank' => $data['bank'] ?? null,
        ':sector' => $data['sector'] ?? null,
        ':status' => $data['status'] ?? 'جديد',
        ':notes' => $data['notes'] ?? null,
        ':contact_date' => $data['contact_date'] ?? date('Y-m-d'),
        ':assigned_to' => $data['assigned_to'] ?? null
    ]);
}


function update_potential_client($pdo, $data) {
    $sql = "UPDATE potential_clients SET 
            name = :name, phone = :phone, email = :email, salary = :salary, 
            monthly_commitment = :monthly_commitment, bank = :bank, sector = :sector, 
            status = :status, notes = :notes, contact_date = :contact_date, assigned_to = :assigned_to
            WHERE id = :client_id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':name' => $data['name'],
        ':phone' => $data['phone'],
        ':email' => $data['email'],
        ':salary' => $data['salary'],
        ':monthly_commitment' => $data['monthly_commitment'],
        ':bank' => $data['bank'],
        ':sector' => $data['sector'],
        ':status' => $data['status'],
        ':notes' => $data['notes'],
        ':contact_date' => $data['contact_date'],
        ':assigned_to' => $data['assigned_to'],
        ':client_id' => $data['client_id']
    ]);
}


function delete_potential_client($pdo, $client_id) {
    $sql = "DELETE FROM potential_clients WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$client_id]);
}

function get_all_potential_clients($pdo) {
    $sql = "SELECT * FROM potential_clients ORDER BY created_at DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// function get_all_employees($pdo) {
//     $sql = "SELECT id, full_name FROM users WHERE role = 'employee'";
//     $stmt = $pdo->query($sql);
//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }

function get_employee_name($pdo, $employee_id) {
    $sql = "SELECT full_name FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$employee_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['full_name'] : 'غير معين';
}


// the end of the new code

// function get_employee_name($pdo, $employee_id) {
//     $stmt = $pdo->prepare("SELECT full_name FROM users WHERE id = ?");
//     $stmt->execute([$employee_id]);
//     $result = $stmt->fetch();
//     return $result ? $result['full_name'] : 'غير معين';
// }

function get_employee_reports($pdo) {
    $sql = "SELECT u.id, u.full_name, 
                   COUNT(DISTINCT s.id) as total_sales, 
                   SUM(s.amount) as total_amount, 
                   COUNT(DISTINCT v.id) as total_visits
            FROM users u
            LEFT JOIN sales s ON u.id = s.employee_id
            LEFT JOIN visits v ON u.id = v.employee_id
            WHERE u.role = 'employee'
            GROUP BY u.id";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_employee_visits($pdo, $employee_id) {
    $stmt = $pdo->prepare("SELECT v.*, pc.name as client_name 
                           FROM visits v 
                           JOIN potential_clients pc ON v.client_id = pc.id 
                           WHERE v.employee_id = ?
                           ORDER BY v.visit_date DESC");
    $stmt->execute([$employee_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// function get_employee_potential_clients($pdo, $employee_id) {
//     $stmt = $pdo->prepare("SELECT * FROM potential_clients WHERE assigned_to = ?");
//     $stmt->execute([$employee_id]);
//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }

function import_potential_clients($pdo, $file) {
    // Implement Excel import logic here
    // You may want to use a library like PhpSpreadsheet for this
}

function export_potential_clients($pdo) {
    // Implement Excel export logic here
    // You may want to use a library like PhpSpreadsheet for this
}

function assign_potential_clients($pdo, $employee_id, $client_ids) {
    $stmt = $pdo->prepare("UPDATE potential_clients SET assigned_to = ? WHERE id IN (" . implode(',', array_fill(0, count($client_ids), '?')) . ")");
    $stmt->execute(array_merge([$employee_id], $client_ids));
}

// Add more functions as needed


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

function get_project_data($pdo, $project_id) {
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$project_id]);
    return $stmt->fetch();
}

function get_project_reports($pdo) {
    $sql = "SELECT p.id, p.name, 
                   COUNT(DISTINCT s.id) as sales, 
                   SUM(s.amount) as total_amount,
                   p.sold_units as reservations,
                   CASE 
                     WHEN p.status = 'completed' THEN 100
                     WHEN p.status = 'in_progress' THEN 50
                     WHEN p.status = 'on_hold' THEN 25
                     ELSE 0
                   END as progress,
                   (SELECT COUNT(*) FROM potential_clients WHERE status = 'interested') as remaining_target
            FROM projects p
            LEFT JOIN sales s ON p.id = s.project_id
            GROUP BY p.id";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function add_daily_stats($pdo, $employee_id, $units_sold, $reservations, $visits) {
    $date = date('Y-m-d');
    
    // إضافة المبيعات
    $stmt = $pdo->prepare("INSERT INTO sales (employee_id, sale_date, units_sold, reservations) VALUES (?, ?, ?, ?)");
    $stmt->execute([$employee_id, $date, $units_sold, $reservations]);
    
    // إضافة الزيارات
    $stmt = $pdo->prepare("INSERT INTO visits (employee_id, visit_date, count) VALUES (?, ?, ?)");
    $stmt->execute([$employee_id, $date, $visits]);
    
    return true;
}

function add_visit($pdo, $employee_id, $client_id, $visit_date, $company_name, $department, $contact_name, $contact_phone, $description, $recommendations, $notes) {
    $stmt = $pdo->prepare("INSERT INTO visits (employee_id, client_id, visit_date, company_name, department, contact_name, contact_phone, description, recommendations, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$employee_id, $client_id, $visit_date, $company_name, $department, $contact_name, $contact_phone, $description, $recommendations, $notes]);
}

function log_communication($pdo, $employee_id, $client_id, $type, $notes = '') {
    $stmt = $pdo->prepare("INSERT INTO communications (employee_id, client_id, communication_date, type, notes) VALUES (?, ?, CURDATE(), ?, ?)");
    return $stmt->execute([$employee_id, $client_id, $type, $notes]);
}

function get_employee_potential_clients($pdo, $employee_id) {
    $stmt = $pdo->prepare("SELECT * FROM potential_clients WHERE assigned_to = ? ORDER BY created_at DESC");
    $stmt->execute([$employee_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function is_client_assigned_to_employee($pdo, $client_id, $employee_id) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM potential_clients WHERE id = ? AND assigned_to = ?");
    $stmt->execute([$client_id, $employee_id]);
    return $stmt->fetchColumn() > 0;
}

function update_client_field($pdo, $client_id, $field, $value) {
    $allowed_fields = ['email', 'salary', 'monthly_commitment', 'bank', 'sector', 'status', 'notes', 'contact_date', 'assigned_to'];
    if (!in_array($field, $allowed_fields)) {
        return false;
    }
    
    $sql = "UPDATE potential_clients SET $field = :value WHERE id = :client_id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([':value' => $value, ':client_id' => $client_id]);
}

function get_all_projects($pdo) {
    $stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function update_project_field($pdo, $project_id, $field, $value) {
    $sql = "UPDATE projects SET $field = :value WHERE id = :project_id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([':value' => $value, ':project_id' => $project_id]);
}

function update_remaining_units($pdo, $project_id) {
    $sql = "UPDATE projects SET remaining_units = total_units - sold_units WHERE id = :project_id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([':project_id' => $project_id]);
}

function update_project_logo($pdo, $project_id, $logo_filename) {
    $sql = "UPDATE projects SET logo = :logo WHERE id = :project_id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([':logo' => $logo_filename, ':project_id' => $project_id]);
}

function record_communication($pdo, $employee_id, $client_id, $new_status, $notes) {
    $sql = "INSERT INTO communication (employee_id, potential_client_id, communication_date, notes, new_status) 
            VALUES (:employee_id, :client_id, NOW(), :notes, :new_status)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':employee_id' => $employee_id,
        ':client_id' => $client_id,
        ':notes' => $notes,
        ':new_status' => $new_status
    ]);
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

function get_all_employees($pdo) {
    $stmt = $pdo->query("SELECT id, username, full_name, email, phone, role, project_id, daily_call_target, monthly_sales_target, monthly_visit_target FROM users WHERE role = 'employee' ORDER BY full_name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []; // إرجاع مصفوفة فارغة إذا لم يتم العثور على نتائج
}

function get_client_name($pdo, $client_id) {
    $stmt = $pdo->prepare("SELECT name FROM potential_clients WHERE id = ?");
    $stmt->execute([$client_id]);
    return $stmt->fetchColumn();
}

function get_recent_visits($pdo, $page = 1, $per_page = 20, $filters = []) {
    $offset = ($page - 1) * $per_page;
    
    $sql = "SELECT v.*, u.full_name as employee_name, p.name as project_name 
            FROM visits v 
            JOIN users u ON v.employee_id = u.id 
            LEFT JOIN projects p ON v.project_id = p.id 
            WHERE 1=1 ";
    
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

function get_all_customers($pdo) {
    $stmt = $pdo->query("SELECT * FROM customers ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_total_customers_count($pdo, $employee_id) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM customers WHERE assigned_to = ?");
    $stmt->execute([$employee_id]);
    return $stmt->fetchColumn();
}

// function get_recent_visits($pdo, $limit = 50, $employee_id = null) {
//     $sql = "SELECT v.*, u.full_name as employee_name, p.name as project_name 
//             FROM visits v 
//             JOIN users u ON v.employee_id = u.id 
//             LEFT JOIN projects p ON v.project_id = p.id 
//             WHERE 1=1 ";
    
//     if ($employee_id) {
//         $sql .= "AND v.employee_id = :employee_id ";
//     }
    
//     $sql .= "ORDER BY v.visit_date DESC LIMIT :limit";
    
//     $stmt = $pdo->prepare($sql);
//     if ($employee_id) {
//         $stmt->bindValue(':employee_id', $employee_id, PDO::PARAM_INT);
//     }
//     $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
//     $stmt->execute();
    
//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }

// Add more functions as needed

// function get_total_potential_clients_count($pdo) {
//     $stmt = $pdo->query("SELECT COUNT(*) FROM potential_clients");
//     return $stmt->fetchColumn();
// }

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

/**
 * Admin > reports.php
 * تقارير العملاء
 */
function get_recent_customers($pdo, $page = 1, $per_page = 20, $filters = []) {
    $offset = ($page - 1) * $per_page;
    
    $sql = "SELECT * FROM customers WHERE 1=1 ";
    $params = [];
    
    if (!empty($filters['employee_id'])) {
        $sql .= "AND assigned_to = :employee_id ";
        $params[':employee_id'] = $filters['employee_id'];
    }
    
    if (!empty($filters['start_date'])) {
        $sql .= "AND created_at >= :start_date ";
        $params[':start_date'] = $filters['start_date'] . ' 00:00:00';
    }
    
    if (!empty($filters['end_date'])) {
        $sql .= "AND created_at <= :end_date ";
        $params[':end_date'] = $filters['end_date'] . ' 23:59:59';
    }
    
    $sql .= "ORDER BY created_at DESC LIMIT :offset, :per_page";
    
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':per_page', $per_page, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}