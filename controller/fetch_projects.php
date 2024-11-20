<?php
session_start();
include '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $userId = $_SESSION['user_id']; 
    
    // Fetch projects where the user is either the project manager or a member
    $query = "
        SELECT DISTINCT p.project_id, p.project_name, p.description, p.created_date, 
                        p.project_manager, p.status, p.due_date,
                        CASE 
                            WHEN p.project_manager = ? THEN 'Manager'
                            ELSE 'Member'
                        END AS user_role
        FROM project p
        LEFT JOIN projectmembers pm ON p.project_id = pm.project_id
        WHERE p.project_manager = ? OR pm.user_id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $userId, $userId, $userId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $projects = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($projects as &$project) {
            $projectId = $project['project_id'];

            $memberQuery = "
                SELECT u.user_id, u.name, u.profile_picture 
                FROM projectmembers pm
                JOIN users u ON pm.user_id = u.user_id
                WHERE pm.project_id = ?
            ";
            $memberStmt = $conn->prepare($memberQuery);
            $memberStmt->bind_param("s", $projectId);

            if ($memberStmt->execute()) {
                $memberResult = $memberStmt->get_result();
                $project['members'] = $memberResult->fetch_all(MYSQLI_ASSOC);
            } else {
                $project['members'] = [];
            }

            $memberStmt->close();
        }

        echo json_encode(['success' => true, 'projects' => $projects]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch projects.']);
    }

    $stmt->close();
    $conn->close();
}
?>
