<?php
include '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the task ID from the request
    $data = json_decode(file_get_contents('php://input'), true);
    $task_id = $data['task_id'];

    // Prepare the SQL query to delete the task
    $query = "DELETE FROM task WHERE task_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $task_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Task deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete the task.']);
    }

    $stmt->close();
    $conn->close();
}
?>
