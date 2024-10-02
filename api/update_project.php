<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = update_project($pdo, $_POST);
    echo $result ? 'success' : 'error';
} else {
    echo 'error';
}