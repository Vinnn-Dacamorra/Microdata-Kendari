<?php
$page_title = "Peta & Visualisasi";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="hero" style="padding: 3rem 0;">
    <div class="container"><h1>Peta & Visualisasi</h1><p>Visualisasi data spasial Kota Kendari</p></div>
</section>

<section class="py-5">
    <div class="container">
        <div class="card mb-4">
            <div class="card-body text-center" style="padding: 4rem 2rem;">
                <i class="fas fa-map-marked-alt" style="font-size: 5rem; color: var(--gray-300); margin-bottom: 2rem;"></i>
                <h2>Fitur Peta Segera Hadir</h2>
                <p style="color: var(--gray-600); max-width: 600px; margin: 1rem auto 2rem;">
                    Kami sedang mengembangkan fitur peta interaktif dengan Leaflet.js untuk memvisualisasikan
                    data geografis Kota Kendari
                </p>
                <a href="<?php echo BASE_URL; ?>/index.php" class="btn btn-primary">
                    <i class="fas fa-home"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
