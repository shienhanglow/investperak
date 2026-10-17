<?php
require dirname(__DIR__) . '/inc/bootstrap.php';
start_session();
header('X-Frame-Options: DENY');
header('X-Robots-Tag: noindex, nofollow');

$a = $_GET['a'] ?? 'dash';

/* ---------------- auth ---------------- */
function logged_in(): bool {
    if (empty($_SESSION['uid'])) return false;
    if (time() - ($_SESSION['seen'] ?? 0) > 7200) { session_unset(); return false; } // 2 h idle timeout
    $_SESSION['seen'] = time();
    return true;
}
function go(string $qs, string $flash = ''): void {
    if ($flash !== '') $_SESSION['flash'] = $flash;
    header('Location: index.php' . ($qs ? '?' . $qs : ''));
    exit;
}
function too_many_attempts(): bool {
    $st = db()->prepare('SELECT COUNT(*) FROM login_attempts WHERE ip = ? AND at_time > ?');
    $st->execute([client_ip(), time() - 900]);
    return (int)$st->fetchColumn() >= 8;
}

if ($a === 'logout') { session_unset(); session_destroy(); header('Location: index.php'); exit; }

if (!logged_in()) {
    $err = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        if (too_many_attempts()) $err = 'Too many attempts. Wait 15 minutes and try again.';
        else {
            $st = db()->prepare('SELECT * FROM users WHERE username = ?');
            $st->execute([trim((string)($_POST['username'] ?? ''))]);
            $u = $st->fetch();
            if ($u && password_verify((string)($_POST['password'] ?? ''), $u['pass_hash'])) {
                session_regenerate_id(true);
                $_SESSION['uid'] = (int)$u['id']; $_SESSION['uname'] = $u['username']; $_SESSION['seen'] = time();
                db()->prepare('DELETE FROM login_attempts WHERE ip = ?')->execute([client_ip()]);
                go('');
            }
            db()->prepare('INSERT INTO login_attempts (ip, at_time) VALUES (?, ?)')->execute([client_ip(), time()]);
            $err = 'Wrong username or password.';
        }
    }
    ?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sign in · Invest Perak CMS</title><link rel="stylesheet" href="admin.css"></head>
    <body class="auth"><main class="auth-card">
      <h1>Invest Perak CMS</h1><p class="muted">Sign in to edit investperak.com</p>
      <?php if ($err): ?><p class="err"><?= e($err) ?></p><?php endif; ?>
      <form method="post"><?= csrf_field() ?>
        <label>Username<input name="username" required autocomplete="username" autofocus></label>
        <label>Password<input name="password" type="password" required autocomplete="current-password"></label>
        <button class="btn" type="submit">Sign in</button>
      </form>
    </main></body></html><?php
    exit;
}

