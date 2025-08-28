<?php include 'auth.php'; ?>
<?php include '_header.php'; ?>
<h2>Ping Server</h2>
<form><input name="target"><button>Ping!</button></form>
<?php
if (!isset($_GET['target'])) {
    die("Missing parameter.");
}
    $target = $_GET['target'];
    echo "<h3>Ping Result for: $target</h3>";
    $output = shell_exec("ping -c 2 " . $target);
    echo "<pre>$output</pre>";

?>
<?php include '_footer.php'; ?>