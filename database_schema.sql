-- =====================================================
-- MICRODATA KENDARI - DATABASE SCHEMA
-- =====================================================
-- Database: microdata_kendari
-- Description: Portal data dan publikasi ilmiah Kota Kendari
-- Created: 2026
-- =====================================================

-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS microdata_kendari 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE microdata_kendari;

-- =====================================================
-- TABEL USERS (untuk admin panel)
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('admin', 'editor', 'viewer') DEFAULT 'viewer',
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL KATEGORI DATA
-- =====================================================
CREATE TABLE IF NOT EXISTS kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    deskripsi TEXT,
    icon VARCHAR(50),
    urutan INT DEFAULT 0,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL DATA STATISTIK
-- =====================================================
CREATE TABLE IF NOT EXISTS data_statistik (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kategori_id INT NOT NULL,
    judul VARCHAR(255) NOT NULL,
    tahun YEAR NOT NULL,
    periode VARCHAR(50) COMMENT 'Contoh: Q1, Q2, Semester 1, Triwulan 3',
    sumber VARCHAR(200) NOT NULL,
    deskripsi TEXT,
    nilai DECIMAL(20,2) DEFAULT NULL,
    satuan VARCHAR(50) COMMENT 'Contoh: orang, persen, rupiah, unit',
    metadata JSON COMMENT 'Data tambahan dalam format JSON',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_kategori (kategori_id),
    INDEX idx_tahun (tahun),
    INDEX idx_judul (judul)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL DATASET / MICRODATA
-- =====================================================
CREATE TABLE IF NOT EXISTS dataset (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_dataset VARCHAR(255) NOT NULL,
    kategori_id INT NOT NULL,
    sumber VARCHAR(200) NOT NULL,
    metodologi TEXT COMMENT 'Metode pengumpulan data',
    tahun YEAR NOT NULL,
    periode VARCHAR(50),
    deskripsi TEXT,
    file_path VARCHAR(500) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_size INT COMMENT 'Ukuran file dalam bytes',
    file_type VARCHAR(50) COMMENT 'csv, xlsx, json, dll',
    jumlah_baris INT COMMENT 'Jumlah record dalam dataset',
    jumlah_kolom INT COMMENT 'Jumlah variabel/kolom',
    frekuensi_update VARCHAR(50) COMMENT 'Tahunan, Bulanan, Triwulanan',
    lisensi VARCHAR(100) DEFAULT 'Open Data',
    download_count INT DEFAULT 0,
    metadata JSON,
    tags VARCHAR(500) COMMENT 'Tag pencarian, pisahkan dengan koma',
    status ENUM('draft', 'published', 'archived') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_kategori (kategori_id),
    INDEX idx_tahun (tahun),
    INDEX idx_status (status),
    FULLTEXT idx_search (nama_dataset, deskripsi, tags)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL PUBLIKASI
-- =====================================================
CREATE TABLE IF NOT EXISTS publikasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(500) NOT NULL,
    penulis VARCHAR(300) NOT NULL,
    jenis ENUM('laporan', 'artikel', 'infografis', 'policy_brief', 'buku', 'jurnal') NOT NULL,
    kategori_id INT,
    abstrak TEXT,
    tahun YEAR NOT NULL,
    bulan TINYINT COMMENT '1-12',
    file_path VARCHAR(500),
    file_name VARCHAR(255),
    file_size INT,
    cover_image VARCHAR(500),
    doi VARCHAR(100) COMMENT 'Digital Object Identifier',
    isbn VARCHAR(20),
    issn VARCHAR(20),
    halaman VARCHAR(20) COMMENT 'Contoh: 1-50, xii+120',
    penerbit VARCHAR(200),
    download_count INT DEFAULT 0,
    view_count INT DEFAULT 0,
    tags VARCHAR(500),
    status ENUM('draft', 'published', 'archived') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_jenis (jenis),
    INDEX idx_tahun (tahun),
    INDEX idx_status (status),
    FULLTEXT idx_search (judul, penulis, abstrak, tags)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL BERITA & UPDATE
-- =====================================================
CREATE TABLE IF NOT EXISTS berita (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(500) NOT NULL,
    slug VARCHAR(500) NOT NULL UNIQUE,
    isi LONGTEXT NOT NULL,
    ringkasan TEXT,
    gambar VARCHAR(500),
    kategori_berita ENUM('update_data', 'kegiatan', 'kerja_sama', 'pengumuman', 'lainnya') DEFAULT 'update_data',
    tanggal DATE NOT NULL,
    penulis VARCHAR(100),
    view_count INT DEFAULT 0,
    status ENUM('draft', 'published', 'archived') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_tanggal (tanggal),
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    FULLTEXT idx_search (judul, ringkasan, isi)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL PERMINTAAN DATA
-- =====================================================
CREATE TABLE IF NOT EXISTS permintaan_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(200) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telepon VARCHAR(20),
    instansi VARCHAR(200),
    jenis_instansi ENUM('pemerintah', 'swasta', 'akademik', 'lsm', 'perorangan', 'lainnya'),
    kebutuhan TEXT NOT NULL COMMENT 'Deskripsi kebutuhan data',
    data_yang_diminta VARCHAR(500),
    tujuan_penggunaan TEXT,
    periode_data VARCHAR(100) COMMENT 'Tahun atau periode yang diminta',
    format_file VARCHAR(50) DEFAULT 'csv' COMMENT 'csv, xlsx, json, pdf',
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'diproses', 'selesai', 'ditolak') DEFAULT 'pending',
    catatan_admin TEXT,
    diproses_oleh INT,
    tanggal_diproses TIMESTAMP NULL,
    FOREIGN KEY (diproses_oleh) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_tanggal (tanggal),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL KONTAK / PESAN
-- =====================================================
CREATE TABLE IF NOT EXISTS kontak (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(200) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subjek VARCHAR(300) NOT NULL,
    pesan TEXT NOT NULL,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('baru', 'dibaca', 'ditanggapi') DEFAULT 'baru',
    catatan_admin TEXT,
    INDEX idx_status (status),
    INDEX idx_tanggal (tanggal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL LOG DOWNLOAD
-- =====================================================
CREATE TABLE IF NOT EXISTS log_download (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipe ENUM('dataset', 'publikasi', 'data_statistik') NOT NULL,
    item_id INT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_tipe_item (tipe, item_id),
    INDEX idx_tanggal (tanggal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL PENGATURAN WEBSITE
-- =====================================================
CREATE TABLE IF NOT EXISTS pengaturan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kunci VARCHAR(100) NOT NULL UNIQUE,
    nilai TEXT,
    deskripsi VARCHAR(500),
    tipe ENUM('text', 'textarea', 'number', 'boolean', 'json') DEFAULT 'text',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_kunci (kunci)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INSERT DATA AWAL
-- =====================================================

-- Insert user admin default (password: admin123)
INSERT INTO users (username, email, password, nama_lengkap, role, status) VALUES
('admin', 'admin@microdatakendari.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin', 'aktif'),
('editor', 'editor@microdatakendari.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Editor Data', 'editor', 'aktif');

-- Insert kategori
INSERT INTO kategori (nama_kategori, slug, deskripsi, icon, urutan, status) VALUES
('Kependudukan', 'kependudukan', 'Data jumlah penduduk, demografi, migrasi, dan vital statistik', 'fa-users', 1, 'aktif'),
('Ekonomi', 'ekonomi', 'Data PDRB, inflasi, kemiskinan, tenaga kerja, dan ekonomi regional', 'fa-chart-line', 2, 'aktif'),
('Pendidikan', 'pendidikan', 'Data sekolah, siswa, guru, dan capaian pendidikan', 'fa-graduation-cap', 3, 'aktif'),
('Kesehatan', 'kesehatan', 'Data fasilitas kesehatan, tenaga medis, dan indikator kesehatan', 'fa-heartbeat', 4, 'aktif'),
('Infrastruktur', 'infrastruktur', 'Data jalan, jembatan, dan infrastruktur publik', 'fa-road', 5, 'aktif'),
('Sosial', 'sosial', 'Data kemiskinan, kesejahteraan sosial, dan pembangunan sosial', 'fa-hand-holding-heart', 6, 'aktif'),
('Lingkungan', 'lingkungan', 'Data lingkungan hidup, kualitas udara, dan pengelolaan sampah', 'fa-leaf', 7, 'aktif');

-- Insert pengaturan website
INSERT INTO pengaturan (kunci, nilai, deskripsi, tipe) VALUES
('nama_website', 'Microdata Kendari', 'Nama website', 'text'),
('tagline', 'Portal Data dan Publikasi Ilmiah Kota Kendari', 'Tagline website', 'text'),
('email_kontak', 'kontak@microdatakendari.id', 'Email kontak', 'text'),
('telepon', '(0401) 123456', 'Nomor telepon', 'text'),
('alamat', 'Jl. Contoh No. 123, Kendari, Sulawesi Tenggara', 'Alamat kantor', 'textarea'),
('deskripsi', 'Microdata Kendari adalah portal data dan publikasi ilmiah yang menyediakan akses terbuka terhadap data mikro, statistik, dan publikasi ilmiah Kota Kendari', 'Deskripsi singkat', 'textarea'),
('facebook', 'https://facebook.com/microdatakendari', 'Link Facebook', 'text'),
('twitter', 'https://twitter.com/microdatakdr', 'Link Twitter', 'text'),
('instagram', 'https://instagram.com/microdatakendari', 'Link Instagram', 'text'),
('linkedin', 'https://linkedin.com/company/microdata-kendari', 'Link LinkedIn', 'text');

-- Insert contoh data statistik
INSERT INTO data_statistik (kategori_id, judul, tahun, periode, sumber, deskripsi, nilai, satuan, created_by) VALUES
(1, 'Jumlah Penduduk Kota Kendari', 2024, 'Tahunan', 'BPS Kota Kendari', 'Total jumlah penduduk Kota Kendari berdasarkan proyeksi', 385678, 'jiwa', 1),
(1, 'Laju Pertumbuhan Penduduk', 2024, 'Tahunan', 'BPS Kota Kendari', 'Persentase pertumbuhan penduduk per tahun', 2.45, 'persen', 1),
(2, 'PDRB Atas Dasar Harga Berlaku', 2023, 'Tahunan', 'BPS Kota Kendari', 'Produk Domestik Regional Bruto', 34567890, 'juta rupiah', 1),
(2, 'Tingkat Pengangguran Terbuka', 2024, 'Semester 1', 'BPS Kota Kendari', 'Persentase pengangguran terbuka', 6.78, 'persen', 1),
(3, 'Angka Partisipasi Sekolah (APS) SD', 2024, 'Tahunan', 'Dinas Pendidikan Kota Kendari', 'Persentase anak usia SD yang bersekolah', 99.45, 'persen', 1),
(4, 'Jumlah Puskesmas', 2024, 'Tahunan', 'Dinas Kesehatan Kota Kendari', 'Total puskesmas di Kota Kendari', 12, 'unit', 1);

-- Insert contoh dataset
INSERT INTO dataset (nama_dataset, kategori_id, sumber, metodologi, tahun, deskripsi, file_path, file_name, file_type, frekuensi_update, lisensi, tags, created_by) VALUES
('Data Penduduk Per Kecamatan 2024', 1, 'BPS Kota Kendari', 'Proyeksi Penduduk', 2024, 'Dataset lengkap jumlah penduduk per kecamatan di Kota Kendari tahun 2024', '/uploads/datasets/penduduk_kecamatan_2024.csv', 'penduduk_kecamatan_2024.csv', 'csv', 'Tahunan', 'CC BY 4.0', 'penduduk, kecamatan, demografi', 1),
('Indikator Ekonomi Kota Kendari 2023', 2, 'BPS Kota Kendari', 'Survei dan Kompilasi Data', 2023, 'Kumpulan indikator ekonomi makro Kota Kendari', '/uploads/datasets/ekonomi_2023.xlsx', 'ekonomi_2023.xlsx', 'xlsx', 'Tahunan', 'Open Data', 'ekonomi, pdrb, inflasi', 1);

-- Insert contoh publikasi
INSERT INTO publikasi (judul, penulis, jenis, kategori_id, abstrak, tahun, file_path, file_name, penerbit, tags, created_by) VALUES
('Kendari Dalam Angka 2024', 'BPS Kota Kendari', 'buku', 1, 'Publikasi komprehensif statistik Kota Kendari tahun 2024', 2024, '/uploads/publikasi/kda_2024.pdf', 'kda_2024.pdf', 'BPS Kota Kendari', 'statistik, tahunan', 1),
('Analisis Kemiskinan Kota Kendari', 'Tim Peneliti BAPPEDA', 'artikel', 2, 'Analisis mendalam tentang kemiskinan dan strategi penanggulangan', 2023, '/uploads/publikasi/analisis_kemiskinan.pdf', 'analisis_kemiskinan.pdf', 'BAPPEDA Kota Kendari', 'kemiskinan, ekonomi', 1);

-- Insert contoh berita
INSERT INTO berita (judul, slug, isi, ringkasan, kategori_berita, tanggal, penulis, created_by) VALUES
('Peluncuran Portal Microdata Kendari', 'peluncuran-portal-microdata-kendari', 'Kota Kendari meluncurkan portal data resmi untuk meningkatkan transparansi dan aksesibilitas data...', 'Portal Microdata Kendari resmi diluncurkan untuk publik', 'pengumuman', '2024-01-15', 'Admin', 1),
('Update Data Statistik Q4 2023', 'update-data-statistik-q4-2023', 'Data statistik triwulan 4 tahun 2023 telah tersedia di portal...', 'Data statistik terbaru telah diupdate', 'update_data', '2024-01-20', 'Admin', 1);

-- =====================================================
-- CREATE VIEWS (untuk memudahkan query)
-- =====================================================

-- View: Statistik dataset per kategori
CREATE OR REPLACE VIEW v_dataset_per_kategori AS
SELECT 
    k.id,
    k.nama_kategori,
    k.slug,
    COUNT(d.id) as jumlah_dataset,
    SUM(d.download_count) as total_download
FROM kategori k
LEFT JOIN dataset d ON k.id = d.kategori_id AND d.status = 'published'
GROUP BY k.id, k.nama_kategori, k.slug;

-- View: Publikasi terbaru
CREATE OR REPLACE VIEW v_publikasi_terbaru AS
SELECT 
    p.*,
    k.nama_kategori,
    u.nama_lengkap as nama_creator
FROM publikasi p
LEFT JOIN kategori k ON p.kategori_id = k.id
LEFT JOIN users u ON p.created_by = u.id
WHERE p.status = 'published'
ORDER BY p.created_at DESC;

-- View: Dataset populer
CREATE OR REPLACE VIEW v_dataset_populer AS
SELECT 
    d.*,
    k.nama_kategori
FROM dataset d
JOIN kategori k ON d.kategori_id = k.id
WHERE d.status = 'published'
ORDER BY d.download_count DESC
LIMIT 10;

-- =====================================================
-- CREATE STORED PROCEDURES
-- =====================================================

DELIMITER //

-- Procedure: Update download count
CREATE PROCEDURE sp_update_download_count(
    IN p_tipe VARCHAR(50),
    IN p_item_id INT
)
BEGIN
    IF p_tipe = 'dataset' THEN
        UPDATE dataset SET download_count = download_count + 1 WHERE id = p_item_id;
    ELSEIF p_tipe = 'publikasi' THEN
        UPDATE publikasi SET download_count = download_count + 1 WHERE id = p_item_id;
    END IF;
END//

-- Procedure: Get statistik dashboard
CREATE PROCEDURE sp_get_dashboard_stats()
BEGIN
    SELECT 
        (SELECT COUNT(*) FROM dataset WHERE status = 'published') as total_dataset,
        (SELECT COUNT(*) FROM publikasi WHERE status = 'published') as total_publikasi,
        (SELECT COUNT(*) FROM data_statistik) as total_data_statistik,
        (SELECT COUNT(*) FROM permintaan_data WHERE status = 'pending') as permintaan_pending,
        (SELECT SUM(download_count) FROM dataset) as total_download_dataset,
        (SELECT SUM(download_count) FROM publikasi) as total_download_publikasi;
END//

DELIMITER ;

-- =====================================================
-- INDEXES TAMBAHAN UNTUK OPTIMASI
-- =====================================================
CREATE INDEX idx_created_at ON data_statistik(created_at);
CREATE INDEX idx_created_at ON dataset(created_at);
CREATE INDEX idx_created_at ON publikasi(created_at);
CREATE INDEX idx_download_count ON dataset(download_count);
CREATE INDEX idx_download_count ON publikasi(download_count);

-- =====================================================
-- SELESAI
-- =====================================================
