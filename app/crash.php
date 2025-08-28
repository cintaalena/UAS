<?php include 'auth.php'; ?>
<?php include '_header.php'; ?>
<h2>Crash Test</h2>
<?php
$factor = $_GET['factor'] ?? 1;
$result = 100 / $factor; 
echo "100 / $factor = $result";
?>
<?php include '_footer.php'; ?>