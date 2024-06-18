<?php
session_start();

if (isset($_POST['upload_cv'])) {
    // Check if file was uploaded without errors
    if (isset($_FILES["cv_file"]) && $_FILES["cv_file"]["error"] == 0) {
        $target_dir = "cv_uploads/"; // Direktori tempat menyimpan file CV
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Buat direktori jika belum ada
        }
        $target_file = $target_dir . basename($_FILES["cv_file"]["name"]);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allow certain file formats
        $allowed_formats = array("pdf", "doc", "docx");
        if (!in_array($file_type, $allowed_formats)) {
            echo "Sorry, only PDF, DOC, and DOCX files are allowed.";
            exit();
        }

        // Move the file to specified directory
        if (move_uploaded_file($_FILES["cv_file"]["tmp_name"], $target_file)) {
            // File successfully uploaded, store file info in session
            $_SESSION['cv_file'] = array(
                'name' => basename($_FILES["cv_file"]["name"]),
                'path' => $target_file
            );
            header("Location: cv.php"); // Redirect back to CV page
            exit();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "No file uploaded or there was an error uploading the file.";
    }
}
?>
