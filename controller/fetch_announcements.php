<?php
require_once '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $projectId = $_GET['project_id'] ?? null;

    if (!$projectId) {
        echo json_encode(['success' => false, 'message' => 'Project ID is required.']);
        exit;
    }

    $announcements = [];
    try {
        $stmt = $conn->prepare("
            SELECT a.announcement_id, a.title, a.critical_message, a.upload_date,
                   af.file_name, af.file_path
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
                    'files' => []
                ];
            }

            if ($row['file_name']) {
                $announcements[$announcementId]['files'][] = [
                    'file_name' => $row['file_name'],
                    'file_path' => $row['file_path']
                ];
            }
        }

        echo json_encode(['success' => true, 'data' => array_values($announcements)]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
