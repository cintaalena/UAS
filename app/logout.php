<?php
// Pastikan helper CSRF & session sudah tersedia
include 'auth.php';

// Wajib POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

// Verifikasi CSRF
csrf_verify();

// Hancurkan sesi (dan cookie sesi jika ada)
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();

// (Opsional) rotasi token setelah logout (sesi baru akan dibuat saat ke login.php)
csrf_rotate();

// Redirect ke login
header('Location: login.php');
exit;
