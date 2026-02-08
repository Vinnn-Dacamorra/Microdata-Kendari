# PANDUAN INSTALASI MICRODATA KENDARI
## Langkah-Langkah Instalasi Lengkap

---

## PERSIAPAN

### 1. Persyaratan Sistem

Pastikan sistem Anda memenuhi persyaratan berikut:

**Software yang Dibutuhkan:**
- PHP 8.0 atau lebih tinggi
- MySQL 8.0 atau lebih tinggi (atau MariaDB 10.5+)
- Web Server (Apache 2.4+ atau Nginx 1.18+)
- Composer (optional, untuk dependency management)

**PHP Extensions yang Dibutuhkan:**
- PDO
- PDO_MySQL
- mbstring
- json
- fileinfo
- openssl
- xml

**Cara Cek PHP Extensions:**
```bash
php -m
```

### 2. Download/Clone Project

**Option A: Clone dari Git**
```bash
git clone https://github.com/your-repo/microdata-kendari.git
cd microdata-kendari
```

**Option B: Download ZIP**
- Download ZIP dari repository
- Extract ke folder web server (htdocs untuk XAMPP, www untuk WAMP, atau /var/www/html untuk Linux)

---

## INSTALASI STEP-BY-STEP

### STEP 1: Setup Database

#### A. Buat Database

**Melalui phpMyAdmin:**
1. Buka http://localhost/phpmyadmin
2. Klik tab "Databases"
3. Masukkan nama database: `microdata_kendari`
4. Pilih collation: `utf8mb4_unicode_ci`
5. Klik "Create"

**Melalui MySQL Command Line:**
```bash
mysql -u root -p
```
```sql
CREATE DATABASE microdata_kendari CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

#### B. Import Schema Database

**Melalui phpMyAdmin:**
1. Pilih database `microdata_kendari`
2. Klik tab "Import"
3. Klik "Choose File" dan pilih file `database_schema.sql`
4. Klik "Go"

**Melalui Command Line:**
```bash
mysql -u root -p microdata_kendari < database_schema.sql
```

#### C. Verifikasi Import
```sql
USE microdata_kendari;
SHOW TABLES;
```

Anda harus melihat tabel-tabel berikut:
- users
- kategori
- data_statistik
- dataset
- publikasi
- berita
- permintaan_data
- kontak
- log_download
- pengaturan

### STEP 2: Konfigurasi Koneksi Database

Edit file `config/database.php`:

```php
define('DB_HOST', 'localhost');        // Host database (biasanya localhost)
define('DB_NAME', 'microdata_kendari'); // Nama database
define('DB_USER', 'root');              // Username MySQL
define('DB_PASS', '');                  // Password MySQL (kosong untuk default XAMPP/WAMP)
define('DB_CHARSET', 'utf8mb4');       // Jangan diubah
```

**Untuk Production:**
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'microdata_kendari');
define('DB_USER', 'microdata_user');
define('DB_PASS', 'password_yang_kuat');
define('DB_CHARSET', 'utf8mb4');
```

### STEP 3: Set File Permissions

**Untuk Linux/Mac:**
```bash
# Masuk ke folder project
cd /path/to/microdata-kendari

# Set permission untuk folder uploads
chmod 755 uploads/
chmod 755 uploads/datasets/
chmod 755 uploads/publikasi/

# Set permission untuk config (jika diperlukan)
chmod 644 config/database.php
```

**Untuk Windows (XAMPP/WAMP):**
- Tidak perlu setting permission khusus
- Pastikan folder uploads dapat ditulis

### STEP 4: Konfigurasi Web Server

#### A. XAMPP (Windows/Mac)

1. **Copy folder project ke htdocs:**
   ```
   C:\xampp\htdocs\microdata-kendari
   ```

2. **Akses melalui browser:**
   ```
   http://localhost/microdata-kendari
   ```

