<?php
session_start();
require_once '../config/conn.php';

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $announcementId = $_GET['announcement_id'] ?? null;

    if (!$announcementId) {
        echo json_encode(['success' => false, 'message' => 'Announcement ID is required.']);
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

        // announcement details
        $stmt = $conn->prepare("
            SELECT a.announcement_id, a.title, a.critical_message, a.upload_date, a.project_id
            FROM announcement a
            WHERE a.announcement_id = ?
        ");
        $stmt->bind_param('i', $announcementId);
        $stmt->execute();
        $announcementResult = $stmt->get_result();

        $announcementDetails = $announcementResult->fetch_assoc();

        if (!$announcementDetails) {
            echo json_encode(['success' => false, 'message' => 'Announcement not found.']);
            exit;
        }

        // files
        $stmtFiles = $conn->prepare("
            SELECT file_id, file_name, file_path
            FROM announcement_file
            WHERE announcement_id = ?
        ");
        $stmtFiles->bind_param('i', $announcementId);
        $stmtFiles->execute();
        $filesResult = $stmtFiles->get_result();

        $files = [];
        while ($fileRow = $filesResult->fetch_assoc()) {
            $files[] = $fileRow;
        }

        //  comments
        $stmtComments = $conn->prepare("
            SELECT c.comment_id, c.content AS comment_text, c.created_date,
                   u.name AS commenter_name, u.profile_picture AS commenter_picture
            FROM comments c
            INNER JOIN users u ON c.user_id = u.user_id
            WHERE c.announcement_id = ?
            ORDER BY c.created_date ASC
        ");
        $stmtComments->bind_param('i', $announcementId);
        $stmtComments->execute();
        $commentsResult = $stmtComments->get_result();

        $comments = [];
        while ($commentRow = $commentsResult->fetch_assoc()) {
            $comments[] = $commentRow;
        }

        echo json_encode([
            'success' => true,
            'announcement' => array_merge($announcementDetails, [
                'user_name' => $user['name'],
                'profile_picture' => $user['profile_picture'],
                'files' => $files,
                'comments' => $comments
            ])
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error fetching announcement details: ' . $e->getMessage()]);
    }
}
