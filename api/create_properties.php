<?php
   require_once '../includes/functions.php';
   require_once '../config/database.php';

   session_start();
   if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
       http_response_code(403);
       exit(json_encode(['error' => 'Forbidden']));
   }

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $project_id = $_POST['project_id'];
       $property_types = get_property_types_by_project($pdo, $project_id);

       foreach ($property_types as $type) {
           for ($i = 0; $i < $type['quantity']; $i++) {
               $serial_number = sprintf('%04d', rand(0, 9999)); // Generate a 4-digit serial number
               $status = 'متاح'; // Default status
               $readiness = 'لم يتم البدء'; // Default readiness

               // Insert into properties table
               $stmt = $pdo->prepare("INSERT INTO properties (project_id, property_type_id, serial_number, price, status, readiness) VALUES (?, ?, ?, ?, ?, ?)");
               $stmt->execute([$project_id, $type['id'], $serial_number, 0, $status, $readiness]); // Assuming price is 0 for now
           }
       }

       echo json_encode(['success' => true]);
   } else {
       http_response_code(405);
       echo json_encode(['error' => 'Method Not Allowed']);
   }