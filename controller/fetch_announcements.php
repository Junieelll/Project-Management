<?php
session_start();
require_once '../config/conn.php';

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $projectId = $_GET['project_id'] ?? null;

    if (!$projectId) {
        echo json_encode(['success' => false, 'message' => 'Project ID is required.']);
        exit;
    }

    try {
        $stmtUser = $conn->prepare("SELECT name, profile_picture FROM users WHERE user_id = ?");
        $stmtUser->bind_param('i', $userId);
        $stmtUser->execute();
        $userResult = $stmtUser->get_result();

        if ($userResult->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'User not found.']);
            exit;
        }

        $user = $userResult->fetch_assoc();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error fetching user details: ' . $e->getMessage()]);
        exit;
    }

    $announcements = [];
    try {
        $stmt = $conn->prepare("
            SELECT a.announcement_id, a.title, a.critical_message, a.upload_date,
                   af.file_id, af.file_name, af.file_path
            FROM announcement a
            LEFT JOIN announcement_file af ON a.announcement_id = af.announcement_id
            WHERE a.project_id = ?
            ORDER BY a.upload_date DESC
        ");
        $stmt->bind_param('i', $projectId);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $announcementId = $row['announcement_id'];
        
            if (!isset($announcements[$announcementId])) {
                $announcements[$announcementId] = [
                    'announcement_id' => $announcementId,
                    'title' => $row['title'],
                    'critical_message' => $row['critical_message'],
                    'upload_date' => $row['upload_date'],
                    'files' => [],
                    'name' => $user['name'], 
                    'profile_picture' => $user['profile_picture']
                ];
            }
        
            if ($row['file_name']) {
                $announcements[$announcementId]['files'][] = [
                    'file_id' => $row['file_id'],
                    'file_name' => $row['file_name'],
                    'file_path' => $row['file_path']
                ];
            }
        }

        echo json_encode([
            'success' => true,
            'user' => [
                'name' => $user['name'],
                'profile_picture' => $user['profile_picture']
            ],
            'data' => array_values($announcements)
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error fetching announcements: ' . $e->getMessage()]);
    }
}
?>
