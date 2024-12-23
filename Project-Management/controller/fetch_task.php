<?php
include '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Fetch all tasks along with the assigned user's name and profile picture
    $query = "
        SELECT 
            task.*,
            users.name AS assigned_user_name,
            users.profile_picture AS assigned_user_picture
        FROM task
        LEFT JOIN users ON task.assigned_user = users.user_id
    ";
    
    $stmt = $conn->prepare($query);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $tasks = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['success' => true, 'tasks' => $tasks]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch tasks.']);
    }

    $stmt->close();
    $conn->close();
}
?>
