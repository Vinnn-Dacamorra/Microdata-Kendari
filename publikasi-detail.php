<?php
/**
 * =====================================================
 * HALAMAN DETAIL PUBLIKASI
 * =====================================================
 */

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$db = getDB();

// Get publikasi ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    redirect(BASE_URL . '/publikasi.php');
}

// Get publikasi detail
try {
    $stmt = $db->prepare("SELECT p.*, k.nama_kategori, k.slug as kategori_slug 
                         FROM publikasi p 
                         LEFT JOIN kategori k ON p.kategori_id = k.id 
                         WHERE p.id = :id AND p.status = 'published'");
    $stmt->execute([':id' => $id]);
    $pub = $stmt->fetch();
    
    if (!$pub) {
        redirect(BASE_URL . '/publikasi.php');
    }
    
    // Update view count
    $stmt = $db->prepare("UPDATE publikasi SET view_count = view_count + 1 WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    $page_title = $pub['judul'];
    $page_description = substr($pub['abstrak'] ?? '', 0, 150);
    
} catch (PDOException $e) {
    error_log("Error: " . $e->getMessage());
    redirect(BASE_URL . '/publikasi.php');
}
?>

<!-- Breadcrumb -->
<section style="background-color: var(--gray-100); padding: 1rem 0;">
    <div class="container">
        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--gray-600);">
            <a href="<?php echo BASE_URL; ?>/index.php" style="color: var(--gray-600);">Beranda</a>
            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
            <a href="<?php echo BASE_URL; ?>/publikasi.php" style="color: var(--gray-600);">Publikasi</a>
            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
            <span style="color: var(--gray-900);">Detail</span>
        </div>
    </div>
</section>

<!-- Content -->
<section class="py-5">
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto;">
            
            <!-- Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem;">
                        <span class="badge" style="background-color: var(--secondary-color); color: white; padding: 0.5rem 1rem; border-radius: 4px;">
                            <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $pub['jenis']))); ?>
                        </span>
                        <?php if ($pub['nama_kategori']): ?>
                        <span class="badge" style="background-color: var(--primary-color); color: white; padding: 0.5rem 1rem; border-radius: 4px;">
                            <?php echo htmlspecialchars($pub['nama_kategori']); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    
                    <h1 style="font-size: 2rem; margin-bottom: 1.5rem; line-height: 1.3;">
                        <?php echo htmlspecialchars($pub['judul']); ?>
                    </h1>
                    
                    <div style="display: flex; flex-wrap: wrap; gap: 2rem; padding: 1rem 0; border-top: 1px solid var(--gray-200); border-bottom: 1px solid var(--gray-200); margin-bottom: 1.5rem;">
                        <div>
                            <strong><i class="fas fa-user"></i> Penulis:</strong><br>
                            <?php echo htmlspecialchars($pub['penulis']); ?>
                        </div>
                        <div>
                            <strong><i class="fas fa-calendar"></i> Tahun:</strong><br>
                            <?php echo $pub['tahun']; ?>
                        </div>
                        <?php if ($pub['penerbit']): ?>
                        <div>
                            <strong><i class="fas fa-building"></i> Penerbit:</strong><br>
                            <?php echo htmlspecialchars($pub['penerbit']); ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($pub['halaman']): ?>
                        <div>
                            <strong><i class="fas fa-file"></i> Halaman:</strong><br>
                            <?php echo htmlspecialchars($pub['halaman']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div style="display: flex; gap: 2rem; font-size: 0.875rem; color: var(--gray-600);">
                        <span><i class="fas fa-eye"></i> <?php echo formatAngka($pub['view_count']); ?> kali dilihat</span>
                        <span><i class="fas fa-download"></i> <?php echo formatAngka($pub['download_count']); ?> kali diunduh</span>
                    </div>
                </div>
            </div>
            
            <!-- Abstrak -->
            <?php if ($pub['abstrak']): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h3 style="margin: 0; font-size: 1.25rem;"><i class="fas fa-align-left"></i> Abstrak</h3>
                </div>
                <div class="card-body">
                    <p style="line-height: 1.8; text-align: justify;">
                        <?php echo nl2br(htmlspecialchars($pub['abstrak'])); ?>
                    </p>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Metadata -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 style="margin: 0; font-size: 1.25rem;"><i class="fas fa-info-circle"></i> Informasi Publikasi</h3>
                </div>
                <div class="card-body">
                    <table style="width: 100%;">
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200); width: 200px;"><strong>Judul</strong></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><?php echo htmlspecialchars($pub['judul']); ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><strong>Penulis</strong></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><?php echo htmlspecialchars($pub['penulis']); ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><strong>Jenis</strong></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $pub['jenis']))); ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><strong>Tahun</strong></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><?php echo $pub['tahun']; ?></td>
                        </tr>
                        <?php if ($pub['penerbit']): ?>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><strong>Penerbit</strong></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><?php echo htmlspecialchars($pub['penerbit']); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($pub['doi']): ?>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><strong>DOI</strong></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><?php echo htmlspecialchars($pub['doi']); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($pub['isbn']): ?>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><strong>ISBN</strong></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><?php echo htmlspecialchars($pub['isbn']); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($pub['tags']): ?>
                        <tr>
                            <td style="padding: 0.75rem;"><strong>Kata Kunci</strong></td>
                            <td style="padding: 0.75rem;">
                                <?php 
                                $tags = explode(',', $pub['tags']);
                                foreach ($tags as $tag): 
                                ?>
                                <span class="badge" style="background-color: var(--gray-200); color: var(--gray-700); padding: 0.25rem 0.75rem; border-radius: 4px; margin-right: 0.5rem;">
                                    <?php echo htmlspecialchars(trim($tag)); ?>
                                </span>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
            
            <!-- Download Section -->
            <?php if ($pub['file_path']): ?>
            <div class="card">
                <div class="card-body text-center" style="padding: 2rem;">
                    <h3 style="margin-bottom: 1rem;">Unduh Publikasi</h3>
                    <p style="color: var(--gray-600); margin-bottom: 2rem;">
                        File tersedia dalam format PDF
                    </p>
                    <a href="<?php echo BASE_URL; ?>/api/download-publikasi.php?id=<?php echo $pub['id']; ?>" 
                       class="btn btn-success btn-lg">
                        <i class="fas fa-download"></i> Download Publikasi
                    </a>
                    <p style="margin-top: 1rem; font-size: 0.875rem; color: var(--gray-500);">
                        Sudah diunduh <?php echo formatAngka($pub['download_count']); ?> kali
                    </p>
                </div>
            </div>
            <?php endif; ?>
            
        </div>
    </div>
