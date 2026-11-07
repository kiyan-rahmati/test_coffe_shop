<?php
// API ساده‌ی کافه نارون: ذخیره‌سازی در فایل JSON (بدون دیتابیس)
require __DIR__ . '/../config.php';
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'path' => '/']);
session_start();

function out($data, $code = 200) { http_response_code($code); echo json_encode($data, JSON_UNESCAPED_UNICODE); exit; }
function fail($msg, $code = 400) { out(['error' => $msg], $code); }
function rd($name, $def) {
    $f = DATA_DIR . "/$name.json";
    if (!is_file($f)) return $def;
    $j = json_decode(file_get_contents($f), true);
    return $j === null ? $def : $j;
}
function wr($name, $data) {
    if (!is_dir(DATA_DIR)) mkdir(DATA_DIR, 0775, true);
    file_put_contents(DATA_DIR . "/$name.json", json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);
}
function body() { $j = json_decode(file_get_contents('php://input'), true); return is_array($j) ? $j : []; }
function pub($u) { return ['username' => $u['username'], 'name' => $u['name'] ?? '', 'job' => $u['job'] ?? '', 'prefs' => (object)($u['prefs'] ?? [])]; }
function need_user() { if (empty($_SESSION['user'])) fail('ابتدا وارد شوید', 401); return $_SESSION['user']; }
function need_admin() { if (empty($_SESSION['admin'])) fail('دسترسی ادمین لازم است', 403); }
function clean($s, $max = 200) { return mb_substr(trim(strip_tags((string)$s)), 0, $max); }

$action = $_GET['action'] ?? '';
$in = $_SERVER['REQUEST_METHOD'] === 'POST' ? body() : [];

