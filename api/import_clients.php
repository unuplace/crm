<?php
require '../vendor/autoload.php';
require_once '../includes/functions.php';
require_once '../config/database.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit('Forbidden');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excelFile'])) {
    try {
        $inputFileName = $_FILES['excelFile']['tmp_name'];
        $spreadsheet = IOFactory::load($inputFileName);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Remove header row
        array_shift($rows);

        $importedCount = 0;
        foreach ($rows as $row) {
            $clientData = [
                'name' => $row[0],
                'phone' => $row[1],
                'email' => $row[2],
                'salary' => $row[3],
                'monthly_commitment' => $row[4],
                'bank' => $row[5],
                'sector' => $row[6],
                'status' => $row[7],
                'notes' => $row[8],
                'contact_date' => $row[9],
                'assigned_to' => $row[10]
            ];

            if (add_potential_client($pdo, $clientData)) {
                $importedCount++;
            }
        }

        echo json_encode(['success' => true, 'message' => "تم استيراد $importedCount عميل بنجاح"]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded or invalid request method']);
}




<!-- <?php
require_once '../config/database.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['clientsFile'])) {
    $file = $_FILES['clientsFile'];
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $result = import_clients_from_csv($pdo, $file['tmp_name']);
        
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to import clients']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'File upload error']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
} -->