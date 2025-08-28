<?php include 'auth.php'; ?>
<?php include '_header.php'; ?>
<h2>Crash Test</h2>
<?php
// Ambil faktor dari query string secara aman.

$factor = filter_input(
    INPUT_GET,
    'factor',
    FILTER_VALIDATE_FLOAT,
    ['options' => ['default' => 1]]
);
if ($factor === false) {
    $factor = 1;
}

// Cegah division by zero dan XSS pada output.
if ((float)$factor == 0.0) {
    echo "<p style='color:#b00'>Invalid factor (division by zero).</p>";
} else {
    $result = 100 / (float)$factor;
    $safeFactor = htmlspecialchars((string)$factor, ENT_QUOTES, 'UTF-8');
    echo "100 / {$safeFactor} = {$result}";
}
?>
<?php include '_footer.php'; ?>
