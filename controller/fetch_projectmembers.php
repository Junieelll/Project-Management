<?php
// fetch_projectmembers.php
session_start();
include '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure search term is provided
    if (empty($_POST['search'])) {
        echo json_encode(['error' => 'Search term is not provided.']);
        exit;
    }

    $search = $_POST['search']; // Access search term from POST

    // Validate the search term (email)
    if (!filter_var($search, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([]); // Return an empty array if email format is invalid
        exit;
    }

    // Ensure user is logged in by checking session
    $currentUserId = $_SESSION['user_id'] ?? null;
    if (!$currentUserId) {
        echo json_encode(['error' => 'User not logged in.']);
        exit;
    }

    // Ensure project_id is provided
    if (empty($_POST['project_id'])) {
        echo json_encode(['error' => 'Project ID is not provided.']);
        exit;
    }

    $projectId = $_POST['project_id']; // Access the project ID from POST

    // Query to get users who are part of the given project and match the search term
    $query = "
        SELECT u.user_id, u.name, u.email, u.profile_picture
        FROM users u
        JOIN project_members pm ON u.user_id = pm.user_id
        WHERE pm.project_id = ? AND u.email LIKE ?
    ";

    $stmt = $conn->prepare($query);
    $likeSearch = '%' . $search . '%'; // Applying the LIKE condition to search term
    $stmt->bind_param("ss", $projectId, $likeSearch);

    // Execute the query and fetch the results
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $users = [];

        // Fetch all the users who match the search criteria and are part of the project
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        echo json_encode($users);
    } else {
        echo json_encode(['error' => 'Failed to fetch users.']);
    }

    $stmt->close();
    $conn->close();
}
?>
