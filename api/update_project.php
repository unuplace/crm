<<<<<<< HEAD
<<<<<<< HEAD
<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = update_project($pdo, $_POST);
    echo $result ? 'success' : 'error';
} else {
    echo 'error';
=======
<?php
require_once '../includes/admin_functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit(json_encode(['success' => false, 'message' => 'Forbidden']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = edit_project($pdo, $_POST);
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update project']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
>>>>>>> 294e1ee0d7e1c090a8e15fbd9d5b7b0df47c69d8
=======
<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = update_project($pdo, $_POST);
    echo $result ? 'success' : 'error';
} else {
    echo 'error';
>>>>>>> e1678e952034126bff7fe0de6ce40fc5897e8c6c
}