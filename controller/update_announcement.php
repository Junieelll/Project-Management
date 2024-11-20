<?php
require_once '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $announcement_id = $_POST['announcement_id'];
    $title = $_POST['title'];
    $message = $_POST['message'];

    $query = "UPDATE announcement SET title = ?, critical_message = ? WHERE announcement_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $title, $message, $announcement_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Announcement updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update announcement.']);
    }

    $stmt->close();
}
?>
