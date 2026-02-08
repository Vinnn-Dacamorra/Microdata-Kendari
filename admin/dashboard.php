<?php
/**
 * =====================================================
 * ADMIN DASHBOARD
 * =====================================================
 */

session_start();
require_once __DIR__ . '/../config/database.php';

// Check login
if (!isLoggedIn()) {
    redirect('/admin/login.php');
}

$page_title = "Dashboard Admin";

$db = getDB();

// Get statistik
try {
    $stmt = $db->query("CALL sp_get_dashboard_stats()");
    $stats = $stmt->fetch();
    
    // Get permintaan data terbaru
    $stmt = $db->query("SELECT * FROM permintaan_data ORDER BY tanggal DESC LIMIT 5");
    $permintaan_terbaru = $stmt->fetchAll();
    
    // Get dataset terbaru
    $stmt = $db->query("SELECT d.*, k.nama_kategori 
                       FROM dataset d 
                       JOIN kategori k ON d.kategori_id = k.id 
                       ORDER BY d.created_at DESC LIMIT 5");
    $dataset_terbaru = $stmt->fetchAll();
    
} catch (PDOException $e) {
    error_log("Dashboard error: " . $e->getMessage());
    $stats = [
        'total_dataset' => 0,
        'total_publikasi' => 0,
        'total_data_statistik' => 0,
        'permintaan_pending' => 0,
        'total_download_dataset' => 0,
        'total_download_publikasi' => 0
    ];
    $permintaan_terbaru = [];
    $dataset_terbaru = [];
}

require_once __DIR__ . '/../includes/header.php';
?>

<style>
    .admin-layout {
        display: flex;
        min-height: 100vh;
    }
    
    .admin-sidebar {
        width: 250px;
        background-color: var(--gray-900);
        color: white;
        padding: 1.5rem 0;
    }
    
    .admin-sidebar-header {
        padding: 0 1.5rem;
        margin-bottom: 2rem;
    }
    
    .admin-sidebar-header h2 {
        color: white;
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }
    
    .admin-sidebar-menu {
        list-style: none;
    }
    
    .admin-sidebar-menu a {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1.5rem;
        color: var(--gray-300);
        text-decoration: none;
        transition: var(--transition-base);
    }
    
    .admin-sidebar-menu a:hover,
    .admin-sidebar-menu a.active {
        background-color: var(--primary-color);
        color: white;
    }
    
    .admin-content {
        flex: 1;
        padding: 2rem;
        background-color: var(--gray-100);
    }
    
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--gray-200);
    }
    
    .admin-user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .stat-box {
        background: white;
        padding: 1.5rem;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        border-left: 4px solid var(--primary-color);
    }
    
    .stat-box.warning {
        border-left-color: var(--warning-color);
    }
    
    .stat-box.success {
        border-left-color: var(--success-color);
    }
    
    .stat-box.info {
        border-left-color: var(--info-color);
    }
</style>