3. **Setup Virtual Host (Optional):**
   
   Edit file `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:
   ```apache
   <VirtualHost *:80>
       ServerName microdatakendari.local
       DocumentRoot "C:/xampp/htdocs/microdata-kendari"
       <Directory "C:/xampp/htdocs/microdata-kendari">
           Options Indexes FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```
   
   Edit file `C:\Windows\System32\drivers\etc\hosts`:
   ```
   127.0.0.1 microdatakendari.local
   ```
   
   Restart Apache, kemudian akses:
   ```
   http://microdatakendari.local
   ```

#### B. WAMP (Windows)

1. **Copy folder project ke www:**
   ```
   C:\wamp64\www\microdata-kendari
   ```

2. **Akses melalui browser:**
   ```
   http://localhost/microdata-kendari
   ```

#### C. Linux (Apache)

1. **Copy project ke /var/www/html:**
   ```bash
   sudo cp -r /path/to/microdata-kendari /var/www/html/
   sudo chown -R www-data:www-data /var/www/html/microdata-kendari
   ```

2. **Setup Virtual Host:**
   
   Buat file `/etc/apache2/sites-available/microdata-kendari.conf`:
   ```apache
   <VirtualHost *:80>
       ServerName microdatakendari.local
       ServerAdmin admin@microdatakendari.local
       DocumentRoot /var/www/html/microdata-kendari
       
       <Directory /var/www/html/microdata-kendari>
           Options Indexes FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>
       
       ErrorLog ${APACHE_LOG_DIR}/microdata_error.log
       CustomLog ${APACHE_LOG_DIR}/microdata_access.log combined
   </VirtualHost>
   ```
   
   Enable site dan restart Apache:
   ```bash
   sudo a2ensite microdata-kendari.conf
   sudo a2enmod rewrite
   sudo systemctl restart apache2
   ```
   
   Edit `/etc/hosts`:
   ```
   127.0.0.1 microdatakendari.local
   ```

### STEP 5: Test Installation

1. **Buka browser dan akses:**
   ```
   http://localhost/microdata-kendari
   atau
   http://microdatakendari.local
   ```

2. **Halaman beranda harus tampil dengan:**
   - Hero section
   - Statistik (Dataset, Publikasi, Data Statistik)
   - Dataset terbaru
   - Publikasi terbaru

3. **Test Admin Panel:**
   ```
   http://localhost/microdata-kendari/admin/login.php
   ```
   
   **Login dengan:**
   - Username: `admin`
   - Password: `admin123`

### STEP 6: Konfigurasi Tambahan

#### A. Ubah Password Admin (WAJIB!)

1. Login ke MySQL:
   ```bash
   mysql -u root -p microdata_kendari
   ```

2. Generate password baru:
   ```bash
   php -r "echo password_hash('password_baru_anda', PASSWORD_DEFAULT);"
   ```

3. Update password:
   ```sql
   UPDATE users 
   SET password = 'HASIL_HASH_DARI_STEP_2' 
   WHERE username = 'admin';
   ```

#### B. Update Pengaturan Website

Login ke admin panel dan update pengaturan di database `pengaturan`:
- nama_website
- email_kontak
- telepon
- alamat
- social media links

#### C. Upload Logo (Optional)

Upload logo Anda ke `/assets/images/logo.png`

---

## TROUBLESHOOTING

### Problem 1: "Database connection failed"

**Penyebab:**
- MySQL service tidak berjalan
- Username/password salah
- Database belum dibuat

**Solusi:**
```bash
# Start MySQL service
# Windows (XAMPP): Start dari XAMPP Control Panel
# Linux:
sudo systemctl start mysql

# Test koneksi
mysql -u root -p
```

### Problem 2: "Permission denied" saat upload

**Penyebab:**
- Folder uploads tidak memiliki permission write

**Solusi:**
```bash
# Linux/Mac:
chmod 755 uploads/
chmod 755 uploads/datasets/
chmod 755 uploads/publikasi/

