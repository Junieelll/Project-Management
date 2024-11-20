<?php
session_start();
header('Content-Type: application/json');
include '../config/conn.php'; 

date_default_timezone_set('Asia/Manila');

$user_id = $_SESSION['user_id']; 

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['content'], $data['announcement_id'])) {
    $content = $data['content'];
    $announcement_id = $data['announcement_id'];
    $created_date = date('Y-m-d H:i:s'); 

    $stmt = $conn->prepare("INSERT INTO comments (content, user_id, announcement_id, created_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssis', $content, $user_id, $announcement_id, $created_date);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database insert failed']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data received']);
}

$conn->close();
?>