/* ---------------- helpers ---------------- */
function save_upload(string $input): ?string {
    if (empty($_FILES[$input]) || $_FILES[$input]['error'] === UPLOAD_ERR_NO_FILE) return null;
    $f = $_FILES[$input];
    if ($f['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('Upload failed (error ' . $f['error'] . '). The file may be too large for your hosting limit.');
    if ($f['size'] > 6 * 1024 * 1024) throw new RuntimeException('Photo is larger than 6 MB. Please resize it first.');
    $info = @getimagesize($f['tmp_name']);
    $types = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    if (!$info || !isset($types[$info['mime']])) throw new RuntimeException('Please upload a JPG, PNG, WebP or GIF image.');
    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
    $name = date('Ymd') . '-' . bin2hex(random_bytes(6)) . '.' . $types[$info['mime']];
    $dest = UPLOAD_DIR . '/' . $name;
    // Downsize very large JPG/PNG/WebP to 2000px wide to keep pages fast
    $resized = false;
    if (function_exists('imagecreatetruecolor') && $info[0] > 2000 && $info['mime'] !== 'image/gif') {
        $src = match ($info['mime']) { 'image/jpeg' => @imagecreatefromjpeg($f['tmp_name']), 'image/png' => @imagecreatefrompng($f['tmp_name']), 'image/webp' => @imagecreatefromwebp($f['tmp_name']), default => false };
        if ($src) {
            $w = 2000; $h = (int)round($info[1] * $w / $info[0]);
            $dst = imagecreatetruecolor($w, $h);
            if ($info['mime'] !== 'image/jpeg') { imagealphablending($dst, false); imagesavealpha($dst, true); }
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $info[0], $info[1]);
            $resized = match ($info['mime']) { 'image/jpeg' => imagejpeg($dst, $dest, 85), 'image/png' => imagepng($dst, $dest, 6), 'image/webp' => imagewebp($dst, $dest, 85) };
        }
    }
    if (!$resized && !move_uploaded_file($f['tmp_name'], $dest)) throw new RuntimeException('Could not save the photo. Check that the uploads folder is writable.');
    @chmod($dest, 0644);
    return $name;
}
function delete_upload(string $name): void {
    $name = basename($name);
    if ($name !== '' && is_file(UPLOAD_DIR . '/' . $name)) @unlink(UPLOAD_DIR . '/' . $name);
}
function item_title(array $it, array $def): string {
    $k = $def['title'];
    $v = iv($it, $k, 'en');
    return $v !== '' ? $v : '(untitled)';
}
function unread_count(): int { return (int)db()->query('SELECT COUNT(*) FROM enquiries WHERE is_read = 0')->fetchColumn(); }

$flash = $_SESSION['flash'] ?? ''; unset($_SESSION['flash']);
$error = '';

/* ---------------- POST actions ---------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $do = $_POST['do'] ?? '';
    try {
        if ($do === 'texts') {
            $g = $_POST['g'] ?? '';
            if (!isset($TEXT_GROUPS[$g])) throw new RuntimeException('Unknown section.');
            foreach ($TEXT_GROUPS[$g]['fields'] as $k => $f) {
                $id = str_replace('.', '__', $k);
                $en = (string)($_POST[$id . '__en'] ?? '');
                $zh = $f['bi'] ? (string)($_POST[$id . '__zh'] ?? '') : '';
                content_save($k, str_replace("\r\n", "\n", $en), str_replace("\r\n", "\n", $zh));
            }
            go('a=texts&g=' . urlencode($g), 'Saved. Changes are live on the website.');
        }
        if ($do === 'item_save') {
            $t = $_POST['t'] ?? '';
            if (!isset($ITEM_TYPES[$t])) throw new RuntimeException('Unknown list.');
            $id = (int)($_POST['id'] ?? 0);
            $old = $id ? item_get($id) : null;
            $data = $old['d'] ?? [];
            foreach ($ITEM_TYPES[$t]['fields'] as $name => $fd) {
                [$label, $type, $bi] = $fd;
                if ($type === 'image') {
                    $new = save_upload($name . '_file');
                    if ($new) { if (!empty($data[$name])) delete_upload($data[$name]); $data[$name] = $new; }
                    elseif (!empty($_POST[$name . '_remove'])) { if (!empty($data[$name])) delete_upload($data[$name]); $data[$name] = ''; }
                    else $data[$name] = $data[$name] ?? '';
                } elseif ($type === 'check') {
                    $data[$name] = !empty($_POST[$name]) ? '1' : '';
                } elseif ($bi) {
                    $data[$name . '_en'] = str_replace("\r\n", "\n", trim((string)($_POST[$name . '_en'] ?? '')));
                    $data[$name . '_zh'] = str_replace("\r\n", "\n", trim((string)($_POST[$name . '_zh'] ?? '')));
                } else {
                    $v = str_replace("\r\n", "\n", trim((string)($_POST[$name] ?? '')));
                    if ($type === 'select' && !array_key_exists($v, $fd[3])) $v = array_key_first($fd[3]);
                    $data[$name] = $v;
                }
            }
            $newId = item_save($id ?: null, $t, $data, !empty($_POST['visible']) ? 1 : 0);
            go('a=list&t=' . urlencode($t), 'Saved. Changes are live on the website.');
        }
        if ($do === 'item_delete') {
            $it = item_get((int)$_POST['id']);
            if ($it) {
                foreach ($ITEM_TYPES[$it['type']]['fields'] ?? [] as $name => $fd) if ($fd[1] === 'image' && !empty($it['d'][$name])) delete_upload($it['d'][$name]);
                db()->prepare('DELETE FROM items WHERE id = ?')->execute([$it['id']]);
                go('a=list&t=' . urlencode($it['type']), 'Deleted.');
            }
        }
        if ($do === 'item_move') {
            $it = item_get((int)$_POST['id']);
            if ($it) {
                $list = items($it['type'], false);
                $ids = array_column($list, 'id');
                $i = array_search($it['id'], $ids);
                $j = $_POST['dir'] === 'up' ? $i - 1 : $i + 1;
                if ($j >= 0 && $j < count($ids)) { [$ids[$i], $ids[$j]] = [$ids[$j], $ids[$i]]; }
                $st = db()->prepare('UPDATE items SET sort = ? WHERE id = ?');
                foreach ($ids as $n => $iid) $st->execute([($n + 1) * 10, $iid]);
                go('a=list&t=' . urlencode($it['type']));
            }
        }
        if ($do === 'item_toggle') {
            $it = item_get((int)$_POST['id']);
            if ($it) { db()->prepare('UPDATE items SET visible = ? WHERE id = ?')->execute([$it['visible'] ? 0 : 1, $it['id']]); go('a=list&t=' . urlencode($it['type']), $it['visible'] ? 'Hidden from the website.' : 'Shown on the website.'); }
        }
        if ($do === 'enq_delete') {
            db()->prepare('DELETE FROM enquiries WHERE id = ?')->execute([(int)$_POST['id']]);
            go('a=enq', 'Enquiry deleted.');
        }
        if ($do === 'enq_unread') {
            db()->prepare('UPDATE enquiries SET is_read = 0 WHERE id = ?')->execute([(int)$_POST['id']]);
            go('a=enq', 'Marked as unread.');
        }
        if ($do === 'password') {
            $st = db()->prepare('SELECT * FROM users WHERE id = ?'); $st->execute([$_SESSION['uid']]); $u = $st->fetch();
            if (!$u || !password_verify((string)$_POST['current'], $u['pass_hash'])) throw new RuntimeException('Current password is wrong.');
            if (strlen((string)$_POST['new']) < 10) throw new RuntimeException('New password must be at least 10 characters.');
            if ($_POST['new'] !== $_POST['new2']) throw new RuntimeException('The two new passwords do not match.');
            db()->prepare('UPDATE users SET pass_hash = ? WHERE id = ?')->execute([password_hash((string)$_POST['new'], PASSWORD_DEFAULT), $u['id']]);
            go('a=account', 'Password changed.');
        }
        if ($do === 'user_add') {
            $un = trim((string)$_POST['username']);
            if (!preg_match('/^[A-Za-z0-9_.@-]{3,60}$/', $un)) throw new RuntimeException('Username must be 3–60 letters, numbers or . _ @ -');
            if (strlen((string)$_POST['password']) < 10) throw new RuntimeException('Password must be at least 10 characters.');
            $st = db()->prepare('SELECT COUNT(*) FROM users WHERE username = ?'); $st->execute([$un]);
            if ($st->fetchColumn()) throw new RuntimeException('That username already exists.');
            db()->prepare('INSERT INTO users (username, pass_hash, created_at) VALUES (?, ?, ?)')->execute([$un, password_hash((string)$_POST['password'], PASSWORD_DEFAULT), date('Y-m-d H:i:s')]);
            go('a=account', 'Admin user added.');
        }
        if ($do === 'user_delete') {
            $uid = (int)$_POST['id'];
            if ($uid === (int)$_SESSION['uid']) throw new RuntimeException('You cannot delete your own login.');
            db()->prepare('DELETE FROM users WHERE id = ?')->execute([$uid]);
            go('a=account', 'Admin user removed.');
        }
    } catch (Throwable $ex) {
        $error = $ex instanceof RuntimeException ? $ex->getMessage() : 'Something went wrong while saving. ' . (!empty($CONFIG['debug']) ? $ex->getMessage() : '');
    }
}

/* CSV export */
if ($a === 'enq_csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="investperak-enquiries-' . date('Ymd') . '.csv"');
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF");
    fputcsv($out, ['Date', 'Language', 'Name', 'Email', 'Phone', 'Country', 'Company', 'Interested in', 'Investment', 'Message']);
    foreach (db()->query('SELECT * FROM enquiries ORDER BY id DESC') as $r) {
        fputcsv($out, [$r['created_at'], $r['lang'], $r['name'], $r['email'], $r['phone'], $r['country'], $r['company'], $r['interest'], $r['budget'], $r['message']]);
    }
    exit;
}