<div class="admin-layout">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="admin-sidebar-header">
            <h2><i class="fas fa-user-shield"></i> Admin Panel</h2>
            <p style="font-size: 0.875rem; color: var(--gray-400); margin: 0;">Microdata Kendari</p>
        </div>
        
        <ul class="admin-sidebar-menu">
            <li><a href="/admin/dashboard.php" class="active">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a></li>
            <li><a href="/admin/data-manage.php">
                <i class="fas fa-chart-bar"></i> Data Statistik
            </a></li>
            <li><a href="/admin/dataset-manage.php">
                <i class="fas fa-database"></i> Dataset
            </a></li>
            <li><a href="/admin/publikasi-manage.php">
                <i class="fas fa-book"></i> Publikasi
            </a></li>
            <li><a href="/admin/berita-manage.php">
                <i class="fas fa-newspaper"></i> Berita
            </a></li>
            <li><a href="/admin/permintaan-manage.php">
                <i class="fas fa-inbox"></i> Permintaan Data
                <?php if ($stats['permintaan_pending'] > 0): ?>
                <span class="badge" style="background-color: var(--error-color); color: white; padding: 0.25rem 0.5rem; border-radius: 10px; font-size: 0.75rem; margin-left: auto;">
                    <?php echo $stats['permintaan_pending']; ?>
                </span>
                <?php endif; ?>
            </a></li>
            <li><a href="/admin/logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a></li>
        </ul>
    </aside>
    
    <!-- Content -->
    <main class="admin-content">
        <!-- Header -->
        <div class="admin-header">
            <h1>Dashboard</h1>
            <div class="admin-user-info">
                <div style="text-align: right;">
                    <strong><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></strong>
                    <br>
                    <small style="color: var(--gray-600);">
                        <?php echo ucfirst($_SESSION['role']); ?>
                    </small>
                </div>
                <div style="width: 40px; height: 40px; background-color: var(--primary-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                    <?php echo strtoupper(substr($_SESSION['nama_lengkap'], 0, 1)); ?>
                </div>
            </div>
        </div>
        
        <!-- Flash Message -->
        <?php 
        $flash = getFlashMessage();
        if ($flash): 
        ?>
        <div class="flash-message flash-<?php echo $flash['type']; ?>">
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
            <button class="flash-close" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <?php endif; ?>
        
        <!-- Stats -->
        <div class="dashboard-stats">
            <div class="stat-box">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h3 style="font-size: 2rem; margin-bottom: 0.25rem;"><?php echo formatAngka($stats['total_dataset']); ?></h3>
                        <p style="color: var(--gray-600); margin: 0;">Dataset</p>
                    </div>
                    <i class="fas fa-database" style="font-size: 2.5rem; color: var(--primary-color); opacity: 0.3;"></i>
                </div>
            </div>
            
            <div class="stat-box success">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h3 style="font-size: 2rem; margin-bottom: 0.25rem;"><?php echo formatAngka($stats['total_publikasi']); ?></h3>
                        <p style="color: var(--gray-600); margin: 0;">Publikasi</p>
                    </div>
                    <i class="fas fa-book" style="font-size: 2.5rem; color: var(--success-color); opacity: 0.3;"></i>
                </div>
            </div>
            
            <div class="stat-box info">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h3 style="font-size: 2rem; margin-bottom: 0.25rem;"><?php echo formatAngka($stats['total_data_statistik']); ?></h3>
                        <p style="color: var(--gray-600); margin: 0;">Data Statistik</p>
                    </div>
                    <i class="fas fa-chart-line" style="font-size: 2.5rem; color: var(--info-color); opacity: 0.3;"></i>
                </div>
            </div>
            
            <div class="stat-box warning">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h3 style="font-size: 2rem; margin-bottom: 0.25rem;"><?php echo formatAngka($stats['permintaan_pending']); ?></h3>
                        <p style="color: var(--gray-600); margin: 0;">Permintaan Pending</p>
                    </div>
                    <i class="fas fa-inbox" style="font-size: 2.5rem; color: var(--warning-color); opacity: 0.3;"></i>
                </div>
            </div>
        </div>
        
        <!-- Content Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">
            <!-- Permintaan Data Terbaru -->
            <div class="card">
                <div class="card-header">
                    <h3 style="margin: 0; font-size: 1.125rem;">
                        <i class="fas fa-inbox"></i> Permintaan Data Terbaru
                    </h3>
                </div>
                <div class="card-body" style="padding: 0;">
                    <?php if (empty($permintaan_terbaru)): ?>
                    <p style="padding: 1.5rem; color: var(--gray-600); text-align: center; margin: 0;">
                        Belum ada permintaan data
                    </p>
                    <?php else: ?>
                    <table style="margin: 0;">
                        <thead style="background-color: var(--gray-100);">
                            <tr>
                                <th>Nama</th>
                                <th>Instansi</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($permintaan_terbaru as $req): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($req['nama']); ?></td>
                                <td><?php echo htmlspecialchars($req['instansi'] ?? '-'); ?></td>
                                <td>
                                    <?php
                                    $badge_class = 'warning';
                                    if ($req['status'] == 'selesai') $badge_class = 'success';
                                    if ($req['status'] == 'ditolak') $badge_class = 'error';
                                    ?>
                                    <span class="badge" style="background-color: var(--<?php echo $badge_class; ?>-color); color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">
                                        <?php echo ucfirst($req['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo formatTanggal($req['tanggal'], 'd M Y'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <a href="/admin/permintaan-manage.php" class="btn btn-primary btn-sm">
                        Lihat Semua <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            
            <!-- Dataset Terbaru -->
            <div class="card">
                <div class="card-header">
                    <h3 style="margin: 0; font-size: 1.125rem;">
                        <i class="fas fa-database"></i> Dataset Terbaru
                    </h3>
                </div>
                <div class="card-body" style="padding: 0;">
                    <?php if (empty($dataset_terbaru)): ?>
                    <p style="padding: 1.5rem; color: var(--gray-600); text-align: center; margin: 0;">
                        Belum ada dataset
                    </p>
                    <?php else: ?>
                    <table style="margin: 0;">
                        <thead style="background-color: var(--gray-100);">
                            <tr>
                                <th>Nama Dataset</th>
                                <th>Kategori</th>
                                <th>Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataset_terbaru as $ds): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(substr($ds['nama_dataset'], 0, 40)); ?>...</td>
                                <td><?php echo htmlspecialchars($ds['nama_kategori']); ?></td>
                                <td><?php echo formatAngka($ds['download_count']); ?>x</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <a href="/admin/dataset-manage.php" class="btn btn-primary btn-sm">
                        Kelola Dataset <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
// Auto hide flash message
const flashMessage = document.querySelector('.flash-message');
if (flashMessage) {
    setTimeout(function() {
        flashMessage.style.opacity = '0';
        setTimeout(function() {
            flashMessage.style.display = 'none';
        }, 300);
    }, 5000);
}
</script>

</body>
</html>
