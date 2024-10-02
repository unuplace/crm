 <?php
// require_once '../config/database.php';
// require_once '../includes/functions.php';

// header('Content-Type: application/json');

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $name = $_POST['name'] ?? '';
//     $phone = $_POST['phone'] ?? '';
//     $email = $_POST['email'] ?? '';
//     $status = $_POST['status'] ?? '';
    

//     $result = add_potential_client($pdo, $name, $phone, $email, $status);

//     if ($result) {
//         echo json_encode(['success' => true]);
//     } else {
//         echo json_encode(['success' => false, 'message' => 'Failed to add client']);
//     }
// } else {
//     echo json_encode(['success' => false, 'message' => 'Invalid request method']);
// } 


require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit('Forbidden');
}

if (isset($_GET['id'])) {
    $client_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM potential_clients WHERE id = ?");
    $stmt->execute([$client_id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($client) {
        echo json_encode($client);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Client not found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Missing client ID']);
}