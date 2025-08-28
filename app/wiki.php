<?php include 'auth.php'; ?>
<?php include '_header.php'; ?>
<h2>Wiki Search</h2>
<form><input name="q"><button>Search</button></form>
<?php
if (isset($_GET['q'])) {
    $q = $_GET['q'];
    $sql = "SELECT * FROM articles WHERE title LIKE '%$q%'";
    echo "<p>Query: $sql</p>";
    $res = $GLOBALS['PDO']->query($sql);
    foreach ($res as $row) {
        echo "<li>{$row['title']}: {$row['body']}</li>";
    }
}
?>
<?php include '_footer.php'; ?>