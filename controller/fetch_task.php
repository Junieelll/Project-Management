<?php
include '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Fetch all tasks for a specific project along with the assigned user's name and profile picture
    $projectId = $_GET['project_id'] ?? null;

    if (!$projectId) {
        echo json_encode(['success' => false, 'message' => 'Project ID is required.']);
        exit;
    }

    $query = "
        SELECT 
            task.*,
            users.name AS assigned_user_name,
            users.profile_picture AS assigned_user_picture
        FROM task
        LEFT JOIN users ON task.assigned_user = users.user_id
        WHERE task.project_id = ?
    ";

    $stmt = $conn->prepare($query);

    // Bind the project ID parameter
    $stmt->bind_param('i', $projectId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $tasks = $result->fetch_all(MYSQLI_ASSOC);

        // Format dates for each task
        foreach ($tasks as &$task) {
            $task['due_date'] = formatDate($task['due_date']);
            $task['created_date'] = formatDate($task['created_date']);
        }

        echo json_encode(['success' => true, 'tasks' => $tasks]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch tasks.']);
    }

    $stmt->close();
    $conn->close();
}

function formatDate($date) {
    // Create a DateTime object from the string
    $dt = new DateTime($date);
    
    // Format it as 'Month Day, Year | Hour:Minute AM/PM'
    return $dt->format('F j, Y | g:i A');
}
?>