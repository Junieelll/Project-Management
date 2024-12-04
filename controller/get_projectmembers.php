<?php
include '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $projectId = $_GET['project_id'] ?? null;

    if (!$projectId) {
        echo json_encode(['success' => false, 'message' => 'Project ID is required.']);
        exit;
    }

    $query = "
        SELECT 
            pm.*,
            u.name AS assigned_user_name,
            u.profile_picture AS assigned_user_picture,
            u.email AS assigned_user_email,
            COUNT(t.task_id) AS task_count
        FROM project_members pm
        LEFT JOIN users u ON pm.user_id = u.user_id
        LEFT JOIN task t ON t.assigned_user = u.user_id
        WHERE pm.project_id = ?
        GROUP BY u.user_id
    ";

    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare the query.']);
        exit;
    }

    $stmt->bind_param('i', $projectId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $members = $result->fetch_all(MYSQLI_ASSOC);

        echo json_encode(['success' => true, 'tasks' => $members]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch project members.']);
    }

    $stmt->close();
    $conn->close();
}
?>
