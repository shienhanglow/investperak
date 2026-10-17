<?php
declare(strict_types=1);

define('ROOT', dirname(__DIR__));
define('UPLOAD_DIR', ROOT . '/uploads');
define('UPLOAD_URL', 'uploads');

if (!is_file(ROOT . '/config.php')) {
    http_response_code(500);
    exit('Setup needed: copy config.sample.php to config.php and enter your database details, then open install.php.');
}
$CONFIG = require ROOT . '/config.php';
date_default_timezone_set($CONFIG['timezone'] ?? 'Asia/Kuala_Lumpur');

if (!empty($CONFIG['debug'])) { ini_set('display_errors', '1'); error_reporting(E_ALL); }
else { ini_set('display_errors', '0'); }

require_once __DIR__ . '/schema.php';

function db(): PDO {
    static $pdo = null;
    global $CONFIG;
    if ($pdo) return $pdo;
    try {
        if (!empty($CONFIG['sqlite_path'])) {
            $pdo = new PDO('sqlite:' . $CONFIG['sqlite_path']);
        } else {
            $dsn = 'mysql:host=' . $CONFIG['db_host'] . ';dbname=' . $CONFIG['db_name'] . ';charset=utf8mb4';
            $pdo = new PDO($dsn, $CONFIG['db_user'], $CONFIG['db_pass']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        exit('Could not connect to the database. Check the details in config.php.' . (!empty($CONFIG['debug']) ? ' ' . $e->getMessage() : ''));
    }
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
}
function is_sqlite(): bool { return db()->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite'; }

function e($s): string { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
/** Escape text and turn line breaks into <br> */
function nl($s): string { return nl2br(e($s), false); }
/** Split a textarea into non-empty lines */
function lines($s): array { return array_values(array_filter(array_map('trim', preg_split('/\R/u', (string)$s)), fn($x) => $x !== '')); }

/* ---------- content (single texts) ---------- */
function content_all(): array {
    static $cache = null;
    if ($cache !== null) return $cache;
    $cache = [];
    foreach (db()->query('SELECT k, en, zh FROM content') as $r) $cache[$r['k']] = $r;
    return $cache;
}
/** Text for key in language; falls back to English, then schema default. */
function t(string $key, string $lang = 'en'): string {
    $all = content_all();
    if (isset($all[$key])) {
        $v = $lang === 'zh' ? ($all[$key]['zh'] ?? '') : ($all[$key]['en'] ?? '');
        if ($v !== '' && $v !== null) return (string)$v;
        if ($lang === 'zh' && ($all[$key]['en'] ?? '') !== '') return (string)$all[$key]['en'];
    }
    $f = field_def($key);
    if ($f) return (string)($lang === 'zh' && isset($f['zh']) ? $f['zh'] : ($f['en'] ?? ''));
    return '';
}
function content_save(string $k, string $en, string $zh): void {
    $st = db()->prepare('REPLACE INTO content (k, en, zh) VALUES (?, ?, ?)');
    $st->execute([$k, $en, $zh]);
}

/* ---------- items (repeatable lists) ---------- */
function items(string $type, bool $onlyVisible = true): array {
    $sql = 'SELECT * FROM items WHERE type = ?' . ($onlyVisible ? ' AND visible = 1' : '') . ' ORDER BY sort, id';
    $st = db()->prepare($sql);
    $st->execute([$type]);
    $out = [];
    foreach ($st as $r) { $r['d'] = json_decode($r['data'] ?: '{}', true) ?: []; $out[] = $r; }
    return $out;
}
/** Field value of an item in language (bilingual fields stored as name_en / name_zh) */
function iv(array $item, string $field, string $lang = 'en'): string {
    $d = $item['d'];
    if (array_key_exists($field, $d)) return (string)$d[$field];
    $v = $d[$field . '_' . $lang] ?? '';
    if ($v === '' && $lang === 'zh') $v = $d[$field . '_en'] ?? '';
    return (string)$v;
}
function item_get(int $id): ?array {
    $st = db()->prepare('SELECT * FROM items WHERE id = ?');
    $st->execute([$id]);
    $r = $st->fetch();
    if (!$r) return null;
    $r['d'] = json_decode($r['data'] ?: '{}', true) ?: [];
    return $r;
}
function item_save(?int $id, string $type, array $data, int $visible = 1): int {
    $json = json_encode($data, JSON_UNESCAPED_UNICODE);
    if ($id) {
        db()->prepare('UPDATE items SET data = ?, visible = ? WHERE id = ?')->execute([$json, $visible, $id]);
        return $id;
    }
    $st = db()->prepare('SELECT COALESCE(MAX(sort), 0) + 10 FROM items WHERE type = ?');
    $st->execute([$type]);
    $sort = (int)$st->fetchColumn();
    db()->prepare('INSERT INTO items (type, sort, data, visible) VALUES (?, ?, ?, ?)')->execute([$type, $sort, $json, $visible]);
    return (int)db()->lastInsertId();
}

/* ---------- sessions & security ---------- */
function start_session(): void {
    if (session_status() === PHP_SESSION_ACTIVE) return;
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_name('ipcms');
    session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'secure' => $secure, 'httponly' => true, 'samesite' => 'Lax']);
    session_start();
}
function csrf_token(): string {
    start_session();
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field(): string { return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">'; }
function csrf_check(): void {
    start_session();
    if (!hash_equals($_SESSION['csrf'] ?? '', (string)($_POST['csrf'] ?? ''))) {
        http_response_code(400);
        exit('Your session expired. Go back, reload the page and try again.');
    }
}
function client_ip(): string { return substr((string)($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45); }
