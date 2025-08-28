<?php
$dbFile = __DIR__ . '/data/app.db';
$needSeed = !file_exists($dbFile);

$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($needSeed) {
    $pdo->exec("
        CREATE TABLE users(id INTEGER PRIMARY KEY, username TEXT, password TEXT, role TEXT);
        CREATE TABLE articles(id INTEGER PRIMARY KEY, title TEXT, body TEXT);
        CREATE TABLE comments(id INTEGER PRIMARY KEY, author TEXT, content TEXT, created_at TEXT);
    ");

    // Seed user dengan password yang sudah di-hash
    $alicePwd = password_hash('alice123', PASSWORD_DEFAULT);
    $adminPwd = password_hash('admin123', PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users(username,password,role) VALUES(?,?,?)");
    $stmt->execute(['alice', $alicePwd, 'user']);
    $stmt->execute(['admin', $adminPwd, 'admin']);

    $pdo->exec("INSERT INTO articles(title,body) VALUES('PHP','Server side scripting')");
    $pdo->exec("INSERT INTO articles(title,body) VALUES('Java','Programming language')");
}

$GLOBALS['PDO'] = $pdo;
