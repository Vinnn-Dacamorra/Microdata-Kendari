<?php
/**
 * =====================================================
 * ADMIN LOGIN
 * =====================================================
 */

session_start();

require_once __DIR__ . '/../config/database.php';

// Jika sudah login, redirect ke dashboard
if (isLoggedIn()) {
    redirect('/admin/dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password wajib diisi';
    } else {
        try {
            $db = getDB();
            $stmt = $db->prepare("SELECT * FROM users WHERE username = :username AND status = 'aktif'");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch();
            
            // Verify password
            if ($user && password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['email'] = $user['email'];
                
                setFlashMessage('success', 'Login berhasil! Selamat datang, ' . $user['nama_lengkap']);
                redirect('/admin/dashboard.php');
            } else {
                $error = 'Username atau password salah';
            }
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            $error = 'Terjadi kesalahan sistem. Silakan coba lagi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Microdata Kendari</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        }
        
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
            width: 100%;
            max-width: 400px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h1 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .login-header p {
            color: var(--gray-600);
            margin: 0;
        }
        
        .error-message {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 0.75rem;
            border-radius: var(--radius-md);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .form-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
        }
        
        .form-group input {
            padding-left: 3rem;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--gray-200);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1><i class="fas fa-user-shield"></i> Admin Panel</h1>
            <p>Microdata Kendari</p>
        </div>
        
        <?php if ($error): ?>
        <div class="error-message">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <i class="fas fa-user"></i>
                <input type="text" 
                       name="username" 
                       class="form-control" 
                       placeholder="Username"
                       required
                       autofocus>
            </div>
            
            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input type="password" 
                       name="password" 
                       class="form-control" 
                       placeholder="Password"
                       required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>
        
        <div class="login-footer">
            <a href="/index.php" style="color: var(--gray-600); text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
        
        <div style="margin-top: 1rem; padding: 1rem; background-color: var(--gray-100); border-radius: var(--radius-md); font-size: 0.875rem;">
            <strong>Demo Login:</strong><br>
            Username: <code>admin</code><br>
            Password: <code>admin123</code>
        </div>
    </div>
</body>
</html>
