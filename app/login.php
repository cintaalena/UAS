<?php
include 'auth.php';

class Profile {
    public $username;
    public $isAdmin = false;

    function __construct($u, $isAdmin = false) {
        $this->username = $u;
        $this->isAdmin = $isAdmin;
    }

    function __toString() {
        return "User: {$this->username}, Role: " . ($this->isAdmin ? "Admin" : "User");
    }
}

if ($_POST) {
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';

    // Prepared statement (hindari SQL injection)
    $stmt = $GLOBALS['PDO']->prepare("SELECT username, password, role FROM users WHERE username = ?");
    $stmt->execute([$u]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $isValid = false;
    if ($row) {
        // Jika sudah migrasi ke password_hash(), gunakan password_verify()
        if (password_get_info($row['password'])['algo'] !== 0) {
            $isValid = password_verify($p, $row['password']);
        } else {
            // Fallback sementara untuk data lama plaintext (lihat patch di init.php untuk migrasi)
            $isValid = hash_equals($row['password'], $p);
        }
    }

    if ($isValid) {
        // Harden session: cegah session fixation
        session_regenerate_id(true);
        $_SESSION['user'] = $row['username'];
        $_SESSION['role'] = $row['role'];

        // Hapus cookie serialized (berisiko). Gunakan session saja untuk profil.
        if (isset($_COOKIE['profile'])) {
            setcookie('profile', '', time() - 3600, '/', '', true, true);
        }

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Login failed.";
    }
}
?>
<?php include '_header.php'; ?>
<h2>Login</h2>
<?php if (!empty($error)) echo "<p style='color:red'>" . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . "</p>"; ?>
<form method="post">
  <label>Username <input name="username"></label>
  <label>Password <input type="password" name="password"></label>
  <button type="submit">Login</button>
</form>
<?php include '_footer.php'; ?>
