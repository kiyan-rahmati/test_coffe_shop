<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#34251f">
  <meta name="description" content="کافه نارون؛ قهوه، کتاب، حال خوب و مهربانی با حیوانات.">
  <title>کافه نارون | یک فنجان خاطره</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800&family=Noto+Nastaliq+Urdu&display=swap" rel="stylesheet">
  <style>
    :root {
      --ink: #30231e;
      --coffee: #4b3027;
      --coffee-light: #765342;
      --cream: #f5ead7;
      --paper: #fbf5e9;
      --gold: #c69a58;
      --red: #9e4938;
      --green: #59664a;
      --muted: #827365;
      --line: rgba(72, 48, 36, .16);
      --shadow: 0 18px 50px rgba(54, 36, 27, .12);
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; scroll-padding-top: 88px; }
    body {
      background: var(--paper);
      color: var(--ink);
      font-family: "Vazirmatn", sans-serif;
      line-height: 1.9;
      overflow-x: hidden;
    }
    a { color: inherit; text-decoration: none; }
    button { font: inherit; }
    .container { width: min(1160px, calc(100% - 40px)); margin-inline: auto; }
    .top-note {
      background: var(--coffee); color: #f7e9d2; text-align: center;
      padding: 7px 15px; font-size: .78rem; letter-spacing: .02em;
    }
    .site-header {
      position: sticky; top: 0; z-index: 50;
      background: rgba(251,245,233,.94); backdrop-filter: blur(14px);
      border-bottom: 1px solid var(--line);
    }
    .nav-wrap { min-height: 76px; display: flex; align-items: center; justify-content: space-between; gap: 24px; }
    .brand { display: flex; align-items: center; gap: 11px; white-space: nowrap; }
    .brand-mark {
      width: 44px; height: 44px; display: grid; place-items: center;
      border: 1px solid var(--gold); border-radius: 50%; color: var(--gold); font-size: 1.35rem;
    }
    .brand-name { font-weight: 800; font-size: 1.18rem; line-height: 1.3; }
    .brand-name small { display: block; color: var(--muted); font-size: .65rem; font-weight: 400; letter-spacing: .13em; }
    .nav-links { display: flex; align-items: center; gap: clamp(12px, 2.2vw, 30px); color: #655247; font-size: .88rem; }
    .nav-links a { transition: color .2s; }
    .nav-links a:hover { color: var(--red); }
    .nav-actions { display: flex; align-items: center; gap: 10px; }
    .cart-button, .menu-toggle {
      border: 1px solid var(--line); background: transparent; color: var(--coffee);
      padding: 9px 14px; border-radius: 999px; cursor: pointer;
    }
    .cart-count { display: inline-grid; place-items: center; min-width: 21px; height: 21px; border-radius: 50%; background: var(--red); color: white; font-size: .72rem; margin-right: 4px; }
    .menu-toggle { display: none; font-size: 1.2rem; }
    .hero {
      min-height: 650px; display: grid; align-items: center; position: relative; isolation: isolate;
      color: #fff4df; overflow: hidden;
      background: linear-gradient(90deg, rgba(35,24,20,.90), rgba(48,31,24,.74) 48%, rgba(48,31,24,.28)),
        url("https://images.unsplash.com/photo-1442512595331-e89e73853f31?auto=format&fit=crop&w=2000&q=85") center/cover;
    }
    .hero::before {
      content: ""; position: absolute; inset: 18px; border: 1px solid rgba(229,194,137,.35);
      pointer-events: none; z-index: -1;
    }
    .hero-content { max-width: 690px; padding: 100px 0 115px; }
    .eyebrow { color: #e0bb80; font-size: .82rem; letter-spacing: .12em; margin-bottom: 18px; }
    .hero h1 { font-size: clamp(2.5rem, 6vw, 5.2rem); line-height: 1.35; font-weight: 700; margin-bottom: 18px; }
    .hero h1 span { color: #e5bd7b; font-family: "Noto Nastaliq Urdu", serif; font-weight: 400; }
    .hero p { color: rgba(255,244,223,.82); max-width: 520px; font-size: 1.02rem; }
    .hero-buttons { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 32px; }
    .btn {
      display: inline-flex; justify-content: center; align-items: center; gap: 8px;
      padding: 12px 23px; border-radius: 4px; border: 1px solid transparent;
      cursor: pointer; transition: transform .2s, background .2s; font-weight: 600; font-size: .9rem;
    }
    .btn:hover { transform: translateY(-3px); }
    .btn-gold { background: var(--gold); color: #2e211b; }
    .btn-outline { border-color: rgba(255,241,217,.55); color: #fff4df; background: transparent; }
    .hero-stamp {
      position: absolute; left: max(8%, calc((100vw - 1160px)/2)); bottom: 70px;
      width: 118px; height: 118px; border: 1px solid rgba(230,193,131,.7); border-radius: 50%;
      display: grid; place-items: center; text-align: center; color: #e9c98f; font-size: .76rem; line-height: 1.7;
      transform: rotate(-12deg);
    }
    .section { padding: 100px 0; }
    .section-head { text-align: center; max-width: 650px; margin: 0 auto 45px; }
    .section-kicker { color: var(--red); font-size: .78rem; font-weight: 700; letter-spacing: .12em; }
    .section-title { font-size: clamp(1.8rem, 4vw, 2.8rem); line-height: 1.5; margin: 8px 0 10px; color: var(--coffee); }
    .section-desc { color: var(--muted); font-size: .93rem; }
    .menu-section { background: #f1e5d1; }
    .menu-filters { display: flex; justify-content: center; flex-wrap: wrap; gap: 9px; margin-bottom: 30px; }
    .filter-btn { border: 1px solid #d6c4a9; background: transparent; color: #6e5949; padding: 8px 17px; border-radius: 99px; cursor: pointer; font-size: .82rem; }
    .filter-btn.active, .filter-btn:hover { background: var(--coffee); color: white; border-color: var(--coffee); }
    .product-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
    .product-card { background: var(--paper); border: 1px solid rgba(82,56,40,.1); box-shadow: 0 8px 25px rgba(58,38,27,.06); transition: transform .25s, box-shadow .25s; }
    .product-card:hover { transform: translateY(-6px); box-shadow: var(--shadow); }
    .product-card[hidden] { display: none; }
    .product-image { height: 220px; overflow: hidden; position: relative; }
    .product-image img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s; }
    .product-card:hover .product-image img { transform: scale(1.05); }
    .product-tag { position: absolute; top: 13px; right: 13px; background: var(--red); color: white; padding: 4px 11px; font-size: .7rem; }
    .product-info { padding: 19px; }
    .product-info h3 { font-size: 1.1rem; color: var(--coffee); }
    .product-info p { color: var(--muted); font-size: .82rem; margin: 5px 0 15px; min-height: 48px; }
    .product-bottom { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
    .price { color: var(--red); font-weight: 800; font-size: .95rem; }
    .add-button { background: var(--coffee); color: #fff2dc; border: 0; border-radius: 3px; padding: 8px 13px; cursor: pointer; font-size: .78rem; }
    .add-button:hover { background: var(--red); }
    .story-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 65px; align-items: center; }
    .story-photo { min-height: 450px; position: relative; }
    .story-photo img { width: 88%; height: 430px; object-fit: cover; display: block; }
    .story-photo::after { content: ""; position: absolute; width: 75%; height: 75%; border: 1px solid var(--gold); bottom: 0; left: 0; z-index: -1; }
    .story-copy h2 { font-size: clamp(1.8rem, 4vw, 2.7rem); color: var(--coffee); line-height: 1.5; margin: 10px 0 18px; }
    .story-copy p { color: var(--muted); margin-bottom: 15px; }
    .signature { font-family: "Noto Nastaliq Urdu", serif; color: var(--red); font-size: 1.5rem; margin-top: 20px; }
    .book-section { background: #e9ddc9; position: relative; overflow: hidden; }
    .book-section::before, .book-section::after {
      content: "✦"; position: absolute; color: rgba(158,73,56,.12); font-size: 13rem; pointer-events: none;
    }
    .book-section::before { top: -70px; right: 2%; }
    .book-section::after { bottom: -110px; left: 4%; }
    .book-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; position: relative; z-index: 1; }
    .book-card { background: #fbf5e9; padding: 24px; border: 1px solid #ddcbb0; display: flex; gap: 18px; align-items: flex-start; }
    .book-cover { width: 90px; min-width: 90px; height: 132px; display: grid; place-items: center; text-align: center; padding: 8px; color: #f9ebd1; background: linear-gradient(145deg, #6b4436, #35241e); box-shadow: 5px 6px 0 #d2b994; font-family: "Noto Nastaliq Urdu", serif; font-size: 1rem; }
    .book-card:nth-child(2) .book-cover { background: linear-gradient(145deg, #69714e, #303b2b); }
    .book-card:nth-child(3) .book-cover { background: linear-gradient(145deg, #a65b43, #5b2d27); }
    .book-info h3 { color: var(--coffee); font-size: .98rem; margin-bottom: 3px; }
    .book-author { color: var(--red); font-size: .75rem; margin-bottom: 8px; }
    .book-info p { color: var(--muted); font-size: .78rem; line-height: 1.9; }
    .book-more { margin-top: 10px; border: 0; background: transparent; color: var(--red); font-size: .8rem; cursor: pointer; font-weight: 700; }
    .book-note { text-align: center; margin-top: 30px; color: #776653; font-size: .85rem; }
    .rug-section {
      padding: 90px 0; color: #f8ead4; text-align: center;
      background-color: #693c31;
      background-image:
        repeating-linear-gradient(45deg, transparent 0 22px, rgba(231,190,126,.11) 22px 24px, transparent 24px 46px),
        repeating-linear-gradient(-45deg, transparent 0 22px, rgba(231,190,126,.10) 22px 24px, transparent 24px 46px),
        radial-gradient(ellipse at center, #80503d 0%, #563229 78%);
      border-block: 10px solid #c49a5e;
      outline: 1px solid rgba(255,235,197,.25); outline-offset: -18px;
    }
    .rug-inner { max-width: 760px; margin: auto; padding: 20px; }
    .rug-section .section-kicker { color: #e7c58f; }
    .rug-section .section-title { color: #fff0d8; }
    .rug-section p { color: rgba(255,240,216,.82); }
    .rug-ornament { font-size: 2rem; color: #e2bd7f; margin-bottom: 15px; }
    .kindness-section { background: #f7f0e4; }
    .kindness-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center; }
    .kindness-image { min-height: 390px; }
    .kindness-image img { width: 100%; height: 390px; object-fit: cover; }
    .kindness-copy h2 { font-size: clamp(1.8rem, 4vw, 2.6rem); color: var(--coffee); line-height: 1.5; margin: 8px 0 15px; }
    .kindness-copy > p { color: var(--muted); margin-bottom: 22px; }
    .help-list { list-style: none; display: grid; gap: 13px; margin-bottom: 25px; }
    .help-list li { display: flex; gap: 12px; color: #66574b; font-size: .9rem; }
    .help-list li span { color: var(--green); font-weight: 800; }
    .btn-coffee { background: var(--coffee); color: #fff1d9; }
    .btn-coffee:hover { background: var(--red); }
    .support-note { font-size: .75rem; color: #8a7b6d; margin-top: 12px; }
    .visit-section { background: #e9ddc9; text-align: center; }
    .visit-card { max-width: 800px; margin: auto; padding: 45px; background: var(--paper); border: 1px solid #d8c4a6; box-shadow: var(--shadow); }
    .visit-card h2 { color: var(--coffee); font-size: 2rem; margin-bottom: 8px; }
    .visit-card p { color: var(--muted); }
    .footer { background: #30231e; color: #ead9bd; padding: 55px 0 20px; }
    .footer-grid { display: grid; grid-template-columns: 1.4fr 1fr 1fr; gap: 40px; padding-bottom: 35px; }
    .footer h3 { color: #d8b47b; margin-bottom: 12px; }
    .footer p, .footer a { color: #c6b39b; font-size: .82rem; }
    .footer-links { display: grid; gap: 7px; }
    .copyright { border-top: 1px solid rgba(255,255,255,.12); padding-top: 17px; text-align: center; color: #9c8873; font-size: .72rem; }
    .cart-panel {
      position: fixed; z-index: 100; top: 0; left: 0; width: min(390px, 100%);
      height: 100dvh; background: var(--paper); box-shadow: 0 0 0 100vmax rgba(25,17,13,.45);
      padding: 25px; transform: translateX(-110%); transition: transform .3s; display: flex; flex-direction: column;
    }
    .cart-panel.open { transform: translateX(0); }
    .cart-head { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--line); padding-bottom: 15px; }
    .cart-head h3 { color: var(--coffee); }
    .close-cart { border: 0; background: transparent; font-size: 1.5rem; cursor: pointer; color: var(--coffee); }
    .cart-items { flex: 1; overflow-y: auto; padding: 15px 0; }
    .cart-line { display: flex; justify-content: space-between; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid var(--line); font-size: .86rem; }
    .remove-item { border: 0; color: var(--red); background: transparent; cursor: pointer; }
    .cart-total { padding-top: 15px; border-top: 1px solid var(--line); display: flex; justify-content: space-between; font-weight: 800; color: var(--coffee); }
    .empty-cart { color: var(--muted); text-align: center; padding: 35px 0; }
    .toast { position: fixed; z-index: 120; bottom: 22px; right: 22px; background: var(--coffee); color: white; padding: 11px 18px; border-radius: 4px; opacity: 0; transform: translateY(15px); pointer-events: none; transition: .25s; font-size: .82rem; }
    .toast.show { opacity: 1; transform: translateY(0); }
    .book-modal { position: fixed; inset: 0; z-index: 110; display: none; place-items: center; padding: 20px; background: rgba(30,20,15,.65); }
    .book-modal.show { display: grid; }
    .modal-box { max-width: 460px; width: 100%; background: var(--paper); padding: 30px; position: relative; box-shadow: var(--shadow); }
    .modal-box h3 { color: var(--coffee); font-size: 1.35rem; }
    .modal-box p { color: var(--muted); margin-top: 12px; }
    .modal-close { position: absolute; top: 10px; left: 14px; border: 0; background: none; font-size: 1.5rem; cursor: pointer; color: var(--coffee); }
    @media (max-width: 900px) {
      .nav-links { gap: 13px; font-size: .8rem; }
      .product-grid, .book-grid { grid-template-columns: repeat(2, 1fr); }
      .story-grid, .kindness-grid { gap: 35px; }
      .hero-stamp { display: none; }
    }
    @media (max-width: 680px) {
      .nav-wrap { min-height: 68px; }
      .menu-toggle { display: inline-block; }
      .nav-links { display: none; position: absolute; top: 68px; right: 0; left: 0; background: var(--paper); padding: 18px 22px 23px; border-bottom: 1px solid var(--line); flex-direction: column; align-items: stretch; gap: 13px; }
      .nav-links.open { display: flex; }
      .cart-button { padding: 8px 10px; font-size: .78rem; }
      .hero { min-height: 570px; background-position: 58% center; }
      .hero-content { padding: 85px 0; }
      .hero::before { inset: 10px; }
      .section { padding: 72px 0; }
      .product-grid, .book-grid { grid-template-columns: 1fr; }
      .story-grid, .kindness-grid { grid-template-columns: 1fr; }
      .story-photo { min-height: 340px; }
      .story-photo img { height: 320px; width: 92%; }
      .kindness-image, .kindness-image img { min-height: 280px; height: 280px; }
      .book-card { max-width: 480px; margin-inline: auto; width: 100%; }
      .visit-card { padding: 30px 20px; }
      .footer-grid { grid-template-columns: 1fr; gap: 25px; }
    }
    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
    }
  </style>
</head>
<body>
  <div class="top-note">اینجا قهوه فقط یک نوشیدنی نیست؛ بهانه‌ای‌ست برای مهربانی ☕</div>

  <header class="site-header">
    <div class="container nav-wrap">
      <a class="brand" href="#home" aria-label="کافه نارون، صفحه اصلی">
        <span class="brand-mark">ن</span>
        <span class="brand-name">کافه نارون<small>یک فنجان خاطره</small></span>
      </a>
      <nav class="nav-links" id="navLinks" aria-label="منوی اصلی">
        <a href="#home">خانه</a>
        <a href="#menu">منوی کافه</a>
        <a href="#story">قصه نارون</a>
        <a href="#books">معرفی کتاب</a>
        <a href="#kindness">حمایت از حیوانات</a>
      </nav>
      <div class="nav-actions">
        <button class="cart-button" id="openCart" type="button">سبد سفارش <span class="cart-count" id="cartCount">0</span></button>
        <button class="menu-toggle" id="menuToggle" type="button" aria-label="باز کردن منو" aria-expanded="false">☰</button>
      </div>
    </div>
  </header>

  <main>
    <section class="hero" id="home">
      <div class="container">
        <div class="hero-content">
          <div class="eyebrow">قهوه، کتاب، فرهنگ و مهربانی</div>
          <h1>طعمِ آرامِ <span>روزهای خوب</span></h1>
          <p>به نارون خوش آمدی؛ جایی برای عطر قهوه تازه، ورق زدن یک کتاب دوست‌داشتنی و ساختن حال خوب برای خودمان و دیگران.</p>
          <div class="hero-buttons">
            <a class="btn btn-gold" href="#menu">دیدن منوی کافه ←</a>
            <a class="btn btn-outline" href="#books">رفتن به کتابخانه کوچک ما</a>
          </div>
        </div>
      </div>
      <div class="hero-stamp">از دلِ ایران<br>با عطرِ خاطره<br>✦</div>
    </section>

    <section class="section menu-section" id="menu">
      <div class="container">
        <div class="section-head">
          <div class="section-kicker">منوی نارون</div>
          <h2 class="section-title">یک فنجان برای حالِ دلت</h2>
          <p class="section-desc">از قهوه‌های محبوب تا نوشیدنی‌های گرم ایرانی؛ با حوصله انتخاب کن و از لحظه‌ات لذت ببر.</p>
        </div>
        <div class="menu-filters" role="group" aria-label="فیلتر دسته‌بندی منو">
          <button class="filter-btn active" data-filter="all" type="button">همه</button>
          <button class="filter-btn" data-filter="coffee" type="button">قهوه</button>
          <button class="filter-btn" data-filter="iranian" type="button">نوشیدنی ایرانی</button>
          <button class="filter-btn" data-filter="dessert" type="button">شیرینی</button>
        </div>
        <div class="product-grid" id="productGrid">
          <article class="product-card" data-category="coffee" data-name="اسپرسو" data-price="95000">
            <div class="product-image"><img src="https://images.unsplash.com/photo-1510707577719-ae7c14805e3a?auto=format&fit=crop&w=900&q=80" alt="فنجان اسپرسو" loading="lazy"><span class="product-tag">پیشنهاد باریستا</span></div>
            <div class="product-info"><h3>اسپرسو</h3><p>یک شات قهوه غلیظ و خوش‌عطر برای شروعی پرانرژی.</p><div class="product-bottom"><span class="price">۹۵٬۰۰۰ تومان</span><button class="add-button" type="button">افزودن به سفارش +</button></div></div>
          </article>
          <article class="product-card" data-category="coffee" data-name="لاته" data-price="145000">
            <div class="product-image"><img src="https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=900&q=80" alt="فنجان لاته" loading="lazy"></div>
            <div class="product-info"><h3>لاته</h3><p>ترکیب لطیف اسپرسو و شیر با بافتی نرم و دلنشین.</p><div class="product-bottom"><span class="price">۱۴۵٬۰۰۰ تومان</span><button class="add-button" type="button">افزودن به سفارش +</button></div></div>
          </article>
          <article class="product-card" data-category="coffee" data-name="قهوه دمی" data-price="135000">
            <div class="product-image"><img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=900&q=80" alt="قهوه دمی" loading="lazy"></div>
            <div class="product-info"><h3>قهوه دمی</h3><p>عطر آرام دانه‌های تازه‌آسیاب‌شده در یک فنجان بزرگ.</p><div class="product-bottom"><span class="price">۱۳۵٬۰۰۰ تومان</span><button class="add-button" type="button">افزودن به سفارش +</button></div></div>
          </article>
          <article class="product-card" data-category="iranian" data-name="چای زعفرانی" data-price="85000">
            <div class="product-image"><img src="https://images.unsplash.com/photo-1544787219-7f47ccb76574?auto=format&fit=crop&w=900&q=80" alt="چای در استکان شیشه‌ای" loading="lazy"></div>
            <div class="product-info"><h3>چای زعفرانی</h3><p>چای خوش‌رنگ ایرانی با عطر لطیف زعفران و هل.</p><div class="product-bottom"><span class="price">۸۵٬۰۰۰ تومان</span><button class="add-button" type="button">افزودن به سفارش +</button></div></div>
          </article>
          <article class="product-card" data-category="iranian" data-name="دمنوش بهارنارنج" data-price="90000">
            <div class="product-image"><img src="https://images.unsplash.com/photo-1576092768241-dec231879fc3?auto=format&fit=crop&w=900&q=80" alt="دمنوش گیاهی" loading="lazy"></div>
            <div class="product-info"><h3>دمنوش بهارنارنج</h3><p>یک فنجان آرامش با عطر بهارنارنج و گل‌محمدی.</p><div class="product-bottom"><span class="price">۹۰٬۰۰۰ تومان</span><button class="add-button" type="button">افزودن به سفارش +</button></div></div>
          </article>
          <article class="product-card" data-category="dessert" data-name="کیک گردویی" data-price="125000">
            <div class="product-image"><img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=900&q=80" alt="کیک خانگی" loading="lazy"></div>
            <div class="product-info"><h3>کیک گردویی</h3><p>کیک روزِ نارون؛ تازه، نرم و مناسب کنار قهوه.</p><div class="product-bottom"><span class="price">۱۲۵٬۰۰۰ تومان</span><button class="add-button" type="button">افزودن به سفارش +</button></div></div>
          </article>
        </div>
      </div>
    </section>

    <section class="section" id="story">
      <div class="container story-grid">
        <div class="story-photo">
          <img src="https://images.unsplash.com/photo-1445116572660-236099ec97a0?auto=format&fit=crop&w=1000&q=85" alt="فضای گرم و صمیمی یک کافه" loading="lazy">
        </div>
        <div class="story-copy">
          <div class="section-kicker">قصه نارون</div>
          <h2>کمی مکث کن؛<br>دنیا عجله‌ای ندارد.</h2>
          <p>نارون از یک فکر ساده شروع شد: کاش جایی باشد که آدم‌ها در آن فقط قهوه نخورند؛ کتابی ورق بزنند، گپی بزنند و چند دقیقه از شلوغی روز فاصله بگیرند.</p>
          <p>ما از نقش‌ونگار ایرانی، قصه‌های قدیمی، موسیقی آرام و دورهمی‌های گرم الهام می‌گیریم. دوست داریم هرکس با خاطره‌ای خوب از اینجا برود.</p>
          <div class="signature">به خانه خودت خوش آمدی</div>
        </div>
      </div>
    </section>

    <section class="section book-section" id="books">
      <div class="container">
        <div class="section-head">
          <div class="section-kicker">کتابخانه کوچک نارون</div>
          <h2 class="section-title">قهوه‌ات را بردار، به دنیای یک کتاب برو</h2>
          <p class="section-desc">هر ماه چند کتاب دوست‌داشتنی معرفی می‌کنیم؛ شاید همین امروز کتاب بعدیِ محبوبت را پیدا کنی.</p>
        </div>
        <div class="book-grid">
          <article class="book-card">
            <div class="book-cover">سمفونی<br>مردگان</div>
            <div class="book-info"><h3>سمفونی مردگان</h3><div class="book-author">عباس معروفی</div><p>روایتی ادبی و چندلایه از خانواده، خاطره و سرنوشت.</p><button class="book-more" type="button" data-book="symphony">معرفی کتاب ←</button></div>
          </article>
          <article class="book-card">
            <div class="book-cover">چشم‌هایش</div>
            <div class="book-info"><h3>چشم‌هایش</h3><div class="book-author">بزرگ علوی</div><p>رمانی رازآلود با نگاهی به عشق، هنر و روزگار گذشته.</p><button class="book-more" type="button" data-book="eyes">معرفی کتاب ←</button></div>
          </article>
          <article class="book-card">
            <div class="book-cover">شازده<br>کوچولو</div>
            <div class="book-info"><h3>شازده کوچولو</h3><div class="book-author">آنتوان دو سنت‌اگزوپری</div><p>قصه‌ای کوتاه و ماندگار درباره دوستی و دیدن با دل.</p><button class="book-more" type="button" data-book="prince">معرفی کتاب ←</button></div>
          </article>
        </div>
        <p class="book-note">پیشنهاد کتاب بعدی‌ات را به ما بگو؛ کتابخانه نارون با سلیقه شما بزرگ‌تر می‌شود.</p>
      </div>
    </section>

    <section class="rug-section" aria-label="الهام از هنر قالی ایرانی">
      <div class="container rug-inner">
        <div class="rug-ornament">✺ ❖ ✺</div>
        <div class="section-kicker">ریشه در هنر و فرهنگ ایران</div>
        <h2 class="section-title">از نقش قالی تا نقشِ خاطره</h2>
        <p>رنگ‌های گرم، ترنج‌های خیال‌انگیز و نقش‌های اصیل قالی ایرانی، الهام‌بخش این گوشه از نارون‌اند؛ جایی برای یادآوری زیبایی‌هایی که از گذشته با ما مانده‌اند.</p>
      </div>
    </section>

    <section class="section kindness-section" id="kindness">
      <div class="container kindness-grid">
        <div class="kindness-image">
          <img src="https://images.unsplash.com/photo-1450778869180-41d0601e046e?auto=format&fit=crop&w=1100&q=85" alt="سگی مهربان در انتظار محبت" loading="lazy">
        </div>
        <div class="kindness-copy">
          <div class="section-kicker">مهربانی، بخشی از قرار ماست</div>
          <h2>یک فنجان برای ما،<br>یک قدم برای آن‌ها.</h2>
          <p>دوست داریم نارون فقط جای خوبی برای آدم‌ها نباشد. می‌خواهیم سهم کوچکی در بهتر شدن زندگی حیوانات بی‌پناه داشته باشیم و همراه گروه‌های معتبر حمایت از حیوانات باشیم.</p>
          <ul class="help-list">
            <li><span>✦</span> کمک به تأمین غذا و وسایل ضروری پناهگاه‌های معتبر</li>
            <li><span>✦</span> معرفی حیوانات آماده سرپرستی و فرهنگ‌سازی فرزندخواندگی</li>
            <li><span>✦</span> همراهی در برنامه‌های داوطلبانه و درمان حیوانات آسیب‌دیده</li>
          </ul>
          <a class="btn btn-coffee" href="#ways-to-help">راه‌های مشارکت و حمایت ←</a>
          <p class="support-note">در این نسخه، پرداخت آنلاین فعال نیست؛ کمک‌ها باید فقط از مسیر مجموعه‌های معتبر و قابل‌پیگیری انجام شوند.</p>
        </div>
      </div>
    </section>

    <section class="section visit-section" id="ways-to-help">
      <div class="container">
        <div class="visit-card">
          <div class="section-kicker">مهربانی از همین‌جا شروع می‌شود</div>
          <h2>چطور می‌توانی همراه نارون باشی؟</h2>
          <p>می‌توانی در برنامه‌های داوطلبانه محلی شرکت کنی، وسایل موردنیاز پناهگاه‌های معتبر را اهدا کنی یا با به‌اشتراک‌گذاشتن آگهی سرپرستی، به پیدا شدن یک خانه امن کمک کنی.</p>
          <br>
          <button class="btn btn-coffee" id="copySupport" type="button">کپی متن دعوت به حمایت</button>
          <p class="support-note" id="copyStatus" aria-live="polite">برای حفظ اعتماد، نام و اطلاعات مراکز حمایتی پس از بررسی و تأیید به این بخش اضافه می‌شود.</p>
        </div>
      </div>
    </section>
  </main>

  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div><h3>کافه نارون</h3><p>یک گوشه دنج برای قهوه، کتاب و مهربانی. با الهام از نقش‌های ایرانی و خاطره‌های شیرین گذشته.</p></div>
        <div><h3>دسترسی سریع</h3><div class="footer-links"><a href="#menu">منوی کافه</a><a href="#story">قصه نارون</a><a href="#books">معرفی کتاب</a><a href="#kindness">حمایت از حیوانات</a></div></div>
        <div><h3>شعار نارون</h3><p>آرام‌تر بنوش، عمیق‌تر بخوان، مهربان‌تر زندگی کن.</p><p class="signature">☕ ✦ 📚</p></div>
      </div>
      <div class="copyright">© کافه نارون — ساخته‌شده با عشق به قهوه و فرهنگ ایرانی</div>
    </div>
  </footer>

  <aside class="cart-panel" id="cartPanel" aria-label="سبد سفارش" aria-hidden="true">
    <div class="cart-head"><h3>سبد سفارش شما</h3><button class="close-cart" id="closeCart" type="button" aria-label="بستن">×</button></div>
    <div class="cart-items" id="cartItems"><p class="empty-cart">هنوز چیزی به سبدت اضافه نکردی.</p></div>
    <div class="cart-total"><span>جمع سفارش</span><span id="cartTotal">۰ تومان</span></div>
  </aside>

  <div class="book-modal" id="bookModal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="modal-box"><button class="modal-close" id="modalClose" type="button" aria-label="بستن">×</button><h3 id="modalTitle"></h3><p id="modalText"></p></div>
  </div>
  <div class="toast" id="toast" role="status" aria-live="polite"></div>

  <script>
    const formatPrice = value => new Intl.NumberFormat('fa-IR').format(value) + ' تومان';
    const cart = [];
    const cartPanel = document.getElementById('cartPanel');
    const toast = document.getElementById('toast');
    let toastTimer;

    function showToast(message) {
      toast.textContent = message;
      toast.classList.add('show');
      clearTimeout(toastTimer);
      toastTimer = setTimeout(() => toast.classList.remove('show'), 2200);
    }

    document.querySelectorAll('.filter-btn').forEach(button => {
      button.addEventListener('click', () => {
        document.querySelectorAll('.filter-btn').forEach(item => item.classList.remove('active'));
        button.classList.add('active');
        const filter = button.dataset.filter;
        document.querySelectorAll('.product-card').forEach(card => {
          card.hidden = filter !== 'all' && card.dataset.category !== filter;
        });
      });
    });

    function renderCart() {
      const cartItems = document.getElementById('cartItems');
      const total = cart.reduce((sum, item) => sum + item.price, 0);
      document.getElementById('cartCount').textContent = new Intl.NumberFormat('fa-IR').format(cart.length);
      document.getElementById('cartTotal').textContent = formatPrice(total);
      if (!cart.length) {
        cartItems.innerHTML = '<p class="empty-cart">هنوز چیزی به سبدت اضافه نکردی.</p>';
        return;
      }
      cartItems.innerHTML = '';
      cart.forEach((item, index) => {
        const row = document.createElement('div');
        row.className = 'cart-line';
        const name = document.createElement('span');
        name.textContent = item.name + ' — ' + formatPrice(item.price);
        const remove = document.createElement('button');
        remove.className = 'remove-item';
        remove.type = 'button';
        remove.textContent = 'حذف';
        remove.addEventListener('click', () => { cart.splice(index, 1); renderCart(); });
        row.append(name, remove);
        cartItems.appendChild(row);
      });
    }

    document.querySelectorAll('.add-button').forEach(button => {
      button.addEventListener('click', () => {
        const card = button.closest('.product-card');
        cart.push({ name: card.dataset.name, price: Number(card.dataset.price) });
        renderCart();
        showToast(card.dataset.name + ' به سبد سفارش اضافه شد.');
      });
    });

    function openCart() {
      cartPanel.classList.add('open');
      cartPanel.setAttribute('aria-hidden', 'false');
    }
    function closeCart() {
      cartPanel.classList.remove('open');
      cartPanel.setAttribute('aria-hidden', 'true');
    }
    document.getElementById('openCart').addEventListener('click', openCart);
    document.getElementById('closeCart').addEventListener('click', closeCart);

    const navLinks = document.getElementById('navLinks');
    const menuToggle = document.getElementById('menuToggle');
    menuToggle.addEventListener('click', () => {
      const isOpen = navLinks.classList.toggle('open');
      menuToggle.setAttribute('aria-expanded', String(isOpen));
      menuToggle.textContent = isOpen ? '×' : '☰';
    });
    navLinks.querySelectorAll('a').forEach(link => link.addEventListener('click', () => {
      navLinks.classList.remove('open');
      menuToggle.setAttribute('aria-expanded', 'false');
      menuToggle.textContent = '☰';
    }));

    const books = {
      symphony: ['سمفونی مردگان — عباس معروفی', 'رمانی تأثیرگذار درباره خانواده، سکوت، حسادت و سرنوشت. نثر و ساختار چندصدایی کتاب، آن را به یکی از آثار شناخته‌شده ادبیات معاصر ایران تبدیل کرده است.'],
      eyes: ['چشم‌هایش — بزرگ علوی', 'داستانی رازآلود که از دل یک پرتره آغاز می‌شود و به عشق، هنر و فضای سیاسی و اجتماعی یک دوره می‌رسد.'],
      prince: ['شازده کوچولو — آنتوان دو سنت‌اگزوپری', 'داستانی کوتاه و نمادین درباره دوستی، مسئولیت و چیزهایی که شاید فقط با دل دیده شوند.']
    };
    const modal = document.getElementById('bookModal');
    document.querySelectorAll('.book-more').forEach(button => button.addEventListener('click', () => {
      const book = books[button.dataset.book];
      document.getElementById('modalTitle').textContent = book[0];
      document.getElementById('modalText').textContent = book[1];
      modal.classList.add('show');
    }));
    function closeModal() { modal.classList.remove('show'); }
    document.getElementById('modalClose').addEventListener('click', closeModal);
    modal.addEventListener('click', event => { if (event.target === modal) closeModal(); });
    document.addEventListener('keydown', event => { if (event.key === 'Escape') { closeModal(); closeCart(); } });

    document.getElementById('copySupport').addEventListener('click', async () => {
      const message = 'بیایید کنار هم به حیوانات بی‌پناه کمک کنیم؛ با حمایت از پناهگاه‌های معتبر، تهیه غذای موردنیاز و معرفی حیوانات آماده سرپرستی. مهربانی را دست‌به‌دست کنیم. — کافه نارون';
      try {
        await navigator.clipboard.writeText(message);
        document.getElementById('copyStatus').textContent = 'متن دعوت با موفقیت کپی شد؛ ممنون از همراهی‌ات.';
      } catch (error) {
        document.getElementById('copyStatus').textContent = message;
      }
    });
    renderCart();
  </script>
</body>
</html>
