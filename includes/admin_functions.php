<?php

/**
 * Admin folder
 * team page
 */

 function edit_employee($pdo, $id, $data) {
    $sql = "UPDATE users SET 
            full_name = :full_name, 
            email = :email, 
            phone = :phone, 
            role = :role, 
            project_id = :project_id, 
            daily_call_target = :daily_call_target, 
            monthly_call_target = :monthly_call_target, 
            monthly_sales_target = :monthly_sales_target, 
            monthly_visit_target = :monthly_visit_target 
            WHERE id = 7";
    
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':full_name' => $data['full_name'],
        ':email' => $data['email'],
        ':phone' => $data['phone'],
        ':role' => $data['role'],
        ':project_id' => $data['project_id'] ?: null,
        ':daily_call_target' => $data['daily_call_target'],
        ':monthly_call_target' => $data['monthly_call_target'],
        ':monthly_sales_target' => $data['monthly_sales_target'],
        ':monthly_visit_target' => $data['monthly_visit_target'],
        ':id' => $data['id']
    ]);
}

function delete_employee($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
}


/**
 * Projects
 * 
 */

 function get_all_projects($pdo) {
    $stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function add_project($pdo, $data) {
    $stmt = $pdo->prepare("INSERT INTO projects (name, city, total_units, design_count, logo) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$data['name'], $data['city'], $data['total_units'], $data['design_count'], $data['logo']]);
}

function edit_project($pdo, $data) {
    $stmt = $pdo->prepare("UPDATE projects SET name = ?, city = ? WHERE id = ?");
    $stmt->execute([$data['name'], $data['city'], $data['id']]);
}

// function edit_project($pdo, $data) {
//     $sql = "UPDATE projects SET 
//             name = :name, 
//             city = :city, 
//             logo = :logo, 
//             total_units = :total_units, 
//             sold_units = :sold_units, 
//             remaining_units = :remaining_units, 
//             design_count = :design_count, 
//             create_at = :create_at, 
//             description = :description 
//             start_date = :start_date 
//             end_date = :end_date 
//             status = :status 
//             WHERE id = :id";
    
//     $stmt = $pdo->prepare($sql);
//     return $stmt->execute([
//         ':name' => $data['name'],
//         ':city' => $data['city'],
//         ':logo' => $data['logo'],
//         ':total_units' => $data['total_units'],
//         ':sold_units' => $data['sold_units'],
//         ':remaining_units' => $data['remaining_units'],
//         ':design_count' => $data['design_count'],
//         ':create_at' => $data['create_at'],
//         ':description' => $data['description'],
//         ':start_date' => $data['start_date'],
//         ':end_date' => $data['end_date'],
//         ':status' => $data['status'],
//         ':id' => $data['id']
//     ]);
// }

function delete_project($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$id]);
}