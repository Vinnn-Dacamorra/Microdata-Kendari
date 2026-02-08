# 🌐 Microdata Kendari - Portal Data & Publikasi Ilmiah

<div align="center">

![PHP](https://img.shields.io/badge/PHP-7.4%2B-blue)
![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange)
![License](https://img.shields.io/badge/License-MIT-green)
![Status](https://img.shields.io/badge/Status-Production%20Ready-success)

Portal data dan publikasi ilmiah yang menyediakan akses terbuka terhadap data mikro, statistik, dan publikasi ilmiah Kota Kendari.

[Demo](#demo) • [Features](#features) • [Installation](#installation) • [Documentation](#documentation)

</div>

---

## 📸 Screenshot

*(Tambahkan screenshot aplikasi Anda di sini)*

---

## ✨ Features

### 🔍 Data Management
- **Dataset Management** - Upload, manage, dan download dataset dalam berbagai format (CSV, XLSX, JSON)
- **Publikasi** - Kelola publikasi ilmiah, laporan, dan artikel
- **Data Statistik** - Tampilkan data statistik dengan visualisasi
- **Kategori Data** - Organisasi data berdasarkan kategori

### 📊 Public Portal
- **Search & Filter** - Pencarian data dengan filter kategori dan tahun
- **Data Visualization** - Grafik dan chart untuk data statistik
- **Download Center** - Download dataset dan publikasi
- **Request Data** - Form permintaan data khusus

### 🔐 Admin Panel
- **User Management** - Kelola user dengan role-based access
- **Content Management** - CRUD untuk dataset, publikasi, dan berita
- **Dashboard Analytics** - Statistik download dan view
- **Activity Logs** - Track user activities

### 🎨 User Experience
- **Responsive Design** - Mobile-friendly interface
- **Modern UI** - Clean dan intuitive design
- **Fast Loading** - Optimized performance
- **SEO Friendly** - Metadata dan sitemap

---

## 🚀 Quick Start

### Prerequisites

- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau MariaDB 10.3+
- Apache/Nginx web server
- mod_rewrite enabled (untuk Apache)

### Installation

#### 1. Clone Repository

```bash
git clone https://github.com/your-username/microdata-kendari.git
cd microdata-kendari
```

#### 2. Database Setup

```bash
# Buat database
mysql -u root -p -e "CREATE DATABASE microdata_kendari CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import schema
mysql -u root -p microdata_kendari < database_schema.sql
```

#### 3. Configuration

Edit `config/database.php`:

```php
// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'microdata_kendari');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');

// Base path - sesuaikan dengan folder instalasi
$base_path = '/microdata-kendari'; // Atau '' jika di root
```

#### 4. Setup Admin Password

Buka di browser:
```
http://localhost/microdata-kendari/setup_admin.php
```

Script akan otomatis membuat user admin dengan password yang benar.

#### 5. Login

```
URL: http://localhost/microdata-kendari/admin/login.php
Username: admin
Password: admin123
```

⚠️ **PENTING:** Setelah login berhasil, hapus file `setup_admin.php` untuk keamanan!

#### 6. Change Default Password

Setelah login pertama kali, segera ganti password default ke password yang lebih kuat.

---

## 📖 Documentation

### File Structure

```
microdata-kendari/
├── admin/                  # Admin panel
│   ├── dashboard.php       # Dashboard admin
│   ├── login.php           # Login page
│   └── logout.php          # Logout handler
├── api/                    # REST API endpoints
│   ├── get-data.php        # Get data
│   ├── get-dataset.php     # Get dataset
│   ├── download-dataset.php
│   ├── download-publikasi.php
│   └── request-data.php
├── assets/                 # Static assets
│   ├── css/                # Stylesheets
│   └── js/                 # JavaScript files
├── config/                 # Configuration files
│   └── database.php        # Database config & helpers
├── includes/               # Reusable components
│   ├── header.php
│   ├── navbar.php
│   └── footer.php
├── uploads/                # Upload directory (create this)
│   ├── datasets/
│   └── publikasi/
├── index.php               # Homepage
├── data.php                # Data page
├── dataset.php             # Dataset listing
├── dataset-detail.php      # Dataset detail
├── publikasi.php           # Publikasi listing
├── publikasi-detail.php    # Publikasi detail
├── berita.php              # News listing
├── about.php               # About page
├── kontak.php              # Contact page
├── .htaccess               # Apache config
└── database_schema.sql     # Database schema
```

### Database Schema

Project menggunakan 11 tabel utama:
- `users` - User accounts
- `kategori` - Data categories
- `data_statistik` - Statistical data
- `dataset` - Dataset/microdata
- `publikasi` - Publications
- `berita` - News & updates
- `permintaan_data` - Data requests
- `log_akses` - Access logs
- `log_download` - Download logs
- `pengaturan` - Site settings

### API Endpoints

```php
// Get data statistik
GET /api/get-data.php?kategori_id=1&tahun=2024

// Get dataset
GET /api/get-dataset.php?id=1

// Download dataset
GET /api/download-dataset.php?id=1

// Request data
POST /api/request-data.php
```

---

## 🔧 Configuration

### Base URL Configuration

Edit `config/database.php` baris 14:

```php
// Jika install di subfolder
$base_path = '/microdata-kendari';

// Jika install di root domain
$base_path = '';

// Jika install di subdomain
$base_path = ''; // subdomain biasanya tidak perlu base_path
```

### Upload Directory

Buat folder uploads dengan permission yang sesuai:

```bash
mkdir -p uploads/datasets uploads/publikasi
chmod -R 755 uploads/
```

### Security

1. **Change Default Password** - Ganti password admin setelah install
2. **Delete Setup Files** - Hapus `setup_admin.php` dan `test_redirect.php`
3. **Enable HTTPS** - Uncomment baris 11-12 di `.htaccess`
4. **Secure Config** - Jangan commit file config dengan credentials asli
5. **Regular Updates** - Update dependencies dan PHP version

---

## 🛠️ Development

### Local Development

```bash
# Start PHP built-in server
php -S localhost:8000

# Or use XAMPP/WAMP/MAMP
# Access: http://localhost/microdata-kendari
```

### Testing

```bash
# Test redirect configuration
http://localhost/microdata-kendari/test_redirect.php

# Test login flow
http://localhost/microdata-kendari/admin/login.php
```

### Debugging

Enable PHP error reporting di development:

```php
// Tambahkan di awal file
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📋 Changelog

### Version 1.0.0 (2026-02-08)
- ✅ Initial release
- ✅ Complete admin panel
- ✅ Public portal with search & filter
- ✅ Dataset & publikasi management
- ✅ User authentication & authorization
- ✅ API endpoints
- ✅ Responsive design

### Bug Fixes
- ✅ Fixed HTTP 500 error on login page
- ✅ Fixed password hash issue
- ✅ Fixed redirect to dashboard after login
- ✅ Fixed logout redirect to homepage

---

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👥 Authors

- **Microdata Kendari Team** - *Initial work*

---

## 🙏 Acknowledgments

- Bootstrap for UI components
- Font Awesome for icons
- Chart.js for data visualization
- PHP community

---

## 📧 Contact

For questions and support:

- **Email:** kontak@microdatakendari.id
- **Website:** [https://microdatakendari.id](https://microdatakendari.id)
- **Issues:** [GitHub Issues](https://github.com/your-username/microdata-kendari/issues)

---

## 🌟 Show your support

Give a ⭐️ if this project helped you!

---

<div align="center">

Made with ❤️ by Microdata Kendari Team

</div>
