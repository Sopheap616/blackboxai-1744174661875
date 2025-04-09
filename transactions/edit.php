<?php
require_once '../../config/database.php';
session_start();

header('Content-Type: application/json');

// Validate user session and input
if (!isset($_SESSION['user_id']) || empty($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    $conn = getDBConnection();
    
    // Get transaction details
    $stmt = $conn->prepare("SELECT t.*, c.type AS category_type 
                          FROM transactions t
                          JOIN categories c ON t.category_id = c.id
                          WHERE t.id = ? AND t.user_id = ?");
    $stmt->bind_param("ii", $_GET['id'], $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Transaction not found']);
        exit;
    }
    
    $transaction = $result->fetch_assoc();
    echo json_encode(['success' => true, 'data' => $transaction]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
