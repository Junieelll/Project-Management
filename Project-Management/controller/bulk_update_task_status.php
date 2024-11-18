<?php
include '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    $tasks = $data['tasks'];

    // Prepare the query to update the status
    $query = "UPDATE task SET status = ? WHERE task_id = ?";
    $stmt = $conn->prepare($query);

    // Loop through each task and update its status
    foreach ($tasks as $task) {
        $status = $task['newStatus']; // newStatus will now be Backlog, Testing, InProgress, or Finished
        $task_id = $task['taskId']; // taskId is still needed as reference to which task to update

        // Bind the parameters and execute the query
        $stmt->bind_param('ss', $status, $task_id);
        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'message' => 'Failed to update some task statuses.']);
            exit();
        }
    }

    // Return success
    echo json_encode(['success' => true]);
    $stmt->close();
    $conn->close();
}
?>
