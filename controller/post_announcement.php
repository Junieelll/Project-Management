<?php
session_start();
require_once '../config/conn.php';

$userid =  $_SESSION['user_id'];

date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $message = $_POST['message'] ?? '';
    $projectId = $_POST['project_id'] ?? null;
    $uploadDate = date('Y-m-d H:i:s');

    if (empty($title) || empty($message) || empty($projectId)) {
        echo json_encode(['success' => false, 'message' => 'All field are required.']);
        exit;
    }

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("INSERT INTO announcement (title, critical_message, upload_date, project_id, user_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssis', $title, $message, $uploadDate, $projectId, $userid);
        $stmt->execute();
        $announcementId = $stmt->insert_id;

        if (!empty($_FILES['files'])) {
            foreach ($_FILES['files']['tmp_name'] as $index => $tmpName) {
                $fileName = $_FILES['files']['name'][$index];
                $filePath = "/PROJECT-MANAGEMENT/uploads/announcement_file/" . uniqid() . "_" . basename($fileName);

                if (move_uploaded_file($tmpName, $_SERVER['DOCUMENT_ROOT'] . $filePath)) {
                    $stmt = $conn->prepare("INSERT INTO announcement_file (announcement_id, file_name, file_path, upload_date) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param('isss', $announcementId, $fileName, $filePath, $uploadDate);
                    $stmt->execute();
                }
            }
        }

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Announcement posted successfully!']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error posting announcement: ' . $e->getMessage()]);
    }
}
?>