/* ---------------- layout ---------------- */
$GROUP_PAGES = ['home' => 'Home', 'why' => 'Why Perak', 'guide' => 'Investor Guide', 'gallery' => 'Gallery', 'about' => 'About Us', 'partners' => 'Partners', 'faq' => 'FAQ'];
function nav_link(string $qs, string $label, bool $on, string $extra = ''): string {
    return '<a href="index.php?' . e($qs) . '"' . ($on ? ' class="on"' : '') . '>' . e($label) . $extra . '</a>';
}
function field_input(string $name, string $type, string $value, array $opts = [], string $lang = ''): string {
    $id = e($name);
    $attr = $lang === 'zh' ? ' lang="zh-Hans"' : '';
    switch ($type) {
        case 'textarea': return '<textarea id="' . $id . '" name="' . $id . '" rows="4"' . $attr . '>' . e($value) . '</textarea>';
        case 'lines': return '<textarea id="' . $id . '" name="' . $id . '" rows="' . max(3, min(10, count(lines($value)) + 1)) . '"' . $attr . '>' . e($value) . '</textarea>';
        case 'select':
            $h = '<select id="' . $id . '" name="' . $id . '">';
            foreach ($opts as $k => $v) $h .= '<option value="' . e($k) . '"' . ((string)$k === $value ? ' selected' : '') . '>' . e($v) . '</option>';
            return $h . '</select>';
        default: return '<input id="' . $id . '" name="' . $id . '" value="' . e($value) . '"' . $attr . '>';
    }
}
$unread = unread_count();
ob_start();

