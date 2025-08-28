<?php include 'auth.php'; ?>
<?php include '_header.php'; ?>
<h2>Dashboard</h2>
<p>Welcome <b><?php echo htmlspecialchars($_SESSION['user']); ?></b>!</p>
<p>Use the menu above to access the web page.</p>
<?php include '_footer.php'; ?>
