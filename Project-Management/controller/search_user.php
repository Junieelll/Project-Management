<?php
include '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the search key exists
    if (!isset($_POST['search'])) {
        echo json_encode(['error' => 'Search term is not provided.']);
        exit;
    }

    $search = $_POST['search'];

    $query = "SELECT user_id, email FROM users WHERE email LIKE ? LIMIT 10";
    $stmt = $conn->prepare($query);
    $likeSearch = '%' . $search . '%';
    $stmt->bind_param("s", $likeSearch);
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    echo json_encode($users);
}
?>
