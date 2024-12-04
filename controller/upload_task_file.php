<?php
session_start();
include '../config/conn.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Check if task ID and files are provided
if (isset($_POST['task_id']) && isset($_FILES['files'])) {
    $taskId = $_POST['task_id'];
    $uploadDir = '../uploads/task_file/'; // Relative path to your PHP script

    // Ensure the directory exists or create it
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
        echo json_encode(['success' => false, 'message' => 'Failed to create upload directory']);
        exit();
    }

    $files = $_FILES['files'];
    $uploadedFiles = [];
    $fileUploadedSuccessfully = false;

    // Loop through each uploaded file
    foreach ($files['name'] as $index => $fileName) {
        $tmpName = $files['tmp_name'][$index];
        $error = $files['error'][$index];

        // Check for errors
        if ($error === UPLOAD_ERR_OK) {
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $uniqueFileName = uniqid() . "." . $fileExtension;
            $filePath = $uploadDir . $uniqueFileName; // Full server path
            $dbPath = 'uploads/task_file/' . $uniqueFileName; // Web-accessible path

            // Move the uploaded file to the desired directory
            if (move_uploaded_file($tmpName, $filePath)) {
                // Insert file information into the database
                $query = "INSERT INTO task_file (task_id, file_name, upload_path, date_added) VALUES (?, ?, ?, NOW())";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("iss", $taskId, $fileName, $dbPath);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    $uploadedFiles[] = $fileName;
                    $fileUploadedSuccessfully = true;
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to move file: ' . $fileName]);
                exit();
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'File upload error for: ' . $fileName]);
            exit();
        }
    }

    // If at least one file was successfully uploaded, update the task status to "Testing"
    if ($fileUploadedSuccessfully) {
        $updateQuery = "UPDATE task SET status = 'Testing' WHERE task_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("i", $taskId);
        $updateStmt->execute();

        if ($updateStmt->affected_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'Failed to update task status']);
            exit();
        }
    }

    // Return response
    if (count($uploadedFiles) > 0) {
        echo json_encode(['success' => true, 'message' => 'Files uploaded successfully, task status updated to Testing', 'files' => $uploadedFiles]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No files uploaded']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Task ID or files missing']);
}
?>
