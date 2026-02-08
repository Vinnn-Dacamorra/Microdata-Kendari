<?php
$page_title = "Layanan Data";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="hero" style="padding: 3rem 0;">
    <div class="container"><h1>Layanan Data</h1><p>Ajukan permintaan data sesuai kebutuhan Anda</p></div>
</section>

<section class="py-5">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto;">
            
            <div class="card mb-4" id="request">
                <div class="card-header"><h3 style="margin: 0;"><i class="fas fa-paper-plane"></i> Form Permintaan Data</h3></div>
                <div class="card-body">
                    <form id="requestForm" method="POST">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap *</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Telepon</label>
                            <input type="tel" name="telepon" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Instansi</label>
                            <input type="text" name="instansi" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jenis Instansi</label>
                            <select name="jenis_instansi" class="form-control">
                                <option value="">Pilih</option>
                                <option value="pemerintah">Pemerintah</option>
                                <option value="swasta">Swasta</option>
                                <option value="akademik">Akademik</option>
                                <option value="lsm">LSM</option>
                                <option value="perorangan">Perorangan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kebutuhan Data *</label>
                            <textarea name="kebutuhan" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tujuan Penggunaan</label>
                            <textarea name="tujuan_penggunaan" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                            <i class="fas fa-paper-plane"></i> Kirim Permintaan
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mb-4" id="panduan">
                <div class="card-header"><h3 style="margin: 0;">Panduan Penggunaan</h3></div>
                <div class="card-body">
                    <ol style="line-height: 2;">
                        <li>Isi form permintaan data dengan lengkap</li>
                        <li>Tim kami akan memproses permintaan Anda</li>
                        <li>Anda akan dihubungi melalui email</li>
                        <li>Data akan dikirim setelah verifikasi</li>
                    </ol>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
document.getElementById('requestForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    try {
        const response = await fetch('<?php echo BASE_URL; ?>/api/request-data.php', {
            method: 'POST',
            body: new URLSearchParams(formData)
        });
        const result = await response.json();
        if (result.success) {
            alert('Permintaan berhasil dikirim! ID: ' + result.request_id);
            this.reset();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        alert('Network error: ' + error.message);
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
