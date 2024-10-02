<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

if (isset($_GET['id'])) {
    $project_id = $_GET['id'];
    $project = get_project_by_id($pdo, $project_id);
    echo json_encode($project);
} else {
    echo json_encode(['error' => 'No project ID provided']);
}