# API DOCUMENTATION - MICRODATA KENDARI
## RESTful API Endpoints

---

## BASE URL

```
Development: http://localhost/microdata-kendari/api
Production: https://microdatakendari.id/api
```

---

## AUTHENTICATION

Saat ini API bersifat **public** dan tidak memerlukan authentication. 
Untuk production, disarankan menambahkan API Key authentication.

---

## ENDPOINTS

### 1. GET DATA STATISTIK

Mengambil data statistik berdasarkan filter.

#### Endpoint
```
GET /api/get-data.php
```

#### Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| kategori_id | integer | No | Filter berdasarkan ID kategori |
| tahun | integer | No | Filter berdasarkan tahun |
| limit | integer | No | Jumlah data (default: 50, max: 100) |

#### Example Request

**JavaScript (Fetch API):**
```javascript
fetch('http://localhost/microdata-kendari/api/get-data.php?kategori_id=1&tahun=2024&limit=10')
  .then(response => response.json())
  .then(data => {
    console.log(data);
  })
  .catch(error => console.error('Error:', error));
```

**jQuery:**
```javascript
$.ajax({
  url: 'http://localhost/microdata-kendari/api/get-data.php',
  method: 'GET',
  data: {
    kategori_id: 1,
    tahun: 2024,
    limit: 10
  },
  success: function(response) {
    console.log(response);
  },
  error: function(error) {
    console.error('Error:', error);
  }
});
```

**PHP (cURL):**
```php
<?php
$url = 'http://localhost/microdata-kendari/api/get-data.php?kategori_id=1&tahun=2024&limit=10';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
print_r($data);
?>
```

**Python:**
```python
import requests

url = 'http://localhost/microdata-kendari/api/get-data.php'
params = {
    'kategori_id': 1,
    'tahun': 2024,
    'limit': 10
}

response = requests.get(url, params=params)
data = response.json()
print(data)
```

#### Success Response

**Code:** `200 OK`

```json
{
  "success": true,
  "total": 10,
  "data": [
    {
      "id": 1,
      "kategori_id": 1,
      "judul": "Jumlah Penduduk Kota Kendari",
      "tahun": 2024,
      "periode": "Tahunan",
      "sumber": "BPS Kota Kendari",
      "deskripsi": "Total jumlah penduduk Kota Kendari",
      "nilai": 385678.00,
      "satuan": "jiwa",
      "nama_kategori": "Kependudukan",
      "kategori_slug": "kependudukan",
      "created_at": "2024-01-15 10:30:00"
    },
    ...
  ]
}
```

#### Error Response

**Code:** `500 Internal Server Error`

```json
{
  "success": false,
  "message": "Database error",
  "error": "Error message details"
}
```

---

### 2. GET DATASET

Mengambil daftar dataset atau detail dataset tertentu.

#### Endpoint
```
GET /api/get-dataset.php
```

#### Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | No | ID dataset spesifik (untuk detail) |
| kategori_id | integer | No | Filter berdasarkan kategori |
| tahun | integer | No | Filter berdasarkan tahun |
| search | string | No | Kata kunci pencarian |
| limit | integer | No | Jumlah data (default: 20, max: 100) |

#### Example Request 1: Get List Dataset

**cURL:**
```bash
curl -X GET "http://localhost/microdata-kendari/api/get-dataset.php?kategori_id=1&limit=5"
```

**JavaScript:**
```javascript
// Get dataset list
const getDatasets = async () => {
  try {
    const response = await fetch('/api/get-dataset.php?kategori_id=1&limit=5');
    const result = await response.json();
    
    if (result.success) {
      console.log(`Found ${result.total} datasets`);
      result.data.forEach(dataset => {
        console.log(`- ${dataset.nama_dataset} (${dataset.tahun})`);
      });
    }
  } catch (error) {
    console.error('Error:', error);
  }
};

getDatasets();
```

#### Example Request 2: Get Dataset Detail

**JavaScript:**
```javascript
// Get specific dataset by ID
fetch('/api/get-dataset.php?id=1')
  .then(response => response.json())
  .then(result => {
    if (result.success) {
      const dataset = result.data;
      console.log('Dataset:', dataset.nama_dataset);
      console.log('Download:', dataset.file_path);
    }
  });
```

#### Success Response (List)

```json
{
  "success": true,
  "total": 5,
  "data": [
    {
      "id": 1,
      "nama_dataset": "Data Penduduk Per Kecamatan 2024",
      "kategori_id": 1,
      "sumber": "BPS Kota Kendari",
      "metodologi": "Proyeksi Penduduk",
      "tahun": 2024,
      "deskripsi": "Dataset lengkap jumlah penduduk per kecamatan",
      "file_path": "/uploads/datasets/penduduk_kecamatan_2024.csv",
      "file_name": "penduduk_kecamatan_2024.csv",
      "file_size": 51200,
      "file_type": "csv",
      "download_count": 145,
      "nama_kategori": "Kependudukan",
      "kategori_slug": "kependudukan"
    },
    ...
  ]
}
```

#### Success Response (Detail)

```json
{
  "success": true,
  "data": {
    "id": 1,
    "nama_dataset": "Data Penduduk Per Kecamatan 2024",
    "kategori_id": 1,
    "sumber": "BPS Kota Kendari",
    "metodologi": "Proyeksi Penduduk",
    "tahun": 2024,
    "periode": null,
    "deskripsi": "Dataset lengkap jumlah penduduk per kecamatan di Kota Kendari tahun 2024",
    "file_path": "/uploads/datasets/penduduk_kecamatan_2024.csv",
    "file_name": "penduduk_kecamatan_2024.csv",
    "file_size": 51200,
    "file_type": "csv",
    "jumlah_baris": 11,
    "jumlah_kolom": 8,
    "frekuensi_update": "Tahunan",
    "lisensi": "CC BY 4.0",
    "download_count": 145,
    "metadata": null,
    "tags": "penduduk, kecamatan, demografi",
    "status": "published",
    "created_at": "2024-01-15 10:00:00",
    "nama_kategori": "Kependudukan",
    "kategori_slug": "kependudukan"
  }
}
```

#### Error Response (Not Found)

**Code:** `404 Not Found`

```json
{
  "success": false,
  "message": "Dataset tidak ditemukan"
}
```

---

### 3. REQUEST DATA

Mengirim permintaan data dari user.

#### Endpoint
```
POST /api/request-data.php
```

#### Parameters (Form Data)

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| nama | string | Yes | Nama lengkap pemohon |
| email | string | Yes | Email pemohon (valid) |
| kebutuhan | string | Yes | Deskripsi kebutuhan data |
| telepon | string | No | Nomor telepon |
| instansi | string | No | Nama instansi |
| jenis_instansi | string | No | pemerintah/swasta/akademik/lsm/perorangan/lainnya |
| data_yang_diminta | string | No | Detail data yang diminta |
| tujuan_penggunaan | string | No | Tujuan penggunaan data |
| periode_data | string | No | Periode/tahun data yang diminta |
| format_file | string | No | Format file (csv/xlsx/json/pdf) |

#### Example Request

**HTML Form:**
```html
<form id="requestDataForm">
  <input type="text" name="nama" placeholder="Nama Lengkap" required>
  <input type="email" name="email" placeholder="Email" required>
  <input type="tel" name="telepon" placeholder="Telepon">
  <input type="text" name="instansi" placeholder="Instansi">
  <select name="jenis_instansi">
    <option value="">Pilih Jenis Instansi</option>
    <option value="pemerintah">Pemerintah</option>
    <option value="swasta">Swasta</option>
    <option value="akademik">Akademik</option>
    <option value="lsm">LSM</option>
    <option value="perorangan">Perorangan</option>
  </select>
  <textarea name="kebutuhan" placeholder="Deskripsi Kebutuhan Data" required></textarea>
  <button type="submit">Kirim Permintaan</button>
</form>

<script>
document.getElementById('requestDataForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  
  const formData = new FormData(this);
  
  try {
    const response = await fetch('/api/request-data.php', {
      method: 'POST',
      body: formData
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
```

**JavaScript (Fetch):**
```javascript
const requestData = async (formData) => {
  try {
    const response = await fetch('/api/request-data.php', {
      method: 'POST',
      body: new URLSearchParams({
        nama: 'John Doe',
        email: 'john@example.com',
        telepon: '081234567890',
        instansi: 'Universitas XYZ',
        jenis_instansi: 'akademik',
        kebutuhan: 'Membutuhkan data kependudukan untuk penelitian',
        data_yang_diminta: 'Data penduduk per kecamatan tahun 2023-2024',
        tujuan_penggunaan: 'Penelitian skripsi tentang demografi',
        periode_data: '2023-2024',
        format_file: 'xlsx'
      })
    });
    
    const result = await response.json();
    return result;
  } catch (error) {
    console.error('Error:', error);
  }
};

// Usage
requestData().then(result => {
  console.log(result);
});
```

**jQuery:**
```javascript
$('#requestDataForm').submit(function(e) {
  e.preventDefault();
  
  $.ajax({
    url: '/api/request-data.php',
    method: 'POST',
    data: $(this).serialize(),
    success: function(response) {
      if (response.success) {
        alert('Permintaan berhasil! ID: ' + response.request_id);
      } else {
        alert('Error: ' + response.message);
      }
    },
    error: function(xhr) {
      alert('Network error');
    }
  });
});
```

**PHP (cURL):**
```php
<?php
$url = 'http://localhost/microdata-kendari/api/request-data.php';

$data = array(
    'nama' => 'John Doe',
    'email' => 'john@example.com',
    'telepon' => '081234567890',
    'instansi' => 'Universitas XYZ',
    'jenis_instansi' => 'akademik',
    'kebutuhan' => 'Membutuhkan data kependudukan untuk penelitian',
    'data_yang_diminta' => 'Data penduduk per kecamatan tahun 2023-2024',
    'tujuan_penggunaan' => 'Penelitian skripsi',
    'periode_data' => '2023-2024',
    'format_file' => 'xlsx'
);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
print_r($result);
?>
```

