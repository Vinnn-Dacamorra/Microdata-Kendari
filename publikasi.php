<?php
/**
 * =====================================================
 * HALAMAN PUBLIKASI
 * =====================================================
 */

$page_title = "Publikasi Ilmiah";
$page_description = "Publikasi ilmiah, laporan, dan analisis data Kota Kendari";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$db = getDB();

// Get filter dari URL
$jenis_filter = $_GET['jenis'] ?? '';
$kategori_filter = $_GET['kategori'] ?? '';
$tahun_filter = $_GET['tahun'] ?? '';
$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 12;
$offset = ($page - 1) * $per_page;

// Get kategori list
$stmt = $db->query("SELECT * FROM kategori WHERE status = 'aktif' ORDER BY urutan");
$kategori_list = $stmt->fetchAll();

// Get tahun list
$stmt = $db->query("SELECT DISTINCT tahun FROM publikasi WHERE status = 'published' ORDER BY tahun DESC");
$tahun_list = $stmt->fetchAll();

// Build query dengan filter
$where_conditions = ["p.status = 'published'"];
$params = [];

if ($jenis_filter) {
    $where_conditions[] = "p.jenis = :jenis";
    $params[':jenis'] = $jenis_filter;
}

if ($kategori_filter) {
    $where_conditions[] = "k.slug = :kategori";
    $params[':kategori'] = $kategori_filter;
}

if ($tahun_filter) {
    $where_conditions[] = "p.tahun = :tahun";
    $params[':tahun'] = $tahun_filter;
}

if ($search) {
    $where_conditions[] = "(p.judul LIKE :search OR p.penulis LIKE :search OR p.abstrak LIKE :search OR p.tags LIKE :search)";
    $params[':search'] = "%$search%";
}

$where_clause = implode(' AND ', $where_conditions);

// Count total records
$count_sql = "SELECT COUNT(*) as total FROM publikasi p 
              LEFT JOIN kategori k ON p.kategori_id = k.id 
              WHERE $where_clause";
$stmt = $db->prepare($count_sql);
$stmt->execute($params);
$total_records = $stmt->fetch()['total'];

// Get publikasi dengan pagination
$sql = "SELECT p.*, k.nama_kategori, k.slug as kategori_slug 
        FROM publikasi p 
        LEFT JOIN kategori k ON p.kategori_id = k.id 
        WHERE $where_clause 
        ORDER BY p.created_at DESC 
        LIMIT :limit OFFSET :offset";

$stmt = $db->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$publikasi_list = $stmt->fetchAll();

// Pagination info
$pagination = generatePagination($total_records, $per_page, $page, BASE_URL . '/publikasi.php?' . http_build_query(array_filter($_GET, function($key) { return $key !== 'page'; }, ARRAY_FILTER_USE_KEY)));
?>

<!-- Page Header -->
<section class="hero" style="padding: 3rem 0;">
    <div class="container">
        <h1>Publikasi Ilmiah</h1>
        <p>Kumpulan publikasi ilmiah, laporan, dan analisis data Kota Kendari</p>
    </div>
</section>

