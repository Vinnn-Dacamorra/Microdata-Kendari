<?php
$page_title = "Berita & Update";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
$db = getDB();

$page_num = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 9;
$offset = ($page_num - 1) * $per_page;

$stmt = $db->query("SELECT COUNT(*) as total FROM berita WHERE status = 'published'");
$total_records = $stmt->fetch()['total'];

$stmt = $db->prepare("SELECT * FROM berita WHERE status = 'published' ORDER BY tanggal DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$berita_list = $stmt->fetchAll();

$pagination = generatePagination($total_records, $per_page, $page_num, BASE_URL . '/berita.php?');
?>

<section class="hero" style="padding: 3rem 0;">
    <div class="container"><h1>Berita & Update</h1><p>Informasi terbaru seputar data dan publikasi</p></div>
</section>

<section class="py-5">
    <div class="container">
        <div class="stats-grid">
            <?php foreach ($berita_list as $berita): ?>
            <div class="card">
                <div class="card-header">
                    <span style="font-size: 0.875rem; color: var(--gray-600);">
                        <i class="fas fa-calendar"></i> <?php echo formatTanggal($berita['tanggal']); ?>
                    </span>
                </div>
                <div class="card-body">
                    <h4 style="font-size: 1.125rem; margin-bottom: 0.75rem; line-height: 1.4;">
                        <?php echo htmlspecialchars($berita['judul']); ?>
                    </h4>
                    <p style="color: var(--gray-600); font-size: 0.875rem; margin-bottom: 1rem;">
                        <?php echo htmlspecialchars(substr($berita['ringkasan'] ?? $berita['isi'], 0, 120)); ?>...
                    </p>
                    <a href="<?php echo BASE_URL; ?>/berita-detail.php?id=<?php echo $berita['id']; ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye"></i> Baca Selengkapnya
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($pagination['total_pages'] > 1): ?>
        <div style="display: flex; justify-content: center; align-items: center; gap: 0.5rem; margin-top: 2rem;">
            <?php if ($pagination['has_prev']): ?>
            <a href="<?php echo $pagination['base_url'] . 'page=' . $pagination['prev_page']; ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-chevron-left"></i> Prev
            </a>
            <?php endif; ?>
            <span style="padding: 0 1rem;">Halaman <?php echo $pagination['current_page']; ?> dari <?php echo $pagination['total_pages']; ?></span>
            <?php if ($pagination['has_next']): ?>
            <a href="<?php echo $pagination['base_url'] . 'page=' . $pagination['next_page']; ?>" class="btn btn-secondary btn-sm">
                Next <i class="fas fa-chevron-right"></i>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
