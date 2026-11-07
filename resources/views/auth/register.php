<?php $title = 'ثبت‌نام | کافه نارون'; require BASE_PATH . '/resources/views/layouts/header.php'; ?>
<main class="form-page"><form class="form-card" method="post" action="/register"><span class="eyebrow">به جمع نارون خوش آمدی</span><h1>ساخت حساب کاربری</h1><?= csrf_field() ?>
<?php if (!empty($error)): ?><p class="alert"><?= e($error) ?></p><?php endif; ?>
<label>نام و نام خانوادگی<input name="name" required maxlength="120" autocomplete="name"></label><label>ایمیل<input type="email" name="email" required autocomplete="email"></label><label>رمز عبور (حداقل ۱۰ کاراکتر)<input type="password" name="password" minlength="10" required autocomplete="new-password"></label><button class="button" type="submit">ثبت‌نام</button><p>قبلاً حساب ساختی؟ <a href="/login">وارد شو</a></p></form></main>
<?php require BASE_PATH . '/resources/views/layouts/footer.php'; ?>