switch ($action) {
    case 'ping': out(['ok' => true, 'php' => PHP_VERSION]);

    case 'menu': out(['menu' => rd('menu', null)]);

    case 'menu_save':
        need_admin();
        $menu = $in['menu'] ?? null;
        if (!is_array($menu)) fail('منوی نامعتبر');
        $safe = [];
        foreach ($menu as $m) {
            $safe[] = ['id' => clean($m['id'] ?? uniqid('i'), 40), 'cat' => clean($m['cat'] ?? 'hot', 20), 'name' => clean($m['name'] ?? '', 80),
                'desc' => clean($m['desc'] ?? '', 200), 'price' => max(0, (int)($m['price'] ?? 0)), 'img' => clean($m['img'] ?? '', 200),
                'tag' => clean($m['tag'] ?? '', 20), 'on' => !empty($m['on'])];
        }
        wr('menu', $safe); out(['ok' => true]);

    case 'register':
        $u = clean($in['username'] ?? '', 30); $p = (string)($in['password'] ?? '');
        if (!preg_match('/^[A-Za-z0-9_.-]{3,30}$/', $u)) fail('نام کاربری فقط انگلیسی/عدد و حداقل ۳ حرف باشد');
        if (strlen($p) < 4) fail('رمز عبور حداقل ۴ کاراکتر باشد');
        $users = rd('users', []);
        if (isset($users[$u])) fail('این نام کاربری قبلاً ثبت شده است', 409);
        $users[$u] = ['username' => $u, 'name' => clean($in['name'] ?? ''), 'job' => clean($in['job'] ?? '', 30), 'pass' => password_hash($p, PASSWORD_DEFAULT),
            'prefs' => is_array($in['prefs'] ?? null) ? array_intersect_key($in['prefs'], ['theme' => 1, 'accent' => 1, 'fs' => 1]) : [], 'created' => time() * 1000];
        wr('users', $users); session_regenerate_id(true); $_SESSION['user'] = $u;
        out(['user' => pub($users[$u])]);

    case 'login':
        $u = clean($in['username'] ?? '', 30); $users = rd('users', []);
        if (!isset($users[$u]) || !password_verify((string)($in['password'] ?? ''), $users[$u]['pass'])) { sleep(1); fail('نام کاربری یا رمز عبور اشتباه است', 401); }
        session_regenerate_id(true); $_SESSION['user'] = $u; out(['user' => pub($users[$u])]);

    case 'logout': unset($_SESSION['user']); out(['ok' => true]);

    case 'me':
        $u = $_SESSION['user'] ?? null; $users = rd('users', []);
        out(['user' => ($u && isset($users[$u])) ? pub($users[$u]) : null]);

    case 'prefs':
        $n = need_user(); $users = rd('users', []);
        if (!isset($users[$n])) fail('کاربر پیدا نشد', 404);
        $p = is_array($in['prefs'] ?? null) ? array_intersect_key($in['prefs'], ['theme' => 1, 'accent' => 1, 'fs' => 1]) : [];
        $users[$n]['prefs'] = array_merge($users[$n]['prefs'] ?? [], $p);
        if (isset($in['name'])) $users[$n]['name'] = clean($in['name']);
        if (isset($in['job'])) $users[$n]['job'] = clean($in['job'], 30);
        wr('users', $users); out(['user' => pub($users[$n])]);

    case 'order':
        $n = need_user(); $menu = rd('menu', null); $items = []; $total = 0;
        foreach (($in['items'] ?? []) as $it) {
            $q = max(1, min(50, (int)($it['q'] ?? 1))); $id = clean($it['id'] ?? '', 40); $name = clean($it['name'] ?? '', 80); $price = max(0, (int)($it['price'] ?? 0));
            if ($menu) { // قیمت را از منوی سرور می‌گیریم تا دستکاری نشود
                $found = null; foreach ($menu as $m) if ($m['id'] === $id && !empty($m['on'])) $found = $m;
                if (!$found) continue; $name = $found['name']; $price = (int)$found['price'];
            }
            $items[] = ['id' => $id, 'name' => $name, 'price' => $price, 'q' => $q]; $total += $price * $q;
        }
        if (!$items) fail('سبد خالی است');
        $type = in_array($in['type'] ?? '', ['dine', 'pickup', 'delivery'], true) ? $in['type'] : 'dine';
        $orders = rd('orders', []);
        $o = ['id' => 'N' . (1001 + count($orders)), 'user' => $n, 'items' => $items, 'total' => $total, 'type' => $type, 'note' => clean($in['note'] ?? ''), 'address' => clean($in['address'] ?? '', 250), 'status' => 'new', 'created' => time() * 1000];
        $orders[] = $o; wr('orders', $orders); out(['order' => $o]);

    case 'orders':
        $n = need_user(); $mine = array_values(array_filter(rd('orders', []), fn($o) => $o['user'] === $n));
        out(['orders' => array_reverse($mine)]);

    case 'admin_login':
        $ok = ($in['user'] ?? '') === ADMIN_USER && (ADMIN_HASH ? password_verify((string)($in['pass'] ?? ''), ADMIN_HASH) : hash_equals(ADMIN_DEFAULT_PASS, (string)($in['pass'] ?? '')));
        if (!$ok) { sleep(1); fail('نام کاربری یا رمز ادمین اشتباه است', 401); }
        session_regenerate_id(true); $_SESSION['admin'] = true; out(['ok' => true]);

    case 'admin_logout': unset($_SESSION['admin']); out(['ok' => true]);
    case 'admin_me': out(['admin' => !empty($_SESSION['admin'])]);
    case 'admin_orders': need_admin(); out(['orders' => array_reverse(rd('orders', []))]);

    case 'admin_order_status':
        need_admin(); $st = $in['status'] ?? '';
        if (!in_array($st, ['new', 'preparing', 'ready', 'delivered', 'canceled'], true)) fail('وضعیت نامعتبر');
        $orders = rd('orders', []);
        foreach ($orders as &$o) if ($o['id'] === ($in['id'] ?? '')) $o['status'] = $st;
        unset($o); wr('orders', $orders); out(['ok' => true]);

    default: fail('عملیات ناشناخته', 404);
}
