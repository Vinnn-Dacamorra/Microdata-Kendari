<?php
require_once __DIR__ . '/../config/database.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    die('Invalid dataset ID');
}

try {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM dataset WHERE id = :id AND status = 'published'");
    $stmt->execute([':id' => $id]);
    $dataset = $stmt->fetch();
    
    if (!$dataset) {
        die('Dataset not found');
    }
    
    // Update download count
    $stmt = $db->prepare("UPDATE dataset SET download_count = download_count + 1 WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    // Log download
    $stmt = $db->prepare("INSERT INTO log_download (tipe, item_id, ip_address, user_agent) 
                         VALUES ('dataset', :id, :ip, :ua)");
    $stmt->execute([
        ':id' => $id,
        ':ip' => $_SERVER['REMOTE_ADDR'],
        ':ua' => $_SERVER['HTTP_USER_AGENT'] ?? ''
    ]);
    
    // Simulate download (in real scenario, file should exist)
    echo "Dataset downloaded: " . htmlspecialchars($dataset['nama_dataset']);
    echo "<br><br>File: " . htmlspecialchars($dataset['file_name']);
    echo "<br><br><a href='" . BASE_URL . "/dataset.php'>Back to Dataset List</a>";
    
} catch (PDOException $e) {
    error_log("Download error: " . $e->getMessage());
    die('Error downloading dataset');
}
?>