/* ---------------- pages ---------------- */
if ($a === 'dash') {
    $title = 'Dashboard';
    $recent = db()->query('SELECT * FROM enquiries ORDER BY id DESC LIMIT 5')->fetchAll();
    ?>
    <div class="cards">
      <a class="card" href="index.php?a=enq"><b><?= $unread ?></b><span>Unread enquiries</span></a>
      <a class="card" href="index.php?a=list&t=gallery"><b><?= count(items('gallery', false)) ?></b><span>Gallery photos</span></a>
      <a class="card" href="index.php?a=list&t=partner"><b><?= count(items('partner', false)) ?></b><span>Partners</span></a>
      <a class="card" href="index.php?a=list&t=faq"><b><?= count(items('faq', false)) ?></b><span>FAQ questions</span></a>
    </div>
    <section class="panel">
      <h2>Quick edits</h2>
      <ul class="quick">
        <li><a href="index.php?a=texts&g=general">Change phone, WhatsApp, email or address</a></li>
        <li><a href="index.php?a=edit&t=gallery">Upload a new gallery photo</a></li>
        <li><a href="index.php?a=list&t=partner">Edit partner details</a></li>
        <li><a href="index.php?a=list&t=rule">Update investment regulations</a></li>
        <li><a href="index.php?a=texts&g=home">Edit the home page headline</a></li>
      </ul>
    </section>
    <section class="panel">
      <h2>Latest enquiries</h2>
      <?php if (!$recent): ?><p class="muted">No enquiries yet. Messages sent from the website's contact form appear here.</p>
      <?php else: ?><table class="tbl"><thead><tr><th>Date</th><th>Name</th><th>Interested in</th><th></th></tr></thead><tbody>
        <?php foreach ($recent as $r): ?><tr class="<?= $r['is_read'] ? '' : 'unread' ?>"><td class="nowrap"><?= e(substr($r['created_at'], 0, 16)) ?></td><td><?= e($r['name']) ?></td><td><?= e($r['interest']) ?></td><td><a href="index.php?a=enq_view&id=<?= (int)$r['id'] ?>">Open</a></td></tr><?php endforeach; ?>
      </tbody></table><?php endif; ?>
    </section>
    <?php
}
elseif ($a === 'texts') {
    $g = $_GET['g'] ?? 'general';
    if (!isset($TEXT_GROUPS[$g])) $g = 'general';
    $grp = $TEXT_GROUPS[$g];
    $title = $grp['label'];
    $all = content_all();
    ?>
    <form method="post" class="edit"><?= csrf_field() ?><input type="hidden" name="do" value="texts"><input type="hidden" name="g" value="<?= e($g) ?>">
      <p class="muted">Edit the wording below. English is on the left, 中文 on the right. If a 中文 box is empty, the English text is shown instead. Use <code>**double asterisks**</code> to make words bold in longer texts.</p>
      <?php foreach ($grp['fields'] as $k => $f):
        $id = str_replace('.', '__', $k);
        $en = $all[$k]['en'] ?? $f['en']; $zh = $all[$k]['zh'] ?? $f['zh']; ?>
        <div class="fld<?= $f['bi'] ? ' bi' : '' ?>">
          <div class="fld-head"><b><?= e($f['label']) ?></b><?php if ($f['type'] === 'lines'): ?><span class="hint">One per line</span><?php endif; ?><?php if ($f['help']): ?><span class="hint"><?= e($f['help']) ?></span><?php endif; ?></div>
          <?php if ($f['bi']): ?>
            <label><span class="lng">EN</span><?= field_input($id . '__en', $f['type'], (string)$en) ?></label>
            <label><span class="lng">中文</span><?= field_input($id . '__zh', $f['type'], (string)$zh, [], 'zh') ?></label>
          <?php else: ?>
            <label><?= field_input($id . '__en', $f['type'], (string)$en) ?></label>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
      <div class="savebar"><button class="btn" type="submit">Save changes</button><a class="btn ghost" href="../#<?= $g === 'general' || $g === 'nav' || $g === 'footer' ? 'contact' : e($g) ?>" target="_blank">View on website</a></div>
    </form>
    <?php
}
elseif ($a === 'list') {
    $t = $_GET['t'] ?? '';
    if (!isset($ITEM_TYPES[$t])) go('');
    $def = $ITEM_TYPES[$t];
    $title = $def['label'];
    $list = items($t, false);
    $hasImg = (bool)array_filter($def['fields'], fn($f) => $f[1] === 'image');
    ?>
    <div class="toolbar"><a class="btn" href="index.php?a=edit&t=<?= e($t) ?>">+ Add new</a><span class="muted">Use the arrows to change the order on the website.</span></div>
    <?php if (!$list): ?><p class="panel muted">Nothing here yet. Select “Add new” to create the first one.</p><?php else: ?>
    <table class="tbl list">
      <thead><tr><?php if ($hasImg): ?><th></th><?php endif; ?><th>Title</th><th>Status</th><th>Order</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($list as $n => $it): ?>
        <tr class="<?= $it['visible'] ? '' : 'off' ?>">
          <?php if ($hasImg): $img = ''; foreach ($def['fields'] as $nm => $fd) if ($fd[1] === 'image' && !empty($it['d'][$nm])) { $img = $it['d'][$nm]; break; } ?>
            <td class="thumb"><?php if ($img): ?><img src="../<?= e(UPLOAD_URL . '/' . rawurlencode($img)) ?>" alt=""><?php else: ?><span class="noimg">No photo</span><?php endif; ?></td>
          <?php endif; ?>
          <td><a href="index.php?a=edit&t=<?= e($t) ?>&id=<?= (int)$it['id'] ?>"><b><?= e(item_title($it, $def)) ?></b></a><?php $zh = iv($it, $def['title'], 'zh'); if (!empty($it['d'][$def['title'] . '_zh'])): ?><br><span class="muted" lang="zh-Hans"><?= e($zh) ?></span><?php endif; ?></td>
          <td><form method="post" class="inline"><?= csrf_field() ?><input type="hidden" name="do" value="item_toggle"><input type="hidden" name="id" value="<?= (int)$it['id'] ?>"><button class="pill <?= $it['visible'] ? 'on' : '' ?>" title="Click to <?= $it['visible'] ? 'hide' : 'show' ?>"><?= $it['visible'] ? 'Shown' : 'Hidden' ?></button></form></td>
          <td class="nowrap">
            <form method="post" class="inline"><?= csrf_field() ?><input type="hidden" name="do" value="item_move"><input type="hidden" name="id" value="<?= (int)$it['id'] ?>"><input type="hidden" name="dir" value="up"><button class="icon" <?= $n === 0 ? 'disabled' : '' ?> aria-label="Move up">↑</button></form>
            <form method="post" class="inline"><?= csrf_field() ?><input type="hidden" name="do" value="item_move"><input type="hidden" name="id" value="<?= (int)$it['id'] ?>"><input type="hidden" name="dir" value="down"><button class="icon" <?= $n === count($list) - 1 ? 'disabled' : '' ?> aria-label="Move down">↓</button></form>
          </td>
          <td class="nowrap right"><a href="index.php?a=edit&t=<?= e($t) ?>&id=<?= (int)$it['id'] ?>">Edit</a>
            <form method="post" class="inline" onsubmit="return confirm('Delete “<?= e(addslashes(item_title($it, $def))) ?>”? This cannot be undone.')"><?= csrf_field() ?><input type="hidden" name="do" value="item_delete"><input type="hidden" name="id" value="<?= (int)$it['id'] ?>"><button class="link danger">Delete</button></form></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif;
}
elseif ($a === 'edit') {
    $t = $_GET['t'] ?? '';
    if (!isset($ITEM_TYPES[$t])) go('');
    $def = $ITEM_TYPES[$t];
    $id = (int)($_GET['id'] ?? 0);
    $it = $id ? item_get($id) : null;
    if ($id && (!$it || $it['type'] !== $t)) go('a=list&t=' . urlencode($t), 'That entry no longer exists.');
    $d = $it['d'] ?? [];
    $title = ($it ? 'Edit: ' : 'Add to: ') . $def['label'];
    ?>
    <form method="post" class="edit" enctype="multipart/form-data"><?= csrf_field() ?>
      <input type="hidden" name="do" value="item_save"><input type="hidden" name="t" value="<?= e($t) ?>"><input type="hidden" name="id" value="<?= $id ?>">
      <?php foreach ($def['fields'] as $name => $fd): [$label, $type, $bi] = $fd; $opts = $fd[3] ?? []; ?>
        <div class="fld<?= $bi ? ' bi' : '' ?>">
          <div class="fld-head"><b><?= e($label) ?></b><?php if ($type === 'lines'): ?><span class="hint">One per line · **bold** allowed</span><?php endif; ?></div>
          <?php if ($type === 'image'): $cur = $d[$name] ?? ''; ?>
            <div class="imgfld">
              <?php if ($cur): ?><img src="../<?= e(UPLOAD_URL . '/' . rawurlencode($cur)) ?>" alt=""><?php endif; ?>
              <div><input type="file" name="<?= e($name) ?>_file" accept="image/jpeg,image/png,image/webp,image/gif">
              <?php if ($cur): ?><label class="check"><input type="checkbox" name="<?= e($name) ?>_remove" value="1"> Remove current image</label><?php endif; ?></div>
            </div>
          <?php elseif ($type === 'check'): ?>
            <label class="check"><input type="checkbox" name="<?= e($name) ?>" value="1" <?= !empty($d[$name]) ? 'checked' : '' ?>> Yes</label>
          <?php elseif ($bi): ?>
            <label><span class="lng">EN</span><?= field_input($name . '_en', $type, (string)($d[$name . '_en'] ?? '')) ?></label>
            <label><span class="lng">中文</span><?= field_input($name . '_zh', $type, (string)($d[$name . '_zh'] ?? ''), [], 'zh') ?></label>
          <?php else: ?>
            <label><?= field_input($name, $type, (string)($d[$name] ?? ''), $opts) ?></label>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
      <div class="fld"><label class="check"><input type="checkbox" name="visible" value="1" <?= (!$it || $it['visible']) ? 'checked' : '' ?>> Show on website</label></div>
      <div class="savebar"><button class="btn" type="submit">Save</button><a class="btn ghost" href="index.php?a=list&t=<?= e($t) ?>">Cancel</a></div>
    </form>
    <?php
}
elseif ($a === 'enq') {
    $title = 'Enquiries';
    $rows = db()->query('SELECT * FROM enquiries ORDER BY id DESC LIMIT 500')->fetchAll();
    ?>
    <div class="toolbar"><a class="btn ghost" href="index.php?a=enq_csv">Download as spreadsheet (CSV)</a><span class="muted">New enquiries are also emailed to <?= e($CONFIG['notify_email'] ?: '(email notifications off)') ?>.</span></div>
    <?php if (!$rows): ?><p class="panel muted">No enquiries yet.</p><?php else: ?>
    <table class="tbl"><thead><tr><th>Date</th><th>Name</th><th>Email</th><th>Interested in</th><th>Lang</th><th></th></tr></thead><tbody>
      <?php foreach ($rows as $r): ?><tr class="<?= $r['is_read'] ? '' : 'unread' ?>">
        <td class="nowrap"><?= e(substr($r['created_at'], 0, 16)) ?></td><td><a href="index.php?a=enq_view&id=<?= (int)$r['id'] ?>"><?= e($r['name']) ?></a></td><td><?= e($r['email']) ?></td><td><?= e($r['interest']) ?></td><td><?= $r['lang'] === 'zh' ? '中文' : 'EN' ?></td>
        <td class="right"><a href="index.php?a=enq_view&id=<?= (int)$r['id'] ?>">Open</a></td></tr><?php endforeach; ?>
    </tbody></table><?php endif;
}
elseif ($a === 'enq_view') {
    $st = db()->prepare('SELECT * FROM enquiries WHERE id = ?'); $st->execute([(int)($_GET['id'] ?? 0)]); $r = $st->fetch();
    if (!$r) go('a=enq', 'That enquiry no longer exists.');
    if (!$r['is_read']) { db()->prepare('UPDATE enquiries SET is_read = 1 WHERE id = ?')->execute([$r['id']]); $unread = unread_count(); }
    $title = 'Enquiry from ' . $r['name'];
    ?>
    <section class="panel">
      <dl class="kv">
        <dt>Received</dt><dd><?= e($r['created_at']) ?> · <?= $r['lang'] === 'zh' ? 'sent from the 中文 site' : 'sent from the English site' ?></dd>
        <dt>Name</dt><dd><?= e($r['name']) ?></dd>
        <dt>Email</dt><dd><a href="mailto:<?= e($r['email']) ?>"><?= e($r['email']) ?></a></dd>
        <dt>Phone</dt><dd><?= e($r['phone'] ?: '—') ?><?php if ($r['phone']): ?> · <a href="https://wa.me/<?= e(preg_replace('/\D+/', '', $r['phone'])) ?>" target="_blank" rel="noopener">WhatsApp</a><?php endif; ?></dd>
        <dt>Country</dt><dd><?= e($r['country'] ?: '—') ?></dd>
        <dt>Company</dt><dd><?= e($r['company'] ?: '—') ?></dd>
        <dt>Interested in</dt><dd><?= e($r['interest']) ?></dd>
        <dt>Investment</dt><dd><?= e($r['budget']) ?></dd>
        <dt>Message</dt><dd class="msg"><?= nl($r['message'] ?: '—') ?></dd>
      </dl>
    </section>
    <div class="savebar">
      <a class="btn" href="mailto:<?= e($r['email']) ?>?subject=<?= rawurlencode('Re: your enquiry to investperak.com') ?>">Reply by email</a>
      <form method="post" class="inline"><?= csrf_field() ?><input type="hidden" name="do" value="enq_unread"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><button class="btn ghost">Mark as unread</button></form>
      <form method="post" class="inline" onsubmit="return confirm('Delete this enquiry? This cannot be undone.')"><?= csrf_field() ?><input type="hidden" name="do" value="enq_delete"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><button class="btn ghost danger">Delete</button></form>
      <a class="btn ghost" href="index.php?a=enq">Back to list</a>
    </div>
    <?php
}
elseif ($a === 'account') {
    $title = 'Account & admin users';
    $users = db()->query('SELECT id, username, created_at FROM users ORDER BY id')->fetchAll();
    ?>
    <section class="panel"><h2>Change your password</h2>
      <form method="post" class="stack"><?= csrf_field() ?><input type="hidden" name="do" value="password">
        <label>Current password<input type="password" name="current" required autocomplete="current-password"></label>
        <label>New password (10+ characters)<input type="password" name="new" required autocomplete="new-password"></label>
        <label>Repeat new password<input type="password" name="new2" required autocomplete="new-password"></label>
        <button class="btn">Change password</button>
      </form>
    </section>
    <section class="panel"><h2>Admin users</h2>
      <table class="tbl"><tbody><?php foreach ($users as $u): ?><tr><td><b><?= e($u['username']) ?></b><?= (int)$u['id'] === (int)$_SESSION['uid'] ? ' <span class="muted">(you)</span>' : '' ?></td><td class="muted">Added <?= e(substr((string)$u['created_at'], 0, 10)) ?></td>
        <td class="right"><?php if ((int)$u['id'] !== (int)$_SESSION['uid']): ?><form method="post" class="inline" onsubmit="return confirm('Remove this admin user?')"><?= csrf_field() ?><input type="hidden" name="do" value="user_delete"><input type="hidden" name="id" value="<?= (int)$u['id'] ?>"><button class="link danger">Remove</button></form><?php endif; ?></td></tr><?php endforeach; ?></tbody></table>
      <h3>Add an admin user</h3>
      <form method="post" class="stack"><?= csrf_field() ?><input type="hidden" name="do" value="user_add">
        <label>Username<input name="username" required autocomplete="off"></label>
        <label>Password (10+ characters)<input type="password" name="password" required autocomplete="new-password"></label>
        <button class="btn">Add user</button>
      </form>
    </section>
    <?php
}
else go('');

