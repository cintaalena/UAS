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
    // Hindari XSS pada output
    $safeLabel = htmlspecialchars($target, ENT_QUOTES, 'UTF-8');
    echo "<h3>Ping Result for: {$safeLabel}</h3>";
    // Hindari command injection
    $cmd = 'ping -c 2 ' . escapeshellarg($target);
    $output = shell_exec($cmd . ' 2>&1');
    echo '<pre>' . htmlspecialchars($output ?? '', ENT_QUOTES, 'UTF-8') . '</pre>';
}
?>
<?php include '_footer.php'; ?>
