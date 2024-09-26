<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

$clients = get_all_potential_clients($pdo);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="potential_clients.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Name', 'Phone', 'Email', 'Status']);

foreach ($clients as $client) {
    fputcsv($output, [$client['name'], $client['phone'], $client['email'], $client['status']]);
}

fclose($output);