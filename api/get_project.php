<<<<<<< HEAD
<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

if (isset($_GET['id'])) {
    $project_id = $_GET['id'];
    $project = get_project_by_id($pdo, $project_id);
    echo json_encode($project);
} else {
    echo json_encode(['error' => 'No project ID provided']);
=======
<?php
require_once '../includes/admin_functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit('Forbidden');
}

if (isset($_GET['id'])) {
    $project_id = $_GET['id'];
    $project = get_project_data($pdo, $project_id);
    
    if ($project) {
        echo json_encode($project);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'project not found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Missing project ID']);
}

function get_project_data($pdo, $project_id) {
    $stmt = $pdo->prepare("SELECT id, name, city, logo, total_units, sold_units, remaining_units, design_count, created_at, description, start_date, end_date, status FROM projects WHERE id = ?");
    $stmt->execute([$project_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
>>>>>>> 294e1ee0d7e1c090a8e15fbd9d5b7b0df47c69d8
}