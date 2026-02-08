<?php
/**
 * =====================================================
 * HALAMAN BERANDA - MICRODATA KENDARI
 * =====================================================
 */

$page_title = "Beranda";
$page_description = "Portal Data dan Publikasi Ilmiah Kota Kendari";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Ambil statistik dashboard
try {
    $db = getDB();
    
    // Total dataset
    $stmt = $db->query("SELECT COUNT(*) as total FROM dataset WHERE status = 'published'");
    $total_dataset = $stmt->fetch()['total'];
    
    // Total publikasi
    $stmt = $db->query("SELECT COUNT(*) as total FROM publikasi WHERE status = 'published'");
    $total_publikasi = $stmt->fetch()['total'];
    
    // Total data statistik
    $stmt = $db->query("SELECT COUNT(*) as total FROM data_statistik");
    $total_data = $stmt->fetch()['total'];
    
    // Total download
    $stmt = $db->query("SELECT (SELECT COALESCE(SUM(download_count), 0) FROM dataset) + 
                               (SELECT COALESCE(SUM(download_count), 0) FROM publikasi) as total");
    $total_download = $stmt->fetch()['total'];
    
    // Dataset terbaru
    $stmt = $db->query("SELECT d.*, k.nama_kategori, k.slug as kategori_slug 
                       FROM dataset d 
                       JOIN kategori k ON d.kategori_id = k.id 
                       WHERE d.status = 'published' 
                       ORDER BY d.created_at DESC 
                       LIMIT 3");
    $dataset_terbaru = $stmt->fetchAll();
    
    // Publikasi terbaru
    $stmt = $db->query("SELECT p.*, k.nama_kategori 
                       FROM publikasi p 
                       LEFT JOIN kategori k ON p.kategori_id = k.id 
                       WHERE p.status = 'published' 
                       ORDER BY p.created_at DESC 
                       LIMIT 3");
    $publikasi_terbaru = $stmt->fetchAll();
    
} catch (PDOException $e) {
    error_log("Error: " . $e->getMessage());
    $total_dataset = $total_publikasi = $total_data = $total_download = 0;
    $dataset_terbaru = $publikasi_terbaru = [];
}
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Selamat Datang di Microdata Kendari</h1>
        <p>Portal Data dan Publikasi Ilmiah Kota Kendari</p>
        <p>Akses data mikro, statistik, dan publikasi ilmiah secara terbuka, terstruktur, dan bertanggung jawab</p>
        <div class="hero-actions">
            <a href="<?php echo BASE_URL; ?>/dataset.php" class="btn btn-primary btn-lg">
                <i class="fas fa-database"></i> Jelajahi Dataset
            </a>
            <a href="<?php echo BASE_URL; ?>/data.php" class="btn btn-outline btn-lg" style="color: white; border-color: white;">
                <i class="fas fa-chart-bar"></i> Lihat Statistik
            </a>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-table"></i>
                </div>
                <div class="stat-number"><?php echo formatAngka($total_dataset); ?></div>
                <div class="stat-label">Dataset Tersedia</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-number"><?php echo formatAngka($total_publikasi); ?></div>
                <div class="stat-label">Publikasi Ilmiah</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-number"><?php echo formatAngka($total_data); ?></div>
                <div class="stat-label">Data Statistik</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-download"></i>
                </div>
                <div class="stat-number"><?php echo formatAngka($total_download); ?></div>
                <div class="stat-label">Total Unduhan</div>
            </div>
        </div>
    </div>
</section>

<!-- Dataset Terbaru -->
<section class="py-5" style="background-color: white;">
    <div class="container">
        <h2 class="text-center mb-4">Dataset Terbaru</h2>
        <p class="text-center mb-5" style="color: var(--gray-600);">
            Akses dataset terbaru yang telah kami publikasikan
        </p>
        
        <div class="stats-grid">
            <?php if (empty($dataset_terbaru)): ?>
                <div class="card">
                    <div class="card-body text-center">
                        <p>Belum ada dataset tersedia</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($dataset_terbaru as $dataset): ?>
                <div class="card">
                    <div class="card-header">
                        <span class="badge" style="background-color: var(--primary-color); color: white; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.875rem;">
                            <?php echo htmlspecialchars($dataset['nama_kategori']); ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <h4 style="font-size: 1.25rem; margin-bottom: 0.75rem;">
                            <?php echo htmlspecialchars($dataset['nama_dataset']); ?>
                        </h4>
                        <p style="color: var(--gray-600); font-size: 0.875rem; margin-bottom: 1rem;">
                            <?php echo htmlspecialchars(substr($dataset['deskripsi'] ?? '', 0, 100)); ?>...
                        </p>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; font-size: 0.875rem; color: var(--gray-500);">
                            <span><i class="fas fa-calendar"></i> <?php echo $dataset['tahun']; ?></span>
                            <span><i class="fas fa-download"></i> <?php echo formatAngka($dataset['download_count']); ?>x</span>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/dataset-detail.php?id=<?php echo $dataset['id']; ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?php echo BASE_URL; ?>/dataset.php" class="btn btn-outline">
                Lihat Semua Dataset <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Publikasi Terbaru -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Publikasi Terbaru</h2>
        <p class="text-center mb-5" style="color: var(--gray-600);">
            Publikasi ilmiah dan laporan statistik terbaru
        </p>
        
        <div class="stats-grid">
            <?php if (empty($publikasi_terbaru)): ?>
                <div class="card">
                    <div class="card-body text-center">
                        <p>Belum ada publikasi tersedia</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($publikasi_terbaru as $pub): ?>
                <div class="card">
                    <div class="card-header">
                        <span class="badge" style="background-color: var(--secondary-color); color: white; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.875rem;">
                            <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $pub['jenis']))); ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <h4 style="font-size: 1.25rem; margin-bottom: 0.75rem;">
                            <?php echo htmlspecialchars($pub['judul']); ?>
                        </h4>
                        <p style="color: var(--gray-600); font-size: 0.875rem; margin-bottom: 0.5rem;">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($pub['penulis']); ?>
                        </p>
                        <p style="color: var(--gray-600); font-size: 0.875rem; margin-bottom: 1rem;">
                            <?php echo htmlspecialchars(substr($pub['abstrak'] ?? '', 0, 100)); ?>...
                        </p>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; font-size: 0.875rem; color: var(--gray-500);">
                            <span><i class="fas fa-calendar"></i> <?php echo $pub['tahun']; ?></span>
                            <span><i class="fas fa-download"></i> <?php echo formatAngka($pub['download_count']); ?>x</span>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/publikasi-detail.php?id=<?php echo $pub['id']; ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?php echo BASE_URL; ?>/publikasi.php" class="btn btn-outline">
                Lihat Semua Publikasi <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white;">
    <div class="container text-center">
        <h2 style="color: white; margin-bottom: 1rem;">Butuh Data Khusus?</h2>
        <p style="font-size: 1.125rem; margin-bottom: 2rem; opacity: 0.9;">
            Kami siap membantu Anda dalam penyediaan data sesuai kebutuhan penelitian dan analisis
        </p>
        <a href="<?php echo BASE_URL; ?>/layanan.php#request" class="btn btn-lg" style="background-color: white; color: var(--primary-color);">
            <i class="fas fa-paper-plane"></i> Ajukan Permintaan Data
        </a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
