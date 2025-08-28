<?php include 'auth.php'; ?>
<?php include '_header.php'; ?>
<h2>Post comments</h2>
<form method="post">
  <input name="author" placeholder="Name...">
  <textarea name="content" placeholder="Comments..."></textarea>
  <button>Post</button>
</form>

<?php
if ($_POST) {
    $stmt = $GLOBALS['PDO']->prepare("INSERT INTO comments(author,content,created_at) VALUES(?,?,datetime('now'))");
    $stmt->execute([$_POST['author'], $_POST['content']]);
}
?>
<h3>Comment lists : </h3>
<?php
foreach ($GLOBALS['PDO']->query("SELECT * FROM comments ORDER BY id DESC") as $row) {
    echo "<p><b>{$row['author']}</b>: {$row['content']}</p>";
}
?>
<?php include '_footer.php'; ?>