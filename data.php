<?php
/**
 * =====================================================
 * HALAMAN DATA & STATISTIK
 * =====================================================
 */

$page_title = "Data & Statistik";
$page_description = "Data statistik Kota Kendari berbagai kategori";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$db = getDB();

// Get kategori dari URL
$kategori_slug = $_GET['kategori'] ?? '';
$kategori_id = null;
$kategori_nama = 'Semua Kategori';

// Get semua kategori
$stmt = $db->query("SELECT * FROM kategori WHERE status = 'aktif' ORDER BY urutan");
$kategori_list = $stmt->fetchAll();

// Jika ada kategori dipilih
if ($kategori_slug) {
    $stmt = $db->prepare("SELECT * FROM kategori WHERE slug = ? AND status = 'aktif'");
    $stmt->execute([$kategori_slug]);
    $kategori = $stmt->fetch();
    
    if ($kategori) {
        $kategori_id = $kategori['id'];
        $kategori_nama = $kategori['nama_kategori'];
    }
}

// Query data statistik
$sql = "SELECT ds.*, k.nama_kategori, k.icon 
        FROM data_statistik ds 
        JOIN kategori k ON ds.kategori_id = k.id 
        WHERE 1=1";

if ($kategori_id) {
    $sql .= " AND ds.kategori_id = :kategori_id";
}

$sql .= " ORDER BY ds.tahun DESC, ds.created_at DESC";

$stmt = $db->prepare($sql);
if ($kategori_id) {
    $stmt->bindParam(':kategori_id', $kategori_id);
}
$stmt->execute();
$data_list = $stmt->fetchAll();

// Siapkan data untuk grafik
$chart_labels = [];
$chart_values = [];
$chart_colors = [];

foreach ($data_list as $index => $item) {
    if ($index < 10) { // Ambil 10 data pertama untuk grafik
        $chart_labels[] = substr($item['judul'], 0, 30) . '...';
        $chart_values[] = floatval($item['nilai']);
        $chart_colors[] = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }
}
?>

<!-- Page Header -->
<section class="hero" style="padding: 3rem 0;">
    <div class="container">
        <h1>Data & Statistik</h1>
        <p>Data statistik Kota Kendari untuk berbagai kategori</p>
    </div>
</section>

<!-- Filter Kategori -->
<section class="py-4" style="background-color: white; border-bottom: 1px solid var(--gray-200);">
    <div class="container">
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; justify-content: center;">
            <a href="<?php echo BASE_URL; ?>/data.php" 
               class="btn <?php echo !$kategori_slug ? 'btn-primary' : 'btn-outline'; ?> btn-sm">
                Semua
            </a>
            <?php foreach ($kategori_list as $kat): ?>
            <a href="<?php echo BASE_URL; ?>/data.php?kategori=<?php echo $kat['slug']; ?>" 
               class="btn <?php echo ($kategori_slug == $kat['slug']) ? 'btn-primary' : 'btn-outline'; ?> btn-sm">
                <i class="fas <?php echo $kat['icon']; ?>"></i>
                <?php echo htmlspecialchars($kat['nama_kategori']); ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Content -->
<section class="py-5">
    <div class="container">
        <h2 class="mb-4"><?php echo htmlspecialchars($kategori_nama); ?></h2>
        
        <?php if (count($data_list) == 0): ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-inbox" style="font-size: 4rem; color: var(--gray-300); margin-bottom: 1rem;"></i>
                    <h3>Belum Ada Data</h3>
                    <p style="color: var(--gray-600);">Data statistik untuk kategori ini belum tersedia</p>
                </div>
            </div>
        <?php else: ?>
            
            <!-- Grafik -->
            <?php if (count($chart_labels) > 0): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h3 style="margin: 0; font-size: 1.25rem;">
                        <i class="fas fa-chart-bar"></i> Visualisasi Data
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="chartStatistik" style="max-height: 400px;"></canvas>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Tabel Data -->
            <div class="card">
                <div class="card-header">
                    <h3 style="margin: 0; font-size: 1.25rem;">
                        <i class="fas fa-table"></i> Tabel Data Statistik
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kategori</th>
                                    <th>Indikator</th>
                                    <th>Tahun</th>
                                    <th>Periode</th>
                                    <th>Nilai</th>
                                    <th>Satuan</th>
                                    <th>Sumber</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data_list as $index => $item): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td>
                                        <span class="badge" style="background-color: var(--primary-color); color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">
                                            <i class="fas <?php echo $item['icon']; ?>"></i>
                                            <?php echo htmlspecialchars($item['nama_kategori']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($item['judul']); ?></strong>
                                        <?php if ($item['deskripsi']): ?>
                                        <br><small style="color: var(--gray-600);"><?php echo htmlspecialchars($item['deskripsi']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $item['tahun']; ?></td>
                                    <td><?php echo htmlspecialchars($item['periode'] ?? '-'); ?></td>
                                    <td><strong><?php echo formatAngka($item['nilai'], 2); ?></strong></td>
                                    <td><?php echo htmlspecialchars($item['satuan'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($item['sumber']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--gray-600);">
                            Total: <strong><?php echo count($data_list); ?></strong> data
                        </span>
                        <div>
                            <button class="btn btn-success btn-sm" onclick="exportToCSV()">
                                <i class="fas fa-file-csv"></i> Export CSV
                            </button>
                            <button class="btn btn-success btn-sm" onclick="exportToExcel()">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Additional JS -->
<?php
$additional_js = <<<JS
<script>
// Data untuk Chart.js
const chartData = {
    labels: <?php echo json_encode($chart_labels); ?>,
    values: <?php echo json_encode($chart_values); ?>,
    colors: <?php echo json_encode($chart_colors); ?>
};

// Buat grafik jika ada data
if (chartData.labels.length > 0) {
    const ctx = document.getElementById('chartStatistik');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Nilai',
                    data: chartData.values,
                    backgroundColor: chartData.colors,
                    borderColor: chartData.colors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Nilai: ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }
}

// Export ke CSV
function exportToCSV() {
    const table = document.querySelector('table');
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let row of rows) {
        let cols = row.querySelectorAll('td, th');
        let csvRow = [];
        for (let col of cols) {
            csvRow.push('"' + col.innerText.replace(/"/g, '""') + '"');
        }
        csv.push(csvRow.join(','));
    }
    
    const csvContent = csv.join('\\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'data_statistik_' + new Date().getTime() + '.csv';
    link.click();
}

// Export ke Excel (HTML table format)
function exportToExcel() {
    const table = document.querySelector('table');
    const html = table.outerHTML;
    const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'data_statistik_' + new Date().getTime() + '.xls';
    link.click();
}
</script>
JS;
?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
