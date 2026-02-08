<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Load database config
require_once __DIR__ . '/../config/database.php';

// Get pengaturan website
$db = getDB();
$stmt = $db->query("SELECT kunci, nilai FROM pengaturan");
$pengaturan = [];
while ($row = $stmt->fetch()) {
    $pengaturan[$row['kunci']] = $row['nilai'];
}

$nama_website = $pengaturan['nama_website'] ?? 'Microdata Kendari';
$tagline = $pengaturan['tagline'] ?? 'Portal Data dan Publikasi Ilmiah';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title><?php echo $page_title ?? $nama_website; ?> - <?php echo $nama_website; ?></title>
    <meta name="description" content="<?php echo $page_description ?? $pengaturan['deskripsi'] ?? ''; ?>">
    <meta name="keywords" content="microdata, data kendari, statistik kendari, open data, publikasi ilmiah">
    <meta name="author" content="<?php echo $nama_website; ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo $page_title ?? $nama_website; ?>">
    <meta property="og:description" content="<?php echo $page_description ?? $pengaturan['deskripsi'] ?? ''; ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo ASSETS_URL; ?>/images/favicon.ico">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/responsive.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Additional CSS if needed -->
    <?php if (isset($additional_css)): ?>
        <?php echo $additional_css; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Flash Message -->
    <?php 
    $flash = getFlashMessage();
    if ($flash): 
    ?>
    <div class="flash-message flash-<?php echo $flash['type']; ?>">
        <div class="container">
            <span class="flash-icon">
                <?php 
                switch($flash['type']) {
                    case 'success': echo '<i class="fas fa-check-circle"></i>'; break;
                    case 'error': echo '<i class="fas fa-times-circle"></i>'; break;
                    case 'warning': echo '<i class="fas fa-exclamation-triangle"></i>'; break;
                    case 'info': echo '<i class="fas fa-info-circle"></i>'; break;
                }
                ?>
            </span>
            <span class="flash-text"><?php echo htmlspecialchars($flash['message']); ?></span>
            <button class="flash-close" onclick="this.parentElement.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <?php endif; ?>
