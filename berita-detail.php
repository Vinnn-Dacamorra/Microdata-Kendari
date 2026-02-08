<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
$db = getDB();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) redirect(BASE_URL . '/berita.php');

$stmt = $db->prepare("SELECT * FROM berita WHERE id = :id AND status = 'published'");
$stmt->execute([':id' => $id]);
$berita = $stmt->fetch();

if (!$berita) redirect(BASE_URL . '/berita.php');

$stmt = $db->prepare("UPDATE berita SET view_count = view_count + 1 WHERE id = :id");
$stmt->execute([':id' => $id]);

$page_title = $berita['judul'];
?>

<section style="background-color: var(--gray-100); padding: 1rem 0;">
    <div class="container">
        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--gray-600);">
            <a href="<?php echo BASE_URL; ?>/index.php" style="color: var(--gray-600);">Beranda</a>
            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
            <a href="<?php echo BASE_URL; ?>/berita.php" style="color: var(--gray-600);">Berita</a>
            <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
            <span style="color: var(--gray-900);">Detail</span>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto;">
            <article class="card">
                <div class="card-body" style="padding: 2rem;">
                    <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--gray-200);">
                        <h1 style="font-size: 2rem; margin-bottom: 1rem; line-height: 1.3;">
                            <?php echo htmlspecialchars($berita['judul']); ?>
                        </h1>
                        <div style="display: flex; gap: 1.5rem; font-size: 0.875rem; color: var(--gray-600);">
                            <span><i class="fas fa-calendar"></i> <?php echo formatTanggal($berita['tanggal']); ?></span>
                            <?php if ($berita['penulis']): ?>
                            <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($berita['penulis']); ?></span>
                            <?php endif; ?>
                            <span><i class="fas fa-eye"></i> <?php echo formatAngka($berita['view_count']); ?>x</span>
                        </div>
                    </div>
                    
                    <?php if ($berita['gambar']): ?>
                    <img src="<?php echo htmlspecialchars($berita['gambar']); ?>" alt="<?php echo htmlspecialchars($berita['judul']); ?>" style="width: 100%; border-radius: 8px; margin-bottom: 2rem;">
                    <?php endif; ?>
                    
                    <div style="line-height: 1.8; font-size: 1.0625rem;">
                        <?php echo nl2br(htmlspecialchars($berita['isi'])); ?>
                    </div>
                </div>
            </article>
            
            <div style="margin-top: 2rem; text-align: center;">
                <a href="<?php echo BASE_URL; ?>/berita.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Berita
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
