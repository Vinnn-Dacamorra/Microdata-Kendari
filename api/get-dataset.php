<?php
/**
 * =====================================================
 * API - GET DATASET
 * =====================================================
 * Endpoint: /api/get-dataset.php
 * Method: GET
 * Parameters:
 *   - id (optional): specific dataset ID
 *   - kategori_id (optional)
 *   - tahun (optional)
 *   - search (optional)
 *   - limit (optional, default: 20)
 * =====================================================
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../config/database.php';

try {
    $db = getDB();
    
    // Get parameters
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;
    $kategori_id = isset($_GET['kategori_id']) ? intval($_GET['kategori_id']) : null;
    $tahun = isset($_GET['tahun']) ? intval($_GET['tahun']) : null;
    $search = isset($_GET['search']) ? trim($_GET['search']) : null;
    $limit = isset($_GET['limit']) ? min(intval($_GET['limit']), 100) : 20;
    
    // Jika ada ID, ambil dataset spesifik
    if ($id) {
        $stmt = $db->prepare("SELECT d.*, k.nama_kategori, k.slug as kategori_slug 
                             FROM dataset d 
                             JOIN kategori k ON d.kategori_id = k.id 
                             WHERE d.id = :id AND d.status = 'published'");
        $stmt->execute([':id' => $id]);
        $dataset = $stmt->fetch();
        
        if (!$dataset) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Dataset tidak ditemukan'
            ], JSON_PRETTY_PRINT);
            exit;
        }
        
        // Update view/download count bisa ditambahkan di sini
        
        echo json_encode([
            'success' => true,
            'data' => $dataset
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Build query untuk list dataset
    $sql = "SELECT d.*, k.nama_kategori, k.slug as kategori_slug 
            FROM dataset d 
            JOIN kategori k ON d.kategori_id = k.id 
            WHERE d.status = 'published'";
    
    $params = [];
    
    if ($kategori_id) {
        $sql .= " AND d.kategori_id = :kategori_id";
        $params[':kategori_id'] = $kategori_id;
    }
    
    if ($tahun) {
        $sql .= " AND d.tahun = :tahun";
        $params[':tahun'] = $tahun;
    }
    
    if ($search) {
        $sql .= " AND (d.nama_dataset LIKE :search OR d.deskripsi LIKE :search OR d.tags LIKE :search)";
        $params[':search'] = "%$search%";
    }
    
    $sql .= " ORDER BY d.created_at DESC LIMIT :limit";
    
    $stmt = $db->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    $datasets = $stmt->fetchAll();
    
    // Response
    echo json_encode([
        'success' => true,
        'total' => count($datasets),
        'data' => $datasets
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
