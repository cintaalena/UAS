<?php
include 'auth.php';
include '_header.php';

$msg = '';
if ($_POST && isset($_POST['delete_user'])) {
    $target = $_POST['target'] ?? '';
    $stmt = $GLOBALS['PDO']->prepare("DELETE FROM users WHERE username = ?");
    $stmt->execute([$target]);
    $msg = "User deleted: " . $target;
}
?>
<h2>Profile</h2>
<?php if (!empty($msg)) echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?>

<form method="post">
  <input name="target" placeholder="username to delete">
  <button type="submit" name="delete_user" value="1">Delete</button>
</form>

<?php include '_footer.php'; ?>
