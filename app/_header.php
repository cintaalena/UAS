<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Secure Coding Exam Lab</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
<header>
  <h1>Secure Coding Exam Lab</h1>
  <?php if (isset($_SESSION['user'])): ?>
    <nav>
      <a href="dashboard.php">Dashboard</a> |
      <a href="profile.php">My Profile</a> |
      <a href="wiki.php">Wiki Search</a> |
      <a href="comment.php">Comments</a> |
      <a href="crash.php">Crash Test</a> |
      <a href="ping.php">Ping Test</a> |
      <a href="logout.php">Logout</a>
    </nav>
  <?php endif; ?>
  <hr>
</header>
