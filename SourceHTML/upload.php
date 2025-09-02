<?php
$targetDir = "gallery/";

// Check if a file was uploaded
if (!isset($_FILES['imageUpload'])) {
    die("No file uploaded.");
}

$file = $_FILES['imageUpload'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

// Only allow images
$allowed = ['jpg','jpeg','png','gif'];
if (!in_array($ext, $allowed)) {
    die("Invalid file type. Only JPG, JPEG, PNG & GIF allowed.");
}

// Optional: generate unique filename to avoid collisions
$newName = uniqid() . '.' . $ext;
$targetPath = $targetDir . $newName;

// Example tags (later you’ll want to pass these from a form)
$tags = [];

if (isset($_POST['tags'])) {
    $tags = json_decode($_POST['tags'], true);
    if (!is_array($tags)) {
        $tags = [];
    }
}

$galleryFile = $targetDir . "gallery.json";

// ---------------- DEBUG JSON HANDLING ----------------
//echo "<pre>";
if (file_exists($galleryFile)) {
    //echo "Gallery file exists: " . realpath($galleryFile) . "\n";
    $jsonContent = file_get_contents($galleryFile);
    if ($jsonContent === false) {
        die("Failed to read gallery.json");
    }
    //echo "Raw JSON content: " . $jsonContent . "\n";

    $gallery = json_decode($jsonContent, true);
    if ($gallery === null && json_last_error() !== JSON_ERROR_NONE) {
        die("JSON decode error: " . json_last_error_msg());
    }
} else {
    echo "Gallery file does not exist, creating new one.\n";
    $gallery = ["images" => []];
}

// Append new entry
$gallery["images"][] = [
    "file" => $newName,
    "tags" => $tags,
    "uploadEpoch" => time()
];

//echo "New gallery data structure:\n";
//print_r($gallery);

// Try writing JSON file
$result = file_put_contents($galleryFile, json_encode($gallery, JSON_PRETTY_PRINT));
if ($result === false) {
    die("Failed to write gallery.json. Check file/folder permissions.");
}
//echo "Gallery.json updated successfully.\n";
// ---------------- END DEBUG ----------------

// Debug upload errors
if ($file['error'] !== UPLOAD_ERR_OK) {
    die("Upload error code: " . $file['error']);
}

// Check folder permissions
if (!is_dir($targetDir)) {
    die("Gallery folder does not exist: " . realpath($targetDir));
}
if (!is_writable($targetDir)) {
    die("Gallery folder is not writable: " . realpath($targetDir));
}

// Attempt to move uploaded file
if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    echo "Upload successful!";
} else {
    echo "Failed to move uploaded file.\n";
    echo "Tmp file: " . $file['tmp_name'] . "\n";
    echo "Target path: " . $targetPath . "\n";
    echo "is_uploaded_file: " . (is_uploaded_file($file['tmp_name']) ? "true" : "false") . "\n";
}
//echo "</pre>";
?>

