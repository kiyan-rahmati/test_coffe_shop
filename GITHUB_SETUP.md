# راهنمای تنظیم گیت‌هاب (متن‌ها، فیلدها و قوانین)

## About (سمت راست صفحه‌ی ریپو ← ⚙️)
- **Description:**
  `کافه نارون رشت؛ سایت فروشگاهی قهوه با ۵ تم، سفارش آنلاین، داشبورد و پنل ادمین | Multi-theme coffee shop storefront (pure HTML/CSS/JS + optional PHP)`
- **Website:** `https://kiyan-rahmati.github.io/naroon_coffe/`
- **Topics:** `coffee-shop` `cafe` `persian` `rtl` `farsi` `html` `css` `javascript` `php` `ajax` `ecommerce` `storefront` `themes` `cyberpunk` `github-pages` `portfolio` `rasht` `iran`
- تیک بزنید: ✅ Releases · ❌ Packages · ❌ Deployments (اختیاری)

## Settings → General
- Features: ✅ Issues · ✅ Discussions (اختیاری) · ❌ Wikis · ❌ Projects (اگر لازم ندارید)
- Pull Requests: ✅ Allow squash merging · ✅ Automatically delete head branches

## Settings → Pages
- **Source:** GitHub Actions (فایل `.github/workflows/pages.yml` آماده است و پوشه‌ی backend را از نسخه‌ی آنلاین حذف می‌کند)
  یا ساده‌تر: Deploy from a branch ← `main` / `(root)`

## قوانین شاخه‌ها (Settings → Rules → Rulesets → New branch ruleset)
- Name: `protect-main` · Target: `main`
- ✅ Restrict deletions
- ✅ Block force pushes
- ✅ Require a pull request before merging (۱ تأییدیه، اگر تنها هستید می‌توانید ۰ بگذارید)

## Settings → Code security
- ✅ Private vulnerability reporting
- ✅ Dependabot alerts

## اولین کامیت و ریلیز
```bash
git add .
git commit -m "feat: Naroon Cafe v1.0 - 5 themes, admin, PHP backend"
git push origin main
git tag v1.0.0 && git push origin v1.0.0
```
Release title: `v1.0.0 — نسخه اول` | توضیح: فهرست امکانات از README.

## متن پیشنهادی برای LinkedIn / رزومه
«کافه نارون: سایت فروشگاهی ۵‌تمه با JavaScript خالص، پنل ادمین، داشبورد کاربر، سبد خرید و بک‌اند اختیاری PHP/AJAX (بدون دیتابیس). نسخه‌ی زنده + سورس در GitHub.»

## یادآوری
- لایسنس: MIT (فایل `LICENSE`) — GitHub آن را خودکار تشخیص می‌دهد.
- قبل از انتشار روی سرور واقعی رمز ادمین را عوض کنید.