# Windows: 
# Right-click folder > Properties > Security > Edit permissions
```

### Problem 3: Chart.js tidak muncul

**Penyebab:**
- Koneksi internet tidak aktif (Chart.js dari CDN)
- JavaScript error

**Solusi:**
- Pastikan koneksi internet aktif
- Buka Console browser (F12) dan cek error

### Problem 4: Session timeout cepat

**Solusi:**
Edit `php.ini`:
```ini
session.gc_maxlifetime = 3600
session.cookie_lifetime = 3600
```

Restart web server.

### Problem 5: URL tidak berfungsi (404 Error)

**Penyebab:**
- mod_rewrite tidak aktif
- .htaccess tidak dibaca

**Solusi:**
```bash
# Enable mod_rewrite (Linux)
sudo a2enmod rewrite
sudo systemctl restart apache2

# XAMPP: sudah aktif secara default
# Pastikan AllowOverride All di httpd.conf
```

---

## DEPLOYMENT KE PRODUCTION

### 1. Persiapan Server Production

**Minimum Server Requirements:**
- VPS/Cloud Server (DigitalOcean, AWS, GCP, dll)
- Ubuntu 20.04+ / CentOS 8+
- 2GB RAM minimum
- 20GB Storage minimum

### 2. Install LEMP Stack

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Nginx
sudo apt install nginx -y

# Install MySQL
sudo apt install mysql-server -y
sudo mysql_secure_installation

# Install PHP
sudo apt install php8.1-fpm php8.1-mysql php8.1-mbstring php8.1-xml php8.1-curl -y
```

### 3. Security Checklist

- [ ] Ubah password admin default
- [ ] Enable HTTPS (SSL Certificate)
- [ ] Setup firewall (UFW/iptables)
- [ ] Disable error display di production
- [ ] Setup automatic backup
- [ ] Monitor log files
- [ ] Rate limiting untuk API
- [ ] Enable CSRF protection

### 4. Optimasi Performance

```bash
# Enable PHP OPcache
# Edit /etc/php/8.1/fpm/php.ini

opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

### 5. Setup Backup Otomatis

```bash
# Create backup script
nano /root/backup-microdata.sh
```

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backup/microdata"
DB_NAME="microdata_kendari"
DB_USER="root"
DB_PASS="your_password"

# Backup database
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/db_$DATE.sql

# Backup files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/html/microdata-kendari/uploads

# Delete old backups (older than 30 days)
find $BACKUP_DIR -type f -mtime +30 -delete
```

```bash
# Make executable
chmod +x /root/backup-microdata.sh

# Add to crontab (daily at 2 AM)
crontab -e
0 2 * * * /root/backup-microdata.sh
```

---

## MAINTENANCE

### Update Database Schema

Jika ada perubahan struktur database:

```bash
# Backup dulu
mysqldump -u root -p microdata_kendari > backup_before_update.sql

# Import schema baru
mysql -u root -p microdata_kendari < database_schema_new.sql
```

### Monitor Log Files

```bash
# Apache error log
tail -f /var/log/apache2/error.log

# PHP error log
tail -f /var/log/php/error.log

# MySQL log
tail -f /var/log/mysql/error.log
```

### Clear Cache (if needed)

```bash
# Clear PHP OPcache
sudo systemctl restart php8.1-fpm

# Clear browser cache
# Hard refresh: Ctrl+Shift+R (Windows/Linux) or Cmd+Shift+R (Mac)
```

---

## SUPPORT & BANTUAN

Jika mengalami kesulitan:

1. **Baca dokumentasi:** README.md
2. **Check troubleshooting:** Bagian ini
3. **Kontak support:** kontak@microdatakendari.id
4. **Issue tracker:** GitHub Issues (jika open source)

---

**Selamat! Website Microdata Kendari Anda sekarang sudah berjalan! 🎉**

Jangan lupa untuk:
- Ubah password admin
- Update pengaturan website
- Tambahkan data contoh
- Test semua fitur
- Setup backup rutin
