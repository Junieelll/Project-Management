<?php
header('Content-Type: application/json');

require_once '../config/conn.php'; 

$data = json_decode(file_get_contents('php://input'), true);
$announcementId = $data['id'];

if (!$announcementId) {
    echo json_encode(['success' => false, 'message' => 'No announcement ID provided']);
    exit;
}

$sql = "DELETE FROM announcement WHERE announcement_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $announcementId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete announcement']);
}

$stmt->close();
$conn->close();
?>
