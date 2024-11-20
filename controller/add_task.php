<?php

require_once '../config/conn.php';

// Set content type to JSON
header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (
        empty($data['task_title']) ||
        empty($data['status']) ||
        empty($data['project_id'])
    ) {
        echo json_encode(['success' => false, 'message' => 'Required fields are missing.']);
        exit;
    }

    $task_title = htmlspecialchars($data['task_title']);
    $note = htmlspecialchars($data['note'] ?? '');
    $due_date = !empty($data['due_date']) ? htmlspecialchars($data['due_date']) : null;
    $assigned_user = !empty($data['assigned_user']) ? htmlspecialchars($data['assigned_user']) : null;
    $priority = !empty($data['priority']) ? htmlspecialchars($data['priority']) : 'Low';
    $status = htmlspecialchars($data['status']);
    $file = !empty($data['file']) ? htmlspecialchars($data['file']) : null;
    $created_date = date('Y-m-d H:i:s'); // Current timestamp
    $project_id = htmlspecialchars($data['project_id']);
    $task_group = !empty($data['task_group']) ? htmlspecialchars($data['task_group']) : null;

    // Prepare SQL query
    $stmt = $conn->prepare("
        INSERT INTO task (task_title, note, due_date, assigned_user, priority, status, file, created_date, project_id, task_group)
        VALUES (:task_title, :note, :due_date, :assigned_user, :priority, :status, :file, :created_date, :project_id, :task_group)
    ");

    // Bind parameters
    $stmt->bindParam(':task_title', $task_title);
    $stmt->bindParam(':note', $note);
    $stmt->bindParam(':due_date', $due_date);
    $stmt->bindParam(':assigned_user', $assigned_user);
    $stmt->bindParam(':priority', $priority);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':file', $file);
    $stmt->bindParam(':created_date', $created_date);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->bindParam(':task_group', $task_group);

    // Execute query
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Task added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add task.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
