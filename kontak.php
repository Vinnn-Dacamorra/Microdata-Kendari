<?php
$page_title = "Kontak";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = sanitize($_POST['nama']);
    $email = sanitize($_POST['email']);
    $subjek = sanitize($_POST['subjek']);
    $pesan = sanitize($_POST['pesan']);
    
    if (!empty($nama) && !empty($email) && !empty($subjek) && !empty($pesan)) {
        if (isValidEmail($email)) {
            $stmt = $db->prepare("INSERT INTO kontak (nama, email, subjek, pesan) VALUES (:nama, :email, :subjek, :pesan)");
            $result = $stmt->execute([':nama' => $nama, ':email' => $email, ':subjek' => $subjek, ':pesan' => $pesan]);
            
            if ($result) {
                setFlashMessage('success', 'Pesan berhasil dikirim. Terima kasih!');
            } else {
                setFlashMessage('error', 'Gagal mengirim pesan. Silakan coba lagi.');
            }
        } else {
            setFlashMessage('error', 'Format email tidak valid.');
        }
    } else {
        setFlashMessage('error', 'Semua field wajib diisi.');
    }
    redirect(BASE_URL . '/kontak.php');
}
?>

<section class="hero" style="padding: 3rem 0;">
    <div class="container"><h1>Hubungi Kami</h1><p>Kami siap membantu Anda</p></div>
</section>

<section class="py-5">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            
            <div>
                <h2 class="mb-4">Informasi Kontak</h2>
                
                <div class="card mb-3">
                    <div class="card-body">
                        <h4><i class="fas fa-map-marker-alt"></i> Alamat</h4>
                        <p style="color: var(--gray-600);"><?php echo nl2br($pengaturan['alamat'] ?? ''); ?></p>
                    </div>
                </div>
                
                <div class="card mb-3">
                    <div class="card-body">
                        <h4><i class="fas fa-envelope"></i> Email</h4>
                        <p style="color: var(--gray-600);">
                            <a href="mailto:<?php echo $pengaturan['email_kontak'] ?? ''; ?>">
                                <?php echo $pengaturan['email_kontak'] ?? ''; ?>
                            </a>
                        </p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h4><i class="fas fa-phone"></i> Telepon</h4>
                        <p style="color: var(--gray-600);"><?php echo $pengaturan['telepon'] ?? ''; ?></p>
                    </div>
                </div>
            </div>
            
            <div>
                <h2 class="mb-4">Kirim Pesan</h2>
                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <label class="form-label">Nama Lengkap *</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Subjek *</label>
                                <input type="text" name="subjek" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Pesan *</label>
                                <textarea name="pesan" class="form-control" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                                <i class="fas fa-paper-plane"></i> Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
