<?php
require_once '../config/conn.php';

date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskTitle = $_POST['task_title'] ?? '';
    $note = $_POST['note'] ?? '';
    $dueDate = $_POST['due_date'] ?? '';
    $assignedUser = $_POST['assigned_user'] ?? '';
    $priority = $_POST['priority'] ?? '';
    $status = $_POST['status'] ?? '';
    $taskGroup = $_POST['task_group'] ?? '';
    $projectId = $_POST['project_id'] ?? null;
    $uploadDate = date('Y-m-d H:i:s');

    if (empty($taskTitle) || empty($dueDate) || empty($assignedUser) || empty($priority) || empty($status) || empty($taskGroup) || empty($projectId)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare(
            "INSERT INTO task (task_title, note, due_date, assigned_user, priority, status, task_group, project_id, created_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('sssssssis', $taskTitle, $note, $dueDate, $assignedUser, $priority, $status, $taskGroup, $projectId, $uploadDate);
        $stmt->execute();
        $taskId = $stmt->insert_id;

        // Set attachments to null as no files should be added
        $attachments = null;

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Task added successfully!']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error adding task: ' . $e->getMessage()]);
    }
}
?>
