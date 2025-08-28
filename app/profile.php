<?php
include 'auth.php';
include '_header.php';

$msg = '';
if ($_POST && isset($_POST['delete_user'])) {
    csrf_verify(); // ← Tambah verifikasi CSRF
    if (($_SESSION['role'] ?? '') !== 'admin') {
        http_response_code(403);
        $msg = 'Forbidden: admin only.';
    } else {
        $target = $_POST['target'] ?? '';
        // (Opsional) cegah hapus akun admin
        if ($target === 'admin') {
            $msg = 'Refused: cannot delete admin.';
        } else {
            $stmt = $GLOBALS['PDO']->prepare("DELETE FROM users WHERE username = ?");
            $stmt->execute([$target]);
            $msg = "User deleted: " . htmlspecialchars($target, ENT_QUOTES, 'UTF-8');
        }
    }
}
?>
<h2>Profile</h2>
<?php if (!empty($msg)) echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?>

<form method="post">
  <?= csrf_field() ?>
  <input name="target" placeholder="username to delete">
  <button type="submit" name="delete_user" value="1">Delete</button>
</form>

<?php include '_footer.php'; ?>
