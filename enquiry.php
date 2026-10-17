<?php
require __DIR__ . '/inc/bootstrap.php';
header('Content-Type: application/json; charset=utf-8');

function out(int $code, array $body): void { http_response_code($code); echo json_encode($body, JSON_UNESCAPED_UNICODE); exit; }
function field(string $k, int $max): string { return mb_substr(trim((string)($_POST[$k] ?? '')), 0, $max); }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') out(405, ['ok' => false]);

$lang = ($_POST['lang'] ?? 'en') === 'zh' ? 'zh' : 'en';

// Spam checks: hidden "website" field must stay empty, and the form must be on screen for 3+ seconds
if (field('website', 200) !== '') out(200, ['ok' => true]);
$ts = (int)($_POST['ts'] ?? 0);
if ($ts > 0 && time() - $ts < 3) out(200, ['ok' => true]);

$name = field('name', 200);
$email = field('email', 200);
if ($name === '') out(422, ['ok' => false, 'error' => t('form.err_name', $lang)]);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) out(422, ['ok' => false, 'error' => t('form.err_email', $lang)]);

// Max 5 enquiries per IP per hour
$ip = client_ip();
$since = date('Y-m-d H:i:s', time() - 3600);
$st = db()->prepare('SELECT COUNT(*) FROM enquiries WHERE ip = ? AND created_at > ?');
$st->execute([$ip, $since]);
if ((int)$st->fetchColumn() >= 5) out(429, ['ok' => false, 'error' => t('form.fail', $lang)]);

$row = [
    'created_at' => date('Y-m-d H:i:s'), 'lang' => $lang, 'name' => $name, 'email' => $email,
    'phone' => field('phone', 60), 'country' => field('country', 120), 'company' => field('company', 200),
    'interest' => field('interest', 200), 'budget' => field('budget', 120), 'message' => field('message', 5000), 'ip' => $ip,
];
try {
    db()->prepare('INSERT INTO enquiries (created_at, lang, name, email, phone, country, company, interest, budget, message, ip, is_read) VALUES (?,?,?,?,?,?,?,?,?,?,?,0)')
        ->execute(array_values($row));
} catch (Throwable $e) {
    out(500, ['ok' => false, 'error' => t('form.fail', $lang)]);
}

// Email notification (optional)
$to = trim((string)($CONFIG['notify_email'] ?? ''));
if ($to !== '' && function_exists('mail')) {
    $from = $CONFIG['mail_from'] ?: 'no-reply@' . preg_replace('/^www\./', '', $_SERVER['HTTP_HOST'] ?? 'localhost');
    $subject = '=?UTF-8?B?' . base64_encode('New enquiry from investperak.com: ' . $name) . '?=';
    $body = "New website enquiry\n\n";
    foreach (['name' => 'Name', 'email' => 'Email', 'phone' => 'Phone', 'country' => 'Country', 'company' => 'Company', 'interest' => 'Interested in', 'budget' => 'Investment', 'lang' => 'Language'] as $k => $label) {
        $body .= str_pad($label . ':', 16) . $row[$k] . "\n";
    }
    $body .= "\nMessage:\n" . $row['message'] . "\n\nReply directly to this email to answer the enquirer.\n";
    $safeReply = preg_replace('/[\r\n]+/', '', $email);
    $headers = "From: Invest Perak <$from>\r\nReply-To: $safeReply\r\nMIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\n";
    @mail($to, $subject, $body, $headers);
}

out(200, ['ok' => true]);
