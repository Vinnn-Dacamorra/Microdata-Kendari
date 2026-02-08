<?php
/**
 * =====================================================
 * HALAMAN TENTANG KAMI
 * =====================================================
 */

$page_title = "Tentang Kami";
$page_description = "Tentang Microdata Kendari - Portal Data dan Publikasi Ilmiah";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Hero Section -->
<section class="hero" style="padding: 3rem 0;">
    <div class="container">
        <h1>Tentang Kami</h1>
        <p>Mengenal Microdata Kendari lebih dekat</p>
    </div>
</section>

<!-- Profile Section -->
<section class="py-5" style="background-color: white;">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto;">
            <h2 class="text-center mb-4">Profil Microdata Kendari</h2>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h3><i class="fas fa-building"></i> Sekilas Tentang Kami</h3>
                    <p style="line-height: 1.8;">
                        <strong>Microdata Kendari</strong> adalah portal data dan publikasi ilmiah yang menyediakan 
                        akses terbuka terhadap data mikro, statistik, dataset, dan publikasi ilmiah Kota Kendari. 
                        Portal ini dikelola dengan tujuan meningkatkan transparansi, aksesibilitas data, dan 
                        mendukung pengambilan keputusan berbasis data.
                    </p>
                    <p style="line-height: 1.8;">
                        Kami berkomitmen untuk menyediakan data yang akurat, terkini, dan mudah diakses oleh 
                        berbagai kalangan termasuk mahasiswa, peneliti, akademisi, praktisi, instansi pemerintah, 
                        dan masyarakat umum.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latar Belakang -->
<section class="py-5">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto;">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 style="margin: 0;"><i class="fas fa-history"></i> Latar Belakang</h3>
                </div>
                <div class="card-body">
                    <p style="line-height: 1.8;">
                        Kebutuhan akan data yang terpercaya dan mudah diakses semakin meningkat di era digital ini. 
                        Banyak stakeholder memerlukan data untuk penelitian, analisis kebijakan, dan pengambilan keputusan. 
                        Namun, data sering kali tersebar di berbagai sumber dan sulit diakses.
                    </p>
                    <p style="line-height: 1.8;">
                        Berangkat dari kondisi tersebut, <strong>Microdata Kendari</strong> hadir sebagai solusi 
                        untuk mengintegrasikan, mengelola, dan menyebarluaskan data Kota Kendari secara terstruktur 
                        dan bertanggung jawab. Portal ini dibangun dengan prinsip <em>Open Data</em> untuk mendorong 
                        transparansi dan partisipasi publik.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Visi Misi -->
<section class="py-5" style="background-color: white;">
    <div class="container">
        <h2 class="text-center mb-5">Visi & Misi</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; max-width: 900px; margin: 0 auto;">
            <!-- Visi -->
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white;">
                    <h3 style="margin: 0; color: white;"><i class="fas fa-eye"></i> Visi</h3>
                </div>
                <div class="card-body">
                    <p style="font-size: 1.125rem; line-height: 1.8; text-align: center; font-style: italic;">
                        "Menjadi portal data terdepan yang menyediakan akses data berkualitas untuk 
                        mendukung pembangunan Kota Kendari yang berbasis data dan bukti ilmiah."
                    </p>
                </div>
            </div>
            
            <!-- Misi -->
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, var(--secondary-color), #059669); color: white;">
                    <h3 style="margin: 0; color: white;"><i class="fas fa-bullseye"></i> Misi</h3>
                </div>
                <div class="card-body">
                    <ol style="line-height: 2; padding-left: 1.5rem;">
                        <li>Menyediakan data dan statistik yang akurat dan terkini</li>
                        <li>Memfasilitasi akses terbuka terhadap data publik</li>
                        <li>Mendukung penelitian dan pengembangan ilmu pengetahuan</li>
                        <li>Meningkatkan transparansi dan akuntabilitas pemerintahan</li>
                        <li>Mendorong pengambilan keputusan berbasis data</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tujuan -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Tujuan Portal</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            <div class="card">
                <div class="card-body text-center">
                    <div style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;">
                        <i class="fas fa-database"></i>
                    </div>
                    <h4>Data Terpusat</h4>
                    <p style="color: var(--gray-600);">
                        Mengintegrasikan data dari berbagai sumber dalam satu platform
                    </p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body text-center">
                    <div style="font-size: 3rem; color: var(--secondary-color); margin-bottom: 1rem;">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4>Akses Mudah</h4>
                    <p style="color: var(--gray-600);">
                        Memudahkan publik mengakses data secara online kapan saja
                    </p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body text-center">
                    <div style="font-size: 3rem; color: var(--accent-color); margin-bottom: 1rem;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4>Kualitas Data</h4>
                    <p style="color: var(--gray-600);">
                        Menjamin kualitas, validitas, dan reliabilitas data
                    </p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body text-center">
                    <div style="font-size: 3rem; color: var(--info-color); margin-bottom: 1rem;">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h4>Riset & Edukasi</h4>
                    <p style="color: var(--gray-600);">
                        Mendukung penelitian dan pengembangan kapasitas
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tim Pengelola -->
<section class="py-5" style="background-color: white;">
    <div class="container">
        <h2 class="text-center mb-5">Tim Pengelola</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; max-width: 1000px; margin: 0 auto;">
            <div class="card text-center">
                <div class="card-body">
                    <div style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem;">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h4>Koordinator</h4>
                    <p style="color: var(--gray-600); margin-bottom: 0.5rem;">Pengelolaan Portal</p>
                </div>
            </div>
            
            <div class="card text-center">
                <div class="card-body">
                    <div style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--secondary-color), #059669); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem;">
                        <i class="fas fa-database"></i>
                    </div>
                    <h4>Data Analyst</h4>
                    <p style="color: var(--gray-600); margin-bottom: 0.5rem;">Analisis & Validasi Data</p>
                </div>
            </div>
            
            <div class="card text-center">
                <div class="card-body">
                    <div style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--accent-color), #d97706); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem;">
                        <i class="fas fa-code"></i>
                    </div>
                    <h4>Developer</h4>
                    <p style="color: var(--gray-600); margin-bottom: 0.5rem;">Pengembangan Sistem</p>
                </div>
            </div>
            
            <div class="card text-center">
                <div class="card-body">
                    <div style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--info-color), #1e40af); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem;">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h4>Support</h4>
                    <p style="color: var(--gray-600); margin-bottom: 0.5rem;">Layanan Pengguna</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white;">
    <div class="container text-center">
        <h2 style="color: white; margin-bottom: 1rem;">Mulai Jelajahi Data</h2>
        <p style="font-size: 1.125rem; margin-bottom: 2rem; opacity: 0.9;">
            Akses ribuan data dan publikasi ilmiah Kota Kendari secara gratis
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="<?php echo BASE_URL; ?>/dataset.php" class="btn btn-lg" style="background-color: white; color: var(--primary-color);">
                <i class="fas fa-database"></i> Lihat Dataset
            </a>
            <a href="<?php echo BASE_URL; ?>/data.php" class="btn btn-lg btn-outline" style="border-color: white; color: white;">
                <i class="fas fa-chart-bar"></i> Data & Statistik
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
