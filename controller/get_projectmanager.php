<?php
include '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Fetch project manager for a specific project
    $projectId = $_GET['project_id'] ?? null;

    if (!$projectId) {
        echo json_encode(['success' => false, 'message' => 'Project ID is required.']);
        exit;
    }

    $query = "
        SELECT 
            p.*,
            u.name AS assigned_user_name,
            u.profile_picture AS assigned_user_picture,
            u.email AS assigned_user_email
        FROM projects p
        LEFT JOIN users u ON p.project_manager = u.user_id
        WHERE p.project_id = ?
    ";

    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare the query.']);
        exit;
    }

    // Bind the project ID parameter
    $stmt->bind_param('i', $projectId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $manager = $result->fetch_all(MYSQLI_ASSOC);

        echo json_encode(['success' => true, 'tasks' => $manager]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch project manager.']);
    }

    $stmt->close();
    $conn->close();
}
?>
