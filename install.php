<?php
require __DIR__ . '/inc/bootstrap.php';

function table_exists(string $t): bool {
    try { db()->query("SELECT 1 FROM $t LIMIT 1"); return true; } catch (Throwable $e) { return false; }
}
function create_tables(): void {
    if (is_sqlite()) {
        $id = 'INTEGER PRIMARY KEY AUTOINCREMENT'; $txt = 'TEXT'; $tail = '';
    } else {
        $id = 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY'; $txt = 'MEDIUMTEXT'; $tail = ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';
    }
    $pdo = db();
    $pdo->exec("CREATE TABLE IF NOT EXISTS content (k VARCHAR(100) NOT NULL PRIMARY KEY, en $txt, zh $txt)$tail");
    $pdo->exec("CREATE TABLE IF NOT EXISTS items (id $id, type VARCHAR(40) NOT NULL, sort INT NOT NULL DEFAULT 0, visible TINYINT NOT NULL DEFAULT 1, data $txt)$tail");
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (id $id, username VARCHAR(60) NOT NULL UNIQUE, pass_hash VARCHAR(255) NOT NULL, created_at VARCHAR(25))$tail");
    $pdo->exec("CREATE TABLE IF NOT EXISTS enquiries (id $id, created_at VARCHAR(25), lang VARCHAR(5), name VARCHAR(200), email VARCHAR(200), phone VARCHAR(60), country VARCHAR(120), company VARCHAR(200), interest VARCHAR(200), budget VARCHAR(120), message $txt, ip VARCHAR(45), is_read TINYINT NOT NULL DEFAULT 0)$tail");
    $pdo->exec("CREATE TABLE IF NOT EXISTS login_attempts (id $id, ip VARCHAR(45), at_time INT)$tail");
}
function seed(): void {
    global $TEXT_GROUPS;
    $have = (int)db()->query('SELECT COUNT(*) FROM content')->fetchColumn();
    if ($have === 0) {
        foreach ($TEXT_GROUPS as $g) foreach ($g['fields'] as $k => $f) content_save($k, (string)$f['en'], (string)$f['zh']);
    }
    $haveItems = (int)db()->query('SELECT COUNT(*) FROM items')->fetchColumn();
    if ($haveItems === 0) {
        foreach (seed_items() as $type => $list) foreach ($list as $raw) item_save(null, $type, flatten_item($raw));
    }
}

$locked = table_exists('users') && (int)db()->query('SELECT COUNT(*) FROM users')->fetchColumn() > 0;
$msg = ''; $done = false;

if (!$locked && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim((string)($_POST['username'] ?? ''));
    $p = (string)($_POST['password'] ?? '');
    $p2 = (string)($_POST['password2'] ?? '');
    if (!preg_match('/^[A-Za-z0-9_.@-]{3,60}$/', $u)) $msg = 'Username must be 3–60 letters, numbers or . _ @ -';
    elseif (strlen($p) < 10) $msg = 'Password must be at least 10 characters.';
    elseif ($p !== $p2) $msg = 'The two passwords do not match.';
    else {
        create_tables();
        seed();
        db()->prepare('INSERT INTO users (username, pass_hash, created_at) VALUES (?, ?, ?)')->execute([$u, password_hash($p, PASSWORD_DEFAULT), date('Y-m-d H:i:s')]);
        if (!is_dir(UPLOAD_DIR)) @mkdir(UPLOAD_DIR, 0755, true);
        $done = true;
    }
}
?><!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Install · Invest Perak CMS</title><meta name="robots" content="noindex">
<link rel="stylesheet" href="admin/admin.css"></head>
<body class="auth"><main class="auth-card">
<h1>Invest Perak CMS setup</h1>
<?php if ($locked && !$done): ?>
  <p>Setup is already complete. For security, delete <code>install.php</code> from your hosting.</p>
  <p><a class="btn" href="admin/">Go to the admin login</a></p>
<?php elseif ($done): ?>
  <p class="ok">Setup complete. The database tables and starting content are in place.</p>
  <p><b>Next:</b> delete <code>install.php</code> from your hosting in cPanel File Manager, then sign in.</p>
  <p><a class="btn" href="admin/">Go to the admin login</a></p>
<?php else: ?>
  <p>This creates the database tables, loads the starting English and 中文 content, and creates your admin login.</p>
  <?php if ($msg): ?><p class="err"><?= e($msg) ?></p><?php endif; ?>
  <form method="post">
    <label>Admin username<input name="username" required value="<?= e($_POST['username'] ?? 'admin') ?>" autocomplete="username"></label>
    <label>Password (10+ characters)<input name="password" type="password" required autocomplete="new-password"></label>
    <label>Repeat password<input name="password2" type="password" required autocomplete="new-password"></label>
    <button class="btn" type="submit">Install</button>
  </form>
<?php endif; ?>
</main></body></html>
