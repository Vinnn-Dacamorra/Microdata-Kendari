<?php
require_once __DIR__ . '/../config/database.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    die('Invalid publication ID');
}

try {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM publikasi WHERE id = :id AND status = 'published'");
    $stmt->execute([':id' => $id]);
    $pub = $stmt->fetch();
    
    if (!$pub) {
        die('Publication not found');
    }
    
    // Update download count
    $stmt = $db->prepare("UPDATE publikasi SET download_count = download_count + 1 WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    // Log download
    $stmt = $db->prepare("INSERT INTO log_download (tipe, item_id, ip_address, user_agent) 
                         VALUES ('publikasi', :id, :ip, :ua)");
    $stmt->execute([
        ':id' => $id,
        ':ip' => $_SERVER['REMOTE_ADDR'],
        ':ua' => $_SERVER['HTTP_USER_AGENT'] ?? ''
    ]);
    
    // Simulate download
    echo "Publication downloaded: " . htmlspecialchars($pub['judul']);
    echo "<br><br>Author: " . htmlspecialchars($pub['penulis']);
    echo "<br><br><a href='" . BASE_URL . "/publikasi.php'>Back to Publication List</a>";
    
} catch (PDOException $e) {
    error_log("Download error: " . $e->getMessage());
    die('Error downloading publication');
}
?>
