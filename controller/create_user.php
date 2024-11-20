<?php
session_start(); 
include '../config/conn.php'; 

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['userId'], $data['name'], $data['email'], $data['profilePicture'])) {
    $userId = $data['userId'];
    $name = $data['name'];
    $email = $data['email'];
    $profilePicture = $data['profilePicture'];

    $sql = "INSERT INTO users (user_id, name, email, profile_picture) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$userId, $name, $email, $profilePicture])) {
        $_SESSION['user_id'] = $userId;
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
}
?>