#### Success Response

**Code:** `201 Created`

```json
{
  "success": true,
  "message": "Permintaan data berhasil dikirim",
  "request_id": 123,
  "data": {
    "nama": "John Doe",
    "email": "john@example.com",
    "status": "pending"
  }
}
```

#### Error Response (Validation Error)

**Code:** `400 Bad Request`

```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": [
    "Field 'nama' wajib diisi",
    "Field 'email' wajib diisi"
  ]
}
```

#### Error Response (Invalid Email)

**Code:** `400 Bad Request`

```json
{
  "success": false,
  "message": "Format email tidak valid"
}
```

---

## ERROR CODES

| Code | Description |
|------|-------------|
| 200 | OK - Request berhasil |
| 201 | Created - Resource berhasil dibuat |
| 400 | Bad Request - Request tidak valid |
| 404 | Not Found - Resource tidak ditemukan |
| 405 | Method Not Allowed - HTTP method tidak diizinkan |
| 500 | Internal Server Error - Error pada server |

---

## RATE LIMITING

**Current:** Tidak ada rate limiting

**Recommendation untuk Production:**
- 100 requests per hour per IP address
- 1000 requests per day per IP address

Implementasi rate limiting dapat ditambahkan menggunakan:
- Redis
- Database-based tracking
- Nginx rate limiting

---

## CORS (Cross-Origin Resource Sharing)

API saat ini mengizinkan akses dari semua origin:
```php
header('Access-Control-Allow-Origin: *');
```

Untuk production, disarankan membatasi origin:
```php
header('Access-Control-Allow-Origin: https://yourdomain.com');
```

---

## EXAMPLES & USE CASES

### Use Case 1: Display Latest Datasets on External Website

```javascript
// Tampilkan 5 dataset terbaru di website eksternal
async function displayLatestDatasets() {
  const response = await fetch('https://microdatakendari.id/api/get-dataset.php?limit=5');
  const result = await response.json();
  
  if (result.success) {
    const container = document.getElementById('datasets');
    result.data.forEach(dataset => {
      container.innerHTML += `
        <div class="dataset-item">
          <h3>${dataset.nama_dataset}</h3>
          <p>${dataset.deskripsi}</p>
          <a href="https://microdatakendari.id/dataset-detail.php?id=${dataset.id}">
            Lihat Detail
          </a>
        </div>
      `;
    });
  }
}

displayLatestDatasets();
```

### Use Case 2: Create Interactive Chart from API Data

```javascript
// Buat chart dari data statistik
async function createChart() {
  const response = await fetch('/api/get-data.php?kategori_id=1&limit=10');
  const result = await response.json();
  
  if (result.success) {
    const labels = result.data.map(item => item.judul);
    const values = result.data.map(item => parseFloat(item.nilai));
    
    const ctx = document.getElementById('myChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Nilai',
          data: values,
          backgroundColor: 'rgba(54, 162, 235, 0.5)'
        }]
      }
    });
  }
}

createChart();
```

### Use Case 3: Auto-submit Data Request Form

```javascript
// Submit form permintaan data secara programmatic
async function autoRequestData() {
  const formData = new URLSearchParams({
    nama: 'Bot Scraper',
    email: 'bot@example.com',
    kebutuhan: 'Automated data collection',
    format_file: 'json'
  });
  
  const response = await fetch('/api/request-data.php', {
    method: 'POST',
    body: formData
  });
  
  const result = await response.json();
  console.log('Request ID:', result.request_id);
}
```

---

## BEST PRACTICES

1. **Always handle errors:**
```javascript
try {
  const response = await fetch('/api/get-data.php');
  const result = await response.json();
  // Handle success
} catch (error) {
  console.error('API Error:', error);
  // Handle error
}
```

2. **Validate data before sending:**
```javascript
function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

if (!isValidEmail(email)) {
  alert('Email tidak valid');
  return;
}
```

3. **Show loading indicators:**
```javascript
// Show loading
button.disabled = true;
button.textContent = 'Loading...';

// Make request
const result = await makeRequest();

// Hide loading
button.disabled = false;
button.textContent = 'Submit';
```

4. **Cache responses (if appropriate):**
```javascript
const cache = new Map();

async function getCachedData(url) {
  if (cache.has(url)) {
    return cache.get(url);
  }
  
  const response = await fetch(url);
  const data = await response.json();
  cache.set(url, data);
  
  return data;
}
```

---

## SUPPORT

Jika ada pertanyaan atau issue terkait API:

- **Email:** api@microdatakendari.id
- **Documentation:** https://microdatakendari.id/api-docs
- **Issue Tracker:** GitHub Issues

---

**Happy Coding! 🚀**
