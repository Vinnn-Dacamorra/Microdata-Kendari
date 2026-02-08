    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <a href="<?php echo BASE_URL; ?>/index.php" class="logo">
                    <img src="<?php echo ASSETS_URL; ?>/images/logo.png" alt="<?php echo $nama_website; ?>" onerror="this.style.display='none'">
                    <div class="logo-text">
                        <h1><?php echo $nama_website; ?></h1>
                        <p><?php echo $tagline; ?></p>
                    </div>
                </a>
                <button class="navbar-toggler" id="navbarToggler">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <div class="navbar-menu" id="navbarMenu">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/index.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                            <i class="fas fa-home"></i> Beranda
                        </a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">
                            <i class="fas fa-database"></i> Data & Statistik
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo BASE_URL; ?>/data.php?kategori=kependudukan" class="dropdown-item">Kependudukan</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/data.php?kategori=ekonomi" class="dropdown-item">Ekonomi</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/data.php?kategori=pendidikan" class="dropdown-item">Pendidikan</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/data.php?kategori=kesehatan" class="dropdown-item">Kesehatan</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/data.php?kategori=infrastruktur" class="dropdown-item">Infrastruktur</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/data.php?kategori=sosial" class="dropdown-item">Sosial</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/data.php?kategori=lingkungan" class="dropdown-item">Lingkungan</a></li>
                            <li class="dropdown-divider"></li>
                            <li><a href="<?php echo BASE_URL; ?>/data.php" class="dropdown-item"><strong>Lihat Semua Data</strong></a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/dataset.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dataset.php') ? 'active' : ''; ?>">
                            <i class="fas fa-table"></i> Dataset
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/publikasi.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'publikasi.php') ? 'active' : ''; ?>">
                            <i class="fas fa-book"></i> Publikasi
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/peta.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'peta.php') ? 'active' : ''; ?>">
                            <i class="fas fa-map-marked-alt"></i> Peta
                        </a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">
                            <i class="fas fa-info-circle"></i> Tentang
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo BASE_URL; ?>/about.php" class="dropdown-item">Tentang Kami</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/layanan.php" class="dropdown-item">Layanan Data</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/berita.php" class="dropdown-item">Berita & Update</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/kontak.php" class="dropdown-item">Kontak</a></li>
                        </ul>
                    </li>
                </ul>
                
                <div class="navbar-actions">
                    <a href="<?php echo BASE_URL; ?>/layanan.php#request" class="btn btn-primary">
                        <i class="fas fa-download"></i> Minta Data
                    </a>
                    <?php if (isLoggedIn()): ?>
                        <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="btn btn-secondary">
                            <i class="fas fa-user-shield"></i> Admin
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <script>
        // Mobile menu toggle
        document.getElementById('navbarToggler').addEventListener('click', function() {
            document.getElementById('navbarMenu').classList.toggle('active');
        });

        // Dropdown toggle
        document.querySelectorAll('.dropdown-toggle').forEach(function(element) {
            element.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.parentElement;
                parent.classList.toggle('active');
            });
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const navbar = document.querySelector('.navbar');
            const isClickInside = navbar.contains(event.target);
            
            if (!isClickInside) {
                document.getElementById('navbarMenu').classList.remove('active');
                document.querySelectorAll('.dropdown').forEach(function(dropdown) {
                    dropdown.classList.remove('active');
                });
            }
        });
    </script>
