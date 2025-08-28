<?php include 'auth.php'; ?>
<?php include '_header.php'; ?>
<h2>Wiki</h2>

<form method="get">
  <input name="q" value="<?= isset($_GET['q']) ? htmlspecialchars((string)$_GET['q'], ENT_QUOTES, 'UTF-8') : '' ?>" placeholder="Search title...">
  <button type="submit">Search</button>
</form>

<?php
$q = isset($_GET['q']) ? (string)$_GET['q'] : '';
if ($q !== '') {
    $like = '%' . $q . '%';
    $stmt = $GLOBALS['PDO']->prepare("SELECT title, body FROM articles WHERE title LIKE ?");
    $stmt->execute([$like]);
    echo "<p>Query: " . htmlspecialchars($q, ENT_QUOTES, 'UTF-8') . "</p>";
    foreach ($stmt as $row) {
        $title = htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');
        $body  = htmlspecialchars($row['body'],  ENT_QUOTES, 'UTF-8');
        echo "<li>{$title}: {$body}</li>";
    }
}
?>
<?php include '_footer.php'; ?>
