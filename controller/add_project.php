<?php
session_start();
include '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $projectManager = $_SESSION['user_id']; 
    $projectName = $input['project_name'];
    $description = $input['description'];
    $dueDate = $input['due_date'];
    $createdDate = date('Y-m-d H:i:s');
    $members = $input['members']; 

    $query = "INSERT INTO project (project_manager, project_name, description, created_date, due_date, status) 
              VALUES (?, ?, ?, ?, ?, 'In-Progress')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $projectManager, $projectName, $description, $createdDate, $dueDate);

    if ($stmt->execute()) {
        $projectId = $stmt->insert_id; 

        $memberQuery = "INSERT INTO project_members (project_id, user_id) VALUES (?, ?)";
        $memberStmt = $conn->prepare($memberQuery);

        foreach ($members as $memberId) {
            $memberStmt->bind_param("is", $projectId, $memberId);
            $memberStmt->execute();
        }

        echo json_encode(['success' => true, 'message' => 'Project created successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create project.']);
    }

    $stmt->close();
    $conn->close();
}
?>