</section>

<!-- Related Publications -->
<section class="py-5" style="background-color: var(--gray-100);">
    <div class="container">
        <h3 class="mb-4">Publikasi Terkait</h3>
        
        <?php
        // Get related publications
        $stmt = $db->prepare("SELECT p.*, k.nama_kategori 
                             FROM publikasi p 
                             LEFT JOIN kategori k ON p.kategori_id = k.id 
                             WHERE p.id != :id 
                             AND p.status = 'published' 
                             AND (p.kategori_id = :kategori_id OR p.jenis = :jenis)
                             ORDER BY p.created_at DESC 
                             LIMIT 3");
        $stmt->execute([
            ':id' => $id,
            ':kategori_id' => $pub['kategori_id'],
            ':jenis' => $pub['jenis']
        ]);
        $related = $stmt->fetchAll();
        ?>
        
        <?php if (count($related) > 0): ?>
        <div class="stats-grid">
            <?php foreach ($related as $rel): ?>
            <div class="card">
                <div class="card-header">
                    <span class="badge" style="background-color: var(--secondary-color); color: white; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.875rem;">
                        <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $rel['jenis']))); ?>
                    </span>
                </div>
                <div class="card-body">
                    <h4 style="font-size: 1.125rem; margin-bottom: 0.75rem; line-height: 1.4;">
                        <?php echo htmlspecialchars(substr($rel['judul'], 0, 80)); ?>...
                    </h4>
                    <p style="color: var(--gray-600); font-size: 0.875rem; margin-bottom: 1rem;">
                        <i class="fas fa-user"></i> <?php echo htmlspecialchars($rel['penulis']); ?>
                    </p>
                    <a href="<?php echo BASE_URL; ?>/publikasi-detail.php?id=<?php echo $rel['id']; ?>" 
                       class="btn btn-primary btn-sm">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p style="color: var(--gray-600);">Tidak ada publikasi terkait</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
