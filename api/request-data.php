<?php
/**
 * =====================================================
 * API - REQUEST DATA
 * =====================================================
 * Endpoint: /api/request-data.php
 * Method: POST
 * Parameters:
 *   - nama (required)
 *   - email (required)
 *   - telepon (optional)
 *   - instansi (optional)
 *   - jenis_instansi (optional)
 *   - kebutuhan (required)
 *   - data_yang_diminta (optional)
 *   - tujuan_penggunaan (optional)
 *   - periode_data (optional)
 *   - format_file (optional)
 * =====================================================
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../config/database.php';

// Only allow POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed. Use POST.'
    ], JSON_PRETTY_PRINT);
    exit;
}

try {
    $db = getDB();
    
    // Validasi input required
    $required_fields = ['nama', 'email', 'kebutuhan'];
    $errors = [];
    
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
            $errors[] = "Field '$field' wajib diisi";
        }
    }
    
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $errors
        ], JSON_PRETTY_PRINT);
        exit;
    }
    
    // Validasi email
    $email = trim($_POST['email']);
    if (!isValidEmail($email)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Format email tidak valid'
        ], JSON_PRETTY_PRINT);
        exit;
    }
    
    // Sanitize inputs
    $nama = sanitize($_POST['nama']);
    $telepon = isset($_POST['telepon']) ? sanitize($_POST['telepon']) : null;
    $instansi = isset($_POST['instansi']) ? sanitize($_POST['instansi']) : null;
    $jenis_instansi = isset($_POST['jenis_instansi']) ? sanitize($_POST['jenis_instansi']) : null;
    $kebutuhan = sanitize($_POST['kebutuhan']);
    $data_yang_diminta = isset($_POST['data_yang_diminta']) ? sanitize($_POST['data_yang_diminta']) : null;
    $tujuan_penggunaan = isset($_POST['tujuan_penggunaan']) ? sanitize($_POST['tujuan_penggunaan']) : null;
    $periode_data = isset($_POST['periode_data']) ? sanitize($_POST['periode_data']) : null;
    $format_file = isset($_POST['format_file']) ? sanitize($_POST['format_file']) : 'csv';
    
    // Validasi jenis_instansi jika ada
    $valid_jenis = ['pemerintah', 'swasta', 'akademik', 'lsm', 'perorangan', 'lainnya'];
    if ($jenis_instansi && !in_array($jenis_instansi, $valid_jenis)) {
        $jenis_instansi = 'lainnya';
    }
    
    // Insert ke database
    $sql = "INSERT INTO permintaan_data 
            (nama, email, telepon, instansi, jenis_instansi, kebutuhan, 
             data_yang_diminta, tujuan_penggunaan, periode_data, format_file, status) 
            VALUES 
            (:nama, :email, :telepon, :instansi, :jenis_instansi, :kebutuhan, 
             :data_yang_diminta, :tujuan_penggunaan, :periode_data, :format_file, 'pending')";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':nama' => $nama,
        ':email' => $email,
        ':telepon' => $telepon,
        ':instansi' => $instansi,
        ':jenis_instansi' => $jenis_instansi,
        ':kebutuhan' => $kebutuhan,
        ':data_yang_diminta' => $data_yang_diminta,
        ':tujuan_penggunaan' => $tujuan_penggunaan,
        ':periode_data' => $periode_data,
        ':format_file' => $format_file
    ]);
    
    $request_id = $db->lastInsertId();
    
    // TODO: Kirim email notifikasi ke admin (optional)
    // sendEmailNotification($email, $nama, $kebutuhan);
    
    // Success response
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Permintaan data berhasil dikirim',
        'request_id' => $request_id,
        'data' => [
            'nama' => $nama,
            'email' => $email,
            'status' => 'pending'
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error',
        'error' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error',
        'error' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
?>
