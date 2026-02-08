# PENTING! KONFIGURASI BASE PATH

## 🔧 Konfigurasi Lokasi Folder

Jika Anda menempatkan folder project **BUKAN** di `/microdata-kendari`, Anda perlu mengubah konfigurasi BASE_URL.

---

## 📍 Edit File: `config/database.php`

Cari bagian ini di **baris 11**:

```php
$base_path = '/microdata-kendari'; // Sesuaikan dengan folder Anda
```

### Contoh Konfigurasi:

#### 1. Jika folder di: `C:\xampp\htdocs\microdata-kendari`
```php
$base_path = '/microdata-kendari'; // TIDAK PERLU DIUBAH
```
URL: `http://localhost/microdata-kendari`

#### 2. Jika folder di: `C:\xampp\htdocs\project-kendari`
```php
$base_path = '/project-kendari';
```
URL: `http://localhost/project-kendari`

#### 3. Jika folder di: `C:\xampp\htdocs\` (root htdocs)
```php
$base_path = '';
```
URL: `http://localhost`

#### 4. Jika folder di: `C:\xampp\htdocs\apps\microdata`
```php
$base_path = '/apps/microdata';
```
URL: `http://localhost/apps/microdata`

---

## ✅ Cara Cek Sudah Benar

1. Buka browser
2. Akses website Anda
3. **Jika CSS/JS tidak load**, cek:
   - Klik kanan > Inspect Element (F12)
   - Tab "Network" 
   - Lihat apakah ada error 404 pada file CSS/JS
   - Jika ada 404, berarti BASE_URL salah

4. **Contoh error yang sering terjadi:**
   ```
   GET http://localhost/assets/css/style.css [404 Not Found]
   ```
   Artinya: BASE_URL belum di-set dengan benar

---

## 🎯 Solusi Praktis

### Metode 1: Edit Langsung (Recommended)
Edit `config/database.php` baris 11:
```php
$base_path = '/NAMA_FOLDER_ANDA';
```

### Metode 2: Rename Folder
Rename folder project Anda menjadi `microdata-kendari` agar tidak perlu edit konfigurasi.

---

## 🧪 Testing

Setelah konfigurasi, test dengan:

1. **Homepage:** `http://localhost/NAMA_FOLDER/index.php`
   - Apakah CSS tampil dengan baik?
   - Apakah menu navigasi berfungsi?

2. **Admin Login:** `http://localhost/NAMA_FOLDER/admin/login.php`
   - Apakah halaman login tampil dengan styling?

3. **Klik Menu:**
   - Data & Statistik
   - Dataset
   - Apakah link menuju halaman yang benar?

---

## ❓ FAQ

**Q: Kenapa CSS tidak muncul?**
A: Path BASE_URL salah. Edit `config/database.php` baris 11.

**Q: Menu link tidak berfungsi (404)?**
A: BASE_URL tidak sesuai dengan lokasi folder. Cek konfigurasi.

**Q: Halaman blank/error?**
A: Pastikan database sudah di-import dan konfigurasi database benar.

---

## 🆘 Troubleshooting

Jika masih bermasalah:

1. Cek file `config/database.php` baris 11
2. Pastikan nama folder sesuai dengan `$base_path`
3. Restart Apache/Nginx
4. Clear browser cache (Ctrl+Shift+R)
5. Cek console browser (F12) untuk error

---

Semoga membantu! 🚀
