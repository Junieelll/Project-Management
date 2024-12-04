<?php
session_start();
include '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['project_id'])) {
        $project_id = $data['project_id'];

        // Delete the project from the database
        $sql = "DELETE FROM projects WHERE project_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt && $stmt->execute([$project_id])) {
            echo json_encode(["success" => true, "message" => "Project deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete the project"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid project ID"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>
