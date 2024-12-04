<?php
require_once '../config/conn.php'; 

if (!isset($_GET['project_id'])) {
    echo json_encode(['success' => false, 'message' => 'Project ID is required.']);
    exit;
}

$project_id = $_GET['project_id'];

// fetch up to 10 recent task files from the last 7 days.
$query = "
    SELECT 
        t.task_title, 
        t.due_date, 
        t.priority, 
        u.name AS assigned_user_name, 
        t.status, 
        t.created_date AS date_submitted, 
        tf.file_name 
    FROM task t
    INNER JOIN task_file tf ON t.task_id = tf.task_id
    INNER JOIN users u ON t.assigned_user = u.user_id
    WHERE t.project_id = ? 
      AND tf.date_added >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    ORDER BY tf.date_added DESC
    LIMIT 10
";


$stmt = $conn->prepare($query);
$stmt->bind_param("i", $project_id);
$stmt->execute();
$result = $stmt->get_result();

$files = [];
while ($row = $result->fetch_assoc()) {
    $files[] = $row;
}

echo json_encode(['success' => true, 'data' => $files]);
?>
