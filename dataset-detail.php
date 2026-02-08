<?php
/**
 * =====================================================
 * HALAMAN DETAIL DATASET
 * =====================================================
 */

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$db = getDB();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    redirect(BASE_URL . '/dataset.php');
}

try {
    $stmt = $db->prepare("SELECT d.*, k.nama_kategori, k.slug as kategori_slug 
                         FROM dataset d 
                         JOIN kategori k ON d.kategori_id = k.id 
                         WHERE d.id = :id AND d.status = 'published'");
    $stmt->execute([':id' => $id]);
    $dataset = $stmt->fetch();
    
    if (!$dataset) {
        redirect(BASE_URL . '/dataset.php');
    }
    
    $page_title = $dataset['nama_dataset'];
    $page_description = substr($dataset['deskripsi'] ?? '', 0, 150);
    
} catch (PDOException $e) {
    error_log("Error: " . $e->getMessage());
    redirect(BASE_URL . '/dataset.php');
}
?>

<!-- Breadcrumb -->
<section style="background-color: var(--gray-100); padding: 1rem 0;">
    <div class="container">
        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--gray-600);">
            <a href="<?php echo BASE_URL; ?>/index.php" style="color: var(--gray-600);">Beranda</a>
            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
            <a href="<?php echo BASE_URL; ?>/dataset.php" style="color: var(--gray-600);">Dataset</a>
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
                        <span class="badge" style="background-color: var(--primary-color); color: white; padding: 0.5rem 1rem; border-radius: 4px;">
                            <?php echo htmlspecialchars($dataset['nama_kategori']); ?>
                        </span>
                        <span class="badge" style="background-color: var(--info-color); color: white; padding: 0.5rem 1rem; border-radius: 4px;">
                            <?php echo strtoupper($dataset['file_type']); ?>
                        </span>
                    </div>
                    
                    <h1 style="font-size: 2rem; margin-bottom: 1.5rem; line-height: 1.3;">
                        <?php echo htmlspecialchars($dataset['nama_dataset']); ?>
                    </h1>
                    
                    <div style="display: flex; flex-wrap: wrap; gap: 2rem; padding: 1rem 0; border-top: 1px solid var(--gray-200); border-bottom: 1px solid var(--gray-200); margin-bottom: 1.5rem;">
                        <div>
                            <strong><i class="fas fa-building"></i> Sumber:</strong><br>
                            <?php echo htmlspecialchars($dataset['sumber']); ?>
                        </div>
                        <div>
                            <strong><i class="fas fa-calendar"></i> Tahun:</strong><br>
                            <?php echo $dataset['tahun']; ?>
                        </div>
                        <div>
                            <strong><i class="fas fa-hdd"></i> Ukuran:</strong><br>
                            <?php echo formatFileSize($dataset['file_size'] ?? 0); ?>
                        </div>
                        <div>
                            <strong><i class="fas fa-download"></i> Download:</strong><br>
                            <?php echo formatAngka($dataset['download_count']); ?>x
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Deskripsi -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 style="margin: 0; font-size: 1.25rem;"><i class="fas fa-align-left"></i> Deskripsi</h3>
                </div>
                <div class="card-body">
                    <p style="line-height: 1.8;">
                        <?php echo nl2br(htmlspecialchars($dataset['deskripsi'])); ?>
                    </p>
                </div>
            </div>
            
            <!-- Metadata -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 style="margin: 0; font-size: 1.25rem;"><i class="fas fa-info-circle"></i> Metadata</h3>
                </div>
                <div class="card-body">
                    <table style="width: 100%;">
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200); width: 200px;"><strong>Nama Dataset</strong></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><?php echo htmlspecialchars($dataset['nama_dataset']); ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><strong>Kategori</strong></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><?php echo htmlspecialchars($dataset['nama_kategori']); ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><strong>Sumber</strong></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><?php echo htmlspecialchars($dataset['sumber']); ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><strong>Tahun</strong></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><?php echo $dataset['tahun']; ?></td>
                        </tr>
                        <?php if ($dataset['periode']): ?>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><strong>Periode</strong></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><?php echo htmlspecialchars($dataset['periode']); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($dataset['metodologi']): ?>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><strong>Metodologi</strong></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><?php echo htmlspecialchars($dataset['metodologi']); ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><strong>Format File</strong></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><?php echo strtoupper($dataset['file_type']); ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><strong>Ukuran File</strong></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><?php echo formatFileSize($dataset['file_size'] ?? 0); ?></td>
                        </tr>
                        <?php if ($dataset['jumlah_baris']): ?>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><strong>Jumlah Baris</strong></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><?php echo formatAngka($dataset['jumlah_baris']); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($dataset['jumlah_kolom']): ?>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><strong>Jumlah Kolom</strong></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><?php echo formatAngka($dataset['jumlah_kolom']); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($dataset['frekuensi_update']): ?>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><strong>Frekuensi Update</strong></td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid var(--gray-200);"><?php echo htmlspecialchars($dataset['frekuensi_update']); ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td style="padding: 0.75rem;"><strong>Lisensi</strong></td>
                            <td style="padding: 0.75rem;"><?php echo htmlspecialchars($dataset['lisensi']); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Download Section -->
            <div class="card">
                <div class="card-body text-center" style="padding: 2rem;">
                    <h3 style="margin-bottom: 1rem;">Unduh Dataset</h3>
                    <p style="color: var(--gray-600); margin-bottom: 2rem;">
                        Dataset tersedia dalam format <?php echo strtoupper($dataset['file_type']); ?>
                    </p>
                    <a href="<?php echo BASE_URL; ?>/api/download-dataset.php?id=<?php echo $dataset['id']; ?>" 
                       class="btn btn-success btn-lg">
                        <i class="fas fa-download"></i> Download Dataset
                    </a>
                    <p style="margin-top: 1rem; font-size: 0.875rem; color: var(--gray-500);">
                        Sudah diunduh <?php echo formatAngka($dataset['download_count']); ?> kali
                    </p>
                </div>
            </div>
            
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
