<?php
include '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    $tasks = $data['tasks'];

    // Prepare the SQL query for bulk updating
    $query = "UPDATE task SET status = ? WHERE task_id = ?";
    $stmt = $conn->prepare($query);

    // Begin transaction for bulk update
    $conn->begin_transaction();

    try {
        foreach ($tasks as $task) {
            $stmt->bind_param('ss', $task['newStatus'], $task['taskId']);
            $stmt->execute();
        }
        $conn->commit(); // Commit the transaction if all updates succeed
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $conn->rollback(); // Rollback the transaction in case of an error
        echo json_encode(['success' => false, 'message' => 'Failed to update task statuses']);
    }

    $stmt->close();
    $conn->close();
}
?>
