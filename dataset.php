<?php
/**
 * =====================================================
 * HALAMAN DATASET / MICRODATA
 * =====================================================
 */

$page_title = "Dataset & Microdata";
$page_description = "Kumpulan dataset dan microdata Kota Kendari";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$db = getDB();

// Get filter dari URL
$kategori_filter = $_GET['kategori'] ?? '';
$tahun_filter = $_GET['tahun'] ?? '';
$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 9;
$offset = ($page - 1) * $per_page;

// Get kategori list
$stmt = $db->query("SELECT * FROM kategori WHERE status = 'aktif' ORDER BY urutan");
$kategori_list = $stmt->fetchAll();

// Get tahun list
$stmt = $db->query("SELECT DISTINCT tahun FROM dataset WHERE status = 'published' ORDER BY tahun DESC");
$tahun_list = $stmt->fetchAll();

// Build query dengan filter
$where_conditions = ["d.status = 'published'"];
$params = [];

if ($kategori_filter) {
    $where_conditions[] = "k.slug = :kategori";
    $params[':kategori'] = $kategori_filter;
}

if ($tahun_filter) {
    $where_conditions[] = "d.tahun = :tahun";
    $params[':tahun'] = $tahun_filter;
}

if ($search) {
    $where_conditions[] = "(d.nama_dataset LIKE :search OR d.deskripsi LIKE :search OR d.tags LIKE :search)";
    $params[':search'] = "%$search%";
}

$where_clause = implode(' AND ', $where_conditions);

// Count total records
$count_sql = "SELECT COUNT(*) as total FROM dataset d 
              JOIN kategori k ON d.kategori_id = k.id 
              WHERE $where_clause";
$stmt = $db->prepare($count_sql);
$stmt->execute($params);
$total_records = $stmt->fetch()['total'];

// Get dataset dengan pagination
$sql = "SELECT d.*, k.nama_kategori, k.slug as kategori_slug 
        FROM dataset d 
        JOIN kategori k ON d.kategori_id = k.id 
        WHERE $where_clause 
        ORDER BY d.created_at DESC 
        LIMIT :limit OFFSET :offset";

$stmt = $db->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$datasets = $stmt->fetchAll();

// Pagination info
$pagination = generatePagination($total_records, $per_page, $page, '/dataset.php?' . http_build_query(array_filter($_GET, function($key) { return $key !== 'page'; }, ARRAY_FILTER_USE_KEY)));
?>

<!-- Page Header -->
<section class="hero" style="padding: 3rem 0;">
    <div class="container">
        <h1>Dataset & Microdata</h1>
        <p>Akses dataset lengkap dengan metadata yang terstruktur</p>
    </div>
</section>

<!-- Filter & Search -->
<section class="py-4" style="background-color: white; box-shadow: var(--shadow-sm);">
    <div class="container">
        <form method="GET" action="<?php echo BASE_URL; ?>/dataset.php" style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
            <!-- Search -->
            <div class="form-group" style="margin: 0;">
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Cari dataset..."
                       value="<?php echo htmlspecialchars($search); ?>">
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
                <a href="<?php echo BASE_URL; ?>/dataset.php" class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>
</section>

<!-- Dataset List -->
<section class="py-5">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2>Daftar Dataset</h2>
            <span style="color: var(--gray-600);">
                Ditemukan <strong><?php echo formatAngka($total_records); ?></strong> dataset
            </span>
        </div>
        
        <?php if (count($datasets) == 0): ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-search" style="font-size: 4rem; color: var(--gray-300); margin-bottom: 1rem;"></i>
                    <h3>Dataset Tidak Ditemukan</h3>
                    <p style="color: var(--gray-600);">Coba ubah filter atau kata kunci pencarian</p>
                    <a href="<?php echo BASE_URL; ?>/dataset.php" class="btn btn-primary mt-3">Reset Filter</a>
                </div>
            </div>
        <?php else: ?>
            
            <div class="stats-grid">
                <?php foreach ($datasets as $dataset): ?>
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="badge" style="background-color: var(--primary-color); color: white; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.875rem;">
                            <?php echo htmlspecialchars($dataset['nama_kategori']); ?>
                        </span>
                        <span style="font-size: 0.875rem; color: var(--gray-600);">
                            <i class="fas fa-calendar"></i> <?php echo $dataset['tahun']; ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <h4 style="font-size: 1.125rem; margin-bottom: 0.75rem; line-height: 1.4;">
                            <?php echo htmlspecialchars($dataset['nama_dataset']); ?>
                        </h4>
                        
                        <p style="color: var(--gray-600); font-size: 0.875rem; margin-bottom: 1rem; min-height: 60px;">
                            <?php echo htmlspecialchars(substr($dataset['deskripsi'] ?? '', 0, 120)); ?>...
                        </p>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; margin-bottom: 1rem; font-size: 0.75rem; color: var(--gray-500);">
                            <div>
                                <i class="fas fa-database"></i> 
                                <?php echo strtoupper($dataset['file_type']); ?>
                            </div>
                            <div>
                                <i class="fas fa-hdd"></i>
                                <?php echo formatFileSize($dataset['file_size'] ?? 0); ?>
                            </div>
                            <div>
                                <i class="fas fa-download"></i>
                                <?php echo formatAngka($dataset['download_count']); ?>x
                            </div>
                            <div>
                                <i class="fas fa-table"></i>
                                <?php echo formatAngka($dataset['jumlah_baris'] ?? 0); ?> baris
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="<?php echo BASE_URL; ?>/dataset-detail.php?id=<?php echo $dataset['id']; ?>" 
                               class="btn btn-primary btn-sm" style="flex: 1;">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <a href="<?php echo BASE_URL; ?>/api/download-dataset.php?id=<?php echo $dataset['id']; ?>" 
                               class="btn btn-success btn-sm">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-footer" style="font-size: 0.75rem; color: var(--gray-500);">
                        <i class="fas fa-building"></i> <?php echo htmlspecialchars($dataset['sumber']); ?>
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
