<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $result = delete_project($pdo, $_POST['id']);
    echo $result ? 'success' : 'error';
} else {
    echo 'error';
}