<?php
session_start();
include '../config/conn.php'; 

$requestPayload = file_get_contents("php://input");
$data = json_decode($requestPayload, true);

$userId = $data['userId']; 

$query = "SELECT user_id FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $userId);
$stmt->execute();
$stmt->store_result();

$response = [];

if ($stmt->num_rows > 0) {
    $response['exists'] = true;
    $_SESSION['user_id'] = $userId; 
} else {
    $response['exists'] = false;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
