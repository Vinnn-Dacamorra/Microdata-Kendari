<?php
/**
 * =====================================================
 * API - GET DATA STATISTIK
 * =====================================================
 * Endpoint: /api/get-data.php
 * Method: GET
 * Parameters: 
 *   - kategori_id (optional)
 *   - tahun (optional)
 *   - limit (optional, default: 50)
 * =====================================================
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../config/database.php';

try {
    $db = getDB();
    
    // Get parameters
    $kategori_id = isset($_GET['kategori_id']) ? intval($_GET['kategori_id']) : null;
    $tahun = isset($_GET['tahun']) ? intval($_GET['tahun']) : null;
    $limit = isset($_GET['limit']) ? min(intval($_GET['limit']), 100) : 50;
    
    // Build query
    $sql = "SELECT ds.*, k.nama_kategori, k.slug as kategori_slug 
            FROM data_statistik ds 
            JOIN kategori k ON ds.kategori_id = k.id 
            WHERE 1=1";
    
    $params = [];
    
    if ($kategori_id) {
        $sql .= " AND ds.kategori_id = :kategori_id";
        $params[':kategori_id'] = $kategori_id;
    }
    
    if ($tahun) {
        $sql .= " AND ds.tahun = :tahun";
        $params[':tahun'] = $tahun;
    }
    
    $sql .= " ORDER BY ds.tahun DESC, ds.created_at DESC LIMIT :limit";
    
    $stmt = $db->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    $data = $stmt->fetchAll();
    
    // Response
    echo json_encode([
        'success' => true,
        'total' => count($data),
        'data' => $data
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error',
        'error' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
?>
