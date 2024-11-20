<?php
include '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if project_id is provided
    if (!isset($_POST['project_id'])) {
        echo json_encode(['error' => 'Project ID is not provided.']);
        exit;
    }

    $projectId = $_POST['project_id'];  // Access the project ID from POST
    $search = isset($_POST['search']) ? $_POST['search'] : '';  // Access the search query from POST

    // Query to fetch project members with optional search
    $query = "
        SELECT pm.user_id, u.name, u.email, u.profile_picture
        FROM projectmembers pm
        JOIN users u ON pm.user_id = u.user_id
        WHERE pm.project_id = ? AND u.email LIKE ?
    ";
    $stmt = $conn->prepare($query);
    $likeSearch = '%' . $search . '%';
    $stmt->bind_param("ss", $projectId, $likeSearch);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $members = $result->fetch_all(MYSQLI_ASSOC);

        echo json_encode(['success' => true, 'members' => $members]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch project members.']);
    }

    $stmt->close();
    $conn->close();
}
?>
