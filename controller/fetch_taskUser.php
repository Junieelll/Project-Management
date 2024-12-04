<?php
include '../config/conn.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Fetch all tasks for a specific project along with the assigned user's name and profile picture
    $projectId = $_GET['project_id'] ?? null;
    $userid = $_SESSION['user_id'] ?? null;

    if (!$projectId || $userid) {
        echo json_encode(['success' => false, 'message' => 'Project ID and User ID is required.']);
        exit;
    }

    $query = "
        SELECT 
            task.*,
            users.name AS assigned_user_name,
            users.profile_picture AS assigned_user_picture
        FROM task
        LEFT JOIN users ON task.assigned_user = users.user_id
        WHERE task.project_id = ? AND task.assigned_user = ?
    ";

    $stmt = $conn->prepare($query);

    // Bind the project ID parameter
    $stmt->bind_param('is', $projectId, $userid);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $tasks = $result->fetch_all(MYSQLI_ASSOC);

        // Format dates for each task

        echo json_encode(['success' => true, 'tasks' => $tasks]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch tasks.']);
    }

    $stmt->close();
    $conn->close();
}

?>