<?php
session_start();
include '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['project_id'])) {
    $projectId = $_GET['project_id'];
    $userId = $_SESSION['user_id']; 

    $query = "SELECT project_manager FROM projects WHERE project_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $projectId);

    if ($stmt->execute()) {
        $stmt->bind_result($projectManager);
        $stmt->fetch();

        $isManager = ($projectManager === $userId);

        echo json_encode(['success' => true, 'is_manager' => $isManager]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to determine user role.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
