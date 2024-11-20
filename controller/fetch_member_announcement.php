<?php
session_start();
require_once '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $projectId = $_GET['project_id'] ?? null;
    $userId = $_SESSION['user_id'] ?? null; // Logged-in user's ID

    if (!$projectId) {
        echo json_encode(['success' => false, 'message' => 'Project ID is required.']);
        exit;
    }

    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'User is not logged in.']);
        exit;
    }

    try {
        // Fetch all announcements for the project along with user details
        $stmt = $conn->prepare("
            SELECT 
                a.announcement_id, 
                a.title, 
                a.critical_message, 
                a.upload_date, 
                a.project_id, 
                a.user_id,
                u.name AS user_name,
                u.profile_picture
            FROM 
                announcement a
            INNER JOIN 
                users u ON a.user_id = u.user_id
            WHERE 
                a.project_id = ?
        ");
        $stmt->bind_param('i', $projectId);
        $stmt->execute();
        $announcementResult = $stmt->get_result();

        $announcements = [];
        while ($announcement = $announcementResult->fetch_assoc()) {
            // Fetch files for each announcement
            $stmtFiles = $conn->prepare("SELECT file_id, file_name, file_path FROM announcement_file WHERE announcement_id = ?");
            $stmtFiles->bind_param('i', $announcement['announcement_id']);
            $stmtFiles->execute();
            $filesResult = $stmtFiles->get_result();

            $files = [];
            while ($fileRow = $filesResult->fetch_assoc()) {
                $files[] = $fileRow;
            }

            // Fetch comments for each announcement
            $stmtComments = $conn->prepare("
                SELECT 
                    c.comment_id, 
                    c.content AS comment_text, 
                    c.created_date, 
                    u.name AS commenter_name, 
                    u.profile_picture AS commenter_picture 
                FROM 
                    comments c 
                INNER JOIN 
                    users u ON c.user_id = u.user_id 
                WHERE 
                    c.announcement_id = ? 
                ORDER BY 
                    c.created_date ASC
            ");
            $stmtComments->bind_param('i', $announcement['announcement_id']);
            $stmtComments->execute();
            $commentsResult = $stmtComments->get_result();

            $comments = [];
            while ($commentRow = $commentsResult->fetch_assoc()) {
                $comments[] = $commentRow;
            }

            // Merge files and comments into the announcement
            $announcementWithDetails = array_merge($announcement, [
                'files' => $files,
                'comments' => $comments
            ]);

            $announcements[] = $announcementWithDetails;
        }

        // Fetch logged-in user's details
        $stmtUser = $conn->prepare("SELECT name, profile_picture FROM users WHERE user_id = ?");
        $stmtUser->bind_param('i', $userId);
        $stmtUser->execute();
        $userResult = $stmtUser->get_result();
        $user = $userResult->fetch_assoc();

        echo json_encode([
            'success' => true,
            'announcements' => $announcements,
            'logged_in_user' => $user, // Add logged-in user's data to the response
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error fetching announcements: ' . $e->getMessage()]);
    }
}


?>
