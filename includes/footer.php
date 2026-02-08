    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><?php echo $nama_website; ?></h3>
                    <p><?php echo $pengaturan['deskripsi'] ?? ''; ?></p>
                    <div class="social-links">
                        <?php if (!empty($pengaturan['facebook'])): ?>
                        <a href="<?php echo $pengaturan['facebook']; ?>" target="_blank" aria-label="Facebook">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($pengaturan['twitter'])): ?>
                        <a href="<?php echo $pengaturan['twitter']; ?>" target="_blank" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($pengaturan['instagram'])): ?>
                        <a href="<?php echo $pengaturan['instagram']; ?>" target="_blank" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($pengaturan['linkedin'])): ?>
                        <a href="<?php echo $pengaturan['linkedin']; ?>" target="_blank" aria-label="LinkedIn">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Link Cepat</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo BASE_URL; ?>/data.php"><i class="fas fa-angle-right"></i> Data & Statistik</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/dataset.php"><i class="fas fa-angle-right"></i> Dataset</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/publikasi.php"><i class="fas fa-angle-right"></i> Publikasi</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/peta.php"><i class="fas fa-angle-right"></i> Peta Visualisasi</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Layanan</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo BASE_URL; ?>/layanan.php"><i class="fas fa-angle-right"></i> Permintaan Data</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/layanan.php#panduan"><i class="fas fa-angle-right"></i> Panduan Penggunaan</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/layanan.php#faq"><i class="fas fa-angle-right"></i> FAQ</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/layanan.php#lisensi"><i class="fas fa-angle-right"></i> Ketentuan & Lisensi</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Kontak</h4>
                    <ul class="footer-contact">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo nl2br($pengaturan['alamat'] ?? ''); ?></span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:<?php echo $pengaturan['email_kontak'] ?? ''; ?>">
                                <?php echo $pengaturan['email_kontak'] ?? ''; ?>
                            </a>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <span><?php echo $pengaturan['telepon'] ?? ''; ?></span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-copyright">
                    <p>&copy; <?php echo date('Y'); ?> <?php echo $nama_website; ?>. All Rights Reserved.</p>
                    <p class="footer-license">
                        <i class="fas fa-creative-commons"></i>
                        Data dilisensikan di bawah <a href="https://creativecommons.org/licenses/by/4.0/" target="_blank">CC BY 4.0</a>
                    </p>
                </div>
                <div class="footer-credits">
                    <p>Powered by <strong>Microdata Kendari Team</strong></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" class="back-to-top" title="Kembali ke atas">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- JavaScript -->
    <script src="<?php echo ASSETS_URL; ?>/js/main.js"></script>
    
    <!-- Additional JS if needed -->
    <?php if (isset($additional_js)): ?>
        <?php echo $additional_js; ?>
    <?php endif; ?>
    
    <script>
        // Back to top button
        const backToTopButton = document.getElementById('backToTop');
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('visible');
            } else {
                backToTopButton.classList.remove('visible');
            }
        });
        
        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        // Auto hide flash message after 5 seconds
        const flashMessage = document.querySelector('.flash-message');
        if (flashMessage) {
            setTimeout(function() {
                flashMessage.style.opacity = '0';
                setTimeout(function() {
                    flashMessage.style.display = 'none';
                }, 300);
            }, 5000);
        }
    </script>
</body>
</html>
