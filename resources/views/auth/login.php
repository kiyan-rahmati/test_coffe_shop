<?php $title = 'ورود به حساب | کافه نارون'; require BASE_PATH . '/resources/views/layouts/header.php'; ?>
<main class="form-page"><form class="form-card" method="post" action="/login"><span class="eyebrow">خوش برگشتی</span><h1>ورود به حساب</h1><?= csrf_field() ?>
<?php if (!empty($error)): ?><p class="alert"><?= e($error) ?></p><?php endif; ?>
<?php if (isset($_GET['registered'])): ?><p class="success">ثبت‌نام انجام شد؛ حالا وارد شو.</p><?php endif; ?>
<label>ایمیل<input type="email" name="email" required autocomplete="email"></label><label>رمز عبور<input type="password" name="password" required autocomplete="current-password"></label><button class="button" type="submit">ورود</button><p>حساب نداری؟ <a href="/register">ثبت‌نام کن</a></p></form></main>
<?php require BASE_PATH . '/resources/views/layouts/footer.php'; ?>
