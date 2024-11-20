<?php
header('Content-Type: application/json');

include '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['file_id'])) {
        $fileId = $input['file_id'];

        // Delete file from the database
        $stmt = $conn->prepare("DELETE FROM announcement_file WHERE file_id = ?");
        $stmt->bind_param("i", $fileId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete the file.']);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'File ID is missing.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