<!-- Filter & Search -->
<section class="py-4" style="background-color: white; box-shadow: var(--shadow-sm);">
    <div class="container">
        <form method="GET" action="<?php echo BASE_URL; ?>/publikasi.php" style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
            <!-- Search -->
            <div class="form-group" style="margin: 0;">
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Cari publikasi..."
                       value="<?php echo htmlspecialchars($search); ?>">
            </div>
            
            <!-- Jenis -->
            <div class="form-group" style="margin: 0;">
                <select name="jenis" class="form-control">
                    <option value="">Semua Jenis</option>
                    <option value="laporan" <?php echo ($jenis_filter == 'laporan') ? 'selected' : ''; ?>>Laporan</option>
                    <option value="artikel" <?php echo ($jenis_filter == 'artikel') ? 'selected' : ''; ?>>Artikel</option>
                    <option value="infografis" <?php echo ($jenis_filter == 'infografis') ? 'selected' : ''; ?>>Infografis</option>
                    <option value="policy_brief" <?php echo ($jenis_filter == 'policy_brief') ? 'selected' : ''; ?>>Policy Brief</option>
                    <option value="buku" <?php echo ($jenis_filter == 'buku') ? 'selected' : ''; ?>>Buku</option>
                    <option value="jurnal" <?php echo ($jenis_filter == 'jurnal') ? 'selected' : ''; ?>>Jurnal</option>
                </select>
            </div>
            
            <!-- Kategori -->
            <div class="form-group" style="margin: 0;">
                <select name="kategori" class="form-control">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($kategori_list as $kat): ?>
                    <option value="<?php echo $kat['slug']; ?>" <?php echo ($kategori_filter == $kat['slug']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Tahun -->
            <div class="form-group" style="margin: 0;">
                <select name="tahun" class="form-control">
                    <option value="">Semua Tahun</option>
                    <?php foreach ($tahun_list as $t): ?>
                    <option value="<?php echo $t['tahun']; ?>" <?php echo ($tahun_filter == $t['tahun']) ? 'selected' : ''; ?>>
                        <?php echo $t['tahun']; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Button -->
            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="<?php echo BASE_URL; ?>/publikasi.php" class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>
</section>

<!-- Publikasi List -->
<section class="py-5">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2>Daftar Publikasi</h2>
            <span style="color: var(--gray-600);">
                Ditemukan <strong><?php echo formatAngka($total_records); ?></strong> publikasi
            </span>
        </div>
        
        <?php if (count($publikasi_list) == 0): ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-search" style="font-size: 4rem; color: var(--gray-300); margin-bottom: 1rem;"></i>
                    <h3>Publikasi Tidak Ditemukan</h3>
                    <p style="color: var(--gray-600);">Coba ubah filter atau kata kunci pencarian</p>
                    <a href="<?php echo BASE_URL; ?>/publikasi.php" class="btn btn-primary mt-3">Reset Filter</a>
                </div>
            </div>
        <?php else: ?>
            
            <div class="stats-grid">
                <?php foreach ($publikasi_list as $pub): ?>
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="badge" style="background-color: var(--secondary-color); color: white; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.875rem;">
                            <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $pub['jenis']))); ?>
                        </span>
                        <?php if ($pub['nama_kategori']): ?>
                        <span style="font-size: 0.875rem; color: var(--gray-600);">
                            <?php echo htmlspecialchars($pub['nama_kategori']); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <h4 style="font-size: 1.125rem; margin-bottom: 0.75rem; line-height: 1.4; min-height: 50px;">
                            <?php echo htmlspecialchars($pub['judul']); ?>
                        </h4>
                        
                        <p style="color: var(--gray-600); font-size: 0.875rem; margin-bottom: 0.5rem;">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($pub['penulis']); ?>
                        </p>
                        
                        <?php if ($pub['abstrak']): ?>
                        <p style="color: var(--gray-600); font-size: 0.875rem; margin-bottom: 1rem; min-height: 60px;">
                            <?php echo htmlspecialchars(substr($pub['abstrak'], 0, 120)); ?>...
                        </p>
                        <?php endif; ?>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; margin-bottom: 1rem; font-size: 0.75rem; color: var(--gray-500);">
                            <div>
                                <i class="fas fa-calendar"></i> <?php echo $pub['tahun']; ?>
                            </div>
                            <div>
                                <i class="fas fa-eye"></i> <?php echo formatAngka($pub['view_count']); ?>x
                            </div>
                            <div>
                                <i class="fas fa-download"></i> <?php echo formatAngka($pub['download_count']); ?>x
                            </div>
                            <?php if ($pub['halaman']): ?>
                            <div>
                                <i class="fas fa-file"></i> <?php echo htmlspecialchars($pub['halaman']); ?> hal
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <a href="<?php echo BASE_URL; ?>/publikasi-detail.php?id=<?php echo $pub['id']; ?>" 
                           class="btn btn-primary btn-sm" style="width: 100%;">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($pagination['total_pages'] > 1): ?>
            <div style="display: flex; justify-content: center; align-items: center; gap: 0.5rem; margin-top: 2rem;">
                <?php if ($pagination['has_prev']): ?>
                <a href="<?php echo $pagination['base_url'] . '&page=' . $pagination['prev_page']; ?>" 
                   class="btn btn-secondary btn-sm">
                    <i class="fas fa-chevron-left"></i> Prev
                </a>
                <?php endif; ?>
                
                <span style="padding: 0 1rem; color: var(--gray-600);">
                    Halaman <?php echo $pagination['current_page']; ?> dari <?php echo $pagination['total_pages']; ?>
                </span>
                
                <?php if ($pagination['has_next']): ?>
                <a href="<?php echo $pagination['base_url'] . '&page=' . $pagination['next_page']; ?>" 
                   class="btn btn-secondary btn-sm">
                    Next <i class="fas fa-chevron-right"></i>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
