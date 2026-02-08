<?php
/**
 * =====================================================
 * DATABASE CONFIGURATION TEMPLATE
 * =====================================================
 * File: config/database.example.php
 * 
 * Cara Pakai:
 * 1. Copy file ini jadi database.php
 * 2. Ganti semua nilai dengan konfigurasi Anda
 * 3. JANGAN commit file database.php dengan credentials asli!
 * =====================================================
 */

// ⚠️ IMPORTANT: Ganti dengan kredensial database Anda!
// Konstanta konfigurasi database
define('DB_HOST', 'localhost');           // ← Host database Anda
define('DB_NAME', 'microdata_kendari');   // ← Nama database Anda
define('DB_USER', 'root');                // ← Username database Anda
define('DB_PASS', '');                    // ← Password database Anda
define('DB_CHARSET', 'utf8mb4');

// Konstanta BASE URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];

// ⚠️ IMPORTANT: Sesuaikan dengan folder instalasi Anda!
// Contoh:
// - Jika di root: $base_path = '';
// - Jika di subfolder: $base_path = '/microdata-kendari';
// - Jika di subdomain: $base_path = '';
$base_path = '/microdata-kendari'; // ← Sesuaikan dengan folder Anda

define('BASE_URL', $protocol . '://' . $host . $base_path);
define('ASSETS_URL', BASE_URL . '/assets');
define('UPLOADS_URL', BASE_URL . '/uploads');

/**
 * Class Database
 * Mengelola koneksi database menggunakan PDO dengan Singleton Pattern
 */
class Database {
    private static $instance = null;
    private $connection;
    
    /**
     * Constructor - Private untuk mencegah instantiasi langsung
     */
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
            ];
            
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
            
        } catch (PDOException $e) {
            // Log error (dalam production, jangan tampilkan error detail ke user)
            error_log("Database Connection Error: " . $e->getMessage());
            die("Koneksi database gagal. Silakan hubungi administrator.");
        }
    }
    
    /**
     * Mendapatkan instance Database (Singleton)
     * 
     * @return Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Mendapatkan koneksi PDO
     * 
     * @return PDO
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Mencegah cloning object
     */
    private function __clone() {}
    
    /**
     * Mencegah unserialize
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

/**
 * Function helper untuk mendapatkan koneksi database
 * 
 * @return PDO
 */
function getDB() {
    return Database::getInstance()->getConnection();
}

/**
 * Function helper untuk sanitasi input
 * 
 * @param string $data
 * @return string
 */
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Function helper untuk validasi email
 * 
 * @param string $email
 * @return bool
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Function helper untuk format angka Indonesia
 * 
 * @param float $number
 * @param int $decimals
 * @return string
 */
function formatAngka($number, $decimals = 0) {
    return number_format($number, $decimals, ',', '.');
}

/**
 * Function helper untuk format tanggal Indonesia
 * 
 * @param string $date
 * @param string $format
 * @return string
 */
function formatTanggal($date, $format = 'd F Y') {
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    $timestamp = strtotime($date);
    $tanggal = date('d', $timestamp);
    $bulanAngka = date('n', $timestamp);
    $tahun = date('Y', $timestamp);
    
    if ($format == 'd F Y') {
        return $tanggal . ' ' . $bulan[$bulanAngka] . ' ' . $tahun;
    }
    
    return date($format, $timestamp);
}

/**
 * Function helper untuk membuat slug dari string
 * 
 * @param string $text
 * @return string
 */
function createSlug($text) {
    // Replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    
    // Transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    
    // Remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    
    // Trim
    $text = trim($text, '-');
    
    // Remove duplicate -
    $text = preg_replace('~-+~', '-', $text);
    
    // Lowercase
    $text = strtolower($text);
    
    if (empty($text)) {
        return 'n-a';
    }
    
    return $text;
}

/**
 * Function helper untuk redirect
 * 
 * @param string $url - URL relatif dari BASE_URL atau URL lengkap
 */
function redirect($url) {
    // Jika URL dimulai dengan http:// atau https://, gunakan langsung
    if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) {
        header("Location: " . $url);
        exit();
    }
    
    // Jika URL dimulai dengan /, tambahkan BASE_URL
    if (strpos($url, '/') === 0) {
        header("Location: " . BASE_URL . $url);
        exit();
    }
    
    // Jika URL relatif tanpa /, tambahkan BASE_URL/
    header("Location: " . BASE_URL . '/' . $url);
    exit();
}

/**
 * Function helper untuk set flash message
 * 
 * @param string $type (success, error, warning, info)
 * @param string $message
 */
function setFlashMessage($type, $message) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Function helper untuk get dan hapus flash message
 * 
 * @return array|null
 */
function getFlashMessage() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    
    return null;
}

/**
 * Function helper untuk format file size
 * 
 * @param int $bytes
 * @return string
 */
function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

/**
 * Function helper untuk upload file
 * 
 * @param array $file $_FILES array
 * @param string $destination Folder tujuan
 * @param array $allowed_types Tipe file yang diizinkan
 * @param int $max_size Ukuran maksimal dalam bytes
 * @return array ['success' => bool, 'message' => string, 'file_path' => string]
 */
function uploadFile($file, $destination, $allowed_types = [], $max_size = 5242880) {
    // Validasi error
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Error uploading file'];
    }
    
    // Validasi ukuran
    if ($file['size'] > $max_size) {
        return ['success' => false, 'message' => 'File terlalu besar. Maksimal ' . formatFileSize($max_size)];
    }
    
    // Validasi tipe file
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!empty($allowed_types) && !in_array($file_extension, $allowed_types)) {
        return ['success' => false, 'message' => 'Tipe file tidak diizinkan'];
    }
    
    // Generate nama file unik
    $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
    $file_path = $destination . '/' . $new_filename;
    
    // Buat folder jika belum ada
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }
    
    // Upload file
    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        return [
            'success' => true, 
            'message' => 'File berhasil diupload',
            'file_path' => $file_path,
            'file_name' => $new_filename
        ];
    }
    
    return ['success' => false, 'message' => 'Gagal mengupload file'];
}

/**
 * Function helper untuk cek login
 * 
 * @return bool
 */
function isLoggedIn() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}

/**
 * Function helper untuk cek role admin
 * 
 * @param string $required_role
 * @return bool
 */
function hasRole($required_role) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isLoggedIn()) {
        return false;
    }
    
    $user_role = $_SESSION['role'] ?? '';
    
    // Admin punya akses ke semua
    if ($user_role === 'admin') {
        return true;
    }
    
    return $user_role === $required_role;
}

/**
 * Function helper untuk generate pagination
 * 
 * @param int $total_records
 * @param int $records_per_page
 * @param int $current_page
 * @param string $base_url
 * @return array
 */
function generatePagination($total_records, $records_per_page, $current_page, $base_url) {
    $total_pages = ceil($total_records / $records_per_page);
    
    return [
        'total_records' => $total_records,
        'total_pages' => $total_pages,
        'current_page' => $current_page,
        'records_per_page' => $records_per_page,
        'offset' => ($current_page - 1) * $records_per_page,
        'base_url' => $base_url,
        'has_prev' => $current_page > 1,
        'has_next' => $current_page < $total_pages,
        'prev_page' => $current_page - 1,
        'next_page' => $current_page + 1
    ];
}

?>
