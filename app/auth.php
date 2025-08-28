<?php
// === (A) Session cookie hardening (set sebelum session_start) ===
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
session_set_cookie_params([
  'lifetime' => 0,
  'path'     => '/',
  'domain'   => '',          // biarkan default
  'secure'   => $secure,     // aktifkan saat HTTPS
  'httponly' => true,
  'samesite' => 'Lax'
]);
// (opsional, tambahan hardening untuk environment lama)
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', $secure ? '1' : '0');

session_start();

// === (B) Security headers dasar (pastikan dieksekusi sebelum output HTML) ===
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
// Sesuaikan sumber CDN di CSP di bawah (water.css dari jsdelivr dipakai di _header.php)
header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; style-src 'self' 'unsafe-inline' cdn.jsdelivr.net; script-src 'self';");

// (opsional) Halaman login sebaiknya no-store agar kredensial tidak di-cache
if (basename($_SERVER['PHP_SELF']) === 'login.php') {
  header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
}

// === (C) DB bootstrap ===
require_once __DIR__ . '/init.php';

// === (D) CSRF helpers ===
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_token() {
    return $_SESSION['csrf_token'] ?? '';
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

function csrf_verify() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $t = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $t)) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            exit;
        }
    }
}

// (opsional) Jika ingin merotasi token setelah event tertentu (mis. login/logout)
function csrf_rotate() {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// === (E) Auth guard (izinkan login.php tanpa login) ===
if (!isset($_SESSION['user']) && basename($_SERVER['PHP_SELF']) !== 'login.php') {
    header("Location: login.php");
    exit;
}
