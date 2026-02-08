<?php
/**
 * =====================================================
 * LOGOUT
 * =====================================================
 */

session_start();
require_once __DIR__ . '/../config/database.php';

// Destroy session
session_unset();
session_destroy();

// Redirect ke halaman utama
redirect('/index.php');
?>
