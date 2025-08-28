<?php include 'auth.php'; ?>
<?php include '_header.php'; ?>
<h2>Ping Utility</h2>

<form method="get">
  <input name="target" placeholder="IPv4 atau hostname">
  <button type="submit">Ping</button>
</form>

<?php
if (isset($_GET['target'])) {
    $target = $_GET['target'];

    // Throttle sederhana (1 request/detik per sesi)
    $now = microtime(true);
    if (isset($_SESSION['last_ping']) && ($now - $_SESSION['last_ping']) < 1.0) {
        echo "<p>Please wait a moment before next ping.</p>";
        include '_footer.php'; exit;
    }
    $_SESSION['last_ping'] = $now;

    // Validasi target: izinkan IPv4 publik atau hostname alfanumerik/.- saja
    $isValid = false;
    $ip = null;

    if (filter_var($target, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
        $isValid = true;
        $ip = $target;
    } elseif (preg_match('/^[a-z0-9.-]{1,253}$/i', $target)) {
        // Resolve dan cek hasilnya bukan private/reserved
        $resolved = gethostbyname($target);
        if ($resolved !== $target) { // resolved ke IP
            if (filter_var($resolved, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                $isValid = true;
                $ip = $target; // tetap gunakan target; ping akan resolve sendiri
            }
        }
    }

    if (!$isValid) {
        echo "<p style='color:#b00'>Invalid or disallowed target.</p>";
        include '_footer.php'; exit;
    }

    // Hindari XSS pada output
    $safeLabel = htmlspecialchars($target, ENT_QUOTES, 'UTF-8');
    echo "<h3>Ping Result for: {$safeLabel}</h3>";

    // Hindari command injection
    $cmd = 'ping -c 2 ' . escapeshellarg($ip ?? $target);
    $output = shell_exec($cmd . ' 2>&1');
    echo '<pre>' . htmlspecialchars($output ?? '', ENT_QUOTES, 'UTF-8') . '</pre>';
}
?>
<?php include '_footer.php'; ?>