$body = ob_get_clean();
?><!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($title) ?> · Invest Perak CMS</title><link rel="stylesheet" href="admin.css?v=<?= @filemtime(__DIR__ . '/admin.css') ?>"></head>
<body>
<header class="top"><a class="logo" href="index.php">Invest Perak <span>CMS</span></a>
  <button class="navbtn" type="button" onclick="document.body.classList.toggle('navopen')">Menu</button>
  <div class="who"><a href="../" target="_blank">View website ↗</a><a href="index.php?a=account"><?= e($_SESSION['uname'] ?? '') ?></a><a href="index.php?a=logout">Sign out</a></div></header>
<div class="shell">
  <nav class="side">
    <?= nav_link('a=dash', 'Dashboard', $a === 'dash') ?>
    <?= nav_link('a=enq', 'Enquiries', in_array($a, ['enq', 'enq_view'], true), $unread ? ' <span class="badge">' . $unread . '</span>' : '') ?>
    <p class="sec">Site settings</p>
    <?= nav_link('a=texts&g=general', 'Contact details', $a === 'texts' && ($_GET['g'] ?? '') === 'general') ?>
    <?= nav_link('a=texts&g=nav', 'Menu labels', $a === 'texts' && ($_GET['g'] ?? '') === 'nav') ?>
    <?= nav_link('a=texts&g=footer', 'Footer', $a === 'texts' && ($_GET['g'] ?? '') === 'footer') ?>
    <?php foreach ($GROUP_PAGES as $g => $label): ?>
      <p class="sec"><?= e($label) ?></p>
      <?= nav_link('a=texts&g=' . $g, 'Page text', $a === 'texts' && ($_GET['g'] ?? '') === $g) ?>
      <?php foreach ($ITEM_TYPES as $ty => $td): if ($td['group'] !== $g) continue; $short = preg_replace('/^.*?–\s*/u', '', $td['label']); ?>
        <?= nav_link('a=list&t=' . $ty, ucfirst($short), in_array($a, ['list', 'edit'], true) && ($_GET['t'] ?? '') === $ty) ?>
      <?php endforeach; ?>
    <?php endforeach; ?>
    <p class="sec">Contact</p>
    <?= nav_link('a=texts&g=contact', 'Page text & form', $a === 'texts' && ($_GET['g'] ?? '') === 'contact') ?>
  </nav>
  <main class="content">
    <h1><?= e($title) ?></h1>
    <?php if ($flash): ?><p class="ok"><?= e($flash) ?></p><?php endif; ?>
    <?php if ($error): ?><p class="err"><?= e($error) ?></p><?php endif; ?>
    <?= $body ?>
  </main>
</div>
</body></html>
