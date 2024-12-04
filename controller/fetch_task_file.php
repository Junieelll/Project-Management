<?php
session_start();
include '../config/conn.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Check if project_id is provided
if (!isset($_GET['project_id'])) {
    echo json_encode(['success' => false, 'message' => 'Project ID is required']);
    exit();
}

$projectId = $_GET['project_id'];

try {
    // Fetch task files, assigned user details, and task details
    $query = "
        SELECT 
            tf.file_name, 
            tf.upload_path, 
            tf.date_added, 
            t.task_id, 
            t.task_title, 
            u.name AS assigned_user_name, 
            u.profile_picture AS assigned_user_picture
        FROM 
            task_file tf
        INNER JOIN 
            task t ON tf.task_id = t.task_id
        INNER JOIN 
            users u ON t.assigned_user = u.user_id
        WHERE 
            t.project_id = ?
        ORDER BY 
            tf.date_added DESC
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $projectId);
    $stmt->execute();
    $result = $stmt->get_result();

    $taskFiles = [];
    while ($row = $result->fetch_assoc()) {
        $taskFiles[] = [
            'file_name' => $row['file_name'],
            'upload_path' => $row['upload_path'],
            'date_added' => $row['date_added'],
            'task_id' => $row['task_id'],
            'task_title' => $row['task_title'],
            'assigned_user_name' => $row['assigned_user_name'],
            'assigned_user_picture' => $row['assigned_user_picture']
        ];
    }

    echo json_encode(['success' => true, 'task_files' => $taskFiles]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error fetching task files: ' . $e->getMessage()]);
}
?>
