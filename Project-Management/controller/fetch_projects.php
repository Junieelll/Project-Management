<?php
session_start();
include '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $userId = $_SESSION['user_id']; 

    // Fetch projects where the user is either the project manager or a member
    $query = "
        SELECT DISTINCT p.project_id, p.project_name, p.description, p.created_date, 
                        p.project_manager, 
                        CASE 
                            WHEN p.project_manager = ? THEN 'Manager'
                            ELSE 'Member'
                        END AS user_role
        FROM projects p
        LEFT JOIN project_members pm ON p.project_id = pm.project_id
        WHERE p.project_manager = ? OR pm.user_id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $userId, $userId, $userId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $projects = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['success' => true, 'projects' => $projects]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch projects.']);
    }

    $stmt->close();
    $conn->close();
}
?>
