<?php
session_start(); 
include '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['search'])) {
        echo json_encode(['error' => 'Search term is not provided.']);
        exit;
    }

    $search = $_POST['search'];

    if (!filter_var($search, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([]);
        exit;
    }

    $currentUserId = $_SESSION['user_id'] ?? null;

    if (!$currentUserId) {
        echo json_encode(['error' => 'User not logged in.']);
        exit;
    }

    $query = "SELECT user_id, email FROM users WHERE email = ? AND user_id != ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $search, $currentUserId);
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    echo json_encode($users);
}
?>
