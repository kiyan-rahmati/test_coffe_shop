/* پنل ادمین: ویرایش قیمت و منو + مدیریت وضعیت سفارش‌ها. ورود پیش‌فرض: admin / admin */
(function () {
  const N = window.NAROON, $ = (s, r) => (r || document).querySelector(s), app = $('#app');
  const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
  const ST = { new: 'جدید', preparing: 'در حال آماده‌سازی', ready: 'آماده', delivered: 'تحویل شد', canceled: 'لغو شد' };
  const fixImg = p => (/^(https?:|data:|\.\.\/)/.test(p) ? p : '../' + p);
  const stripImg = p => p.replace(/^\.\.\//, '');
  let menu = [], tab = 'prices';
  function toast(m) { const t = document.createElement('div'); t.className = 'toast'; t.textContent = m; document.body.appendChild(t); setTimeout(() => t.remove(), 2600); }

  function loginView() {
    app.innerHTML = `<div class="login panel"><h1>پنل مدیریت</h1><p class="sub" style="color:var(--muted);margin-bottom:14px">ورود ادمین کافه نارون</p>
      <div class="field"><label for="u">نام کاربری</label><input id="u" autocomplete="username"></div>
      <div class="field"><label for="p">رمز عبور</label><input id="p" type="password" autocomplete="current-password"></div>
      <p id="err" style="color:#e74c3c;min-height:1.6em"></p><button class="btn" id="go" style="width:100%;justify-content:center">ورود</button>
      <p style="margin-top:12px"><a href="../index.html">← بازگشت به سایت</a></p></div>`;
    const go = async () => { try { await Store.adminLogin($('#u').value.trim(), $('#p').value); start(); } catch (e) { $('#err').textContent = e.message; } };
    $('#go').onclick = go; $('#p').onkeydown = e => { if (e.key === 'Enter') go(); };
  }

  async function start() {
    menu = await Store.getMenu(); menu = JSON.parse(JSON.stringify(menu)); view();
  }

  function view() {
    app.innerHTML = `<div class="adm"><div class="bar"><h1>پنل مدیریت کافه نارون</h1><div style="display:flex;gap:8px"><a class="btn ghost small" href="../index.html">مشاهده سایت</a><button class="btn ghost small" id="out">خروج</button></div></div>
      <div class="note">${Store.mode === 'php' ? 'نسخه PHP: تغییرات روی سرور (backend/data) ذخیره می‌شود و برای همه‌ی بازدیدکننده‌ها اعمال می‌شود.' : 'نسخه استاتیک (GitHub Pages): تغییرات فقط در همین مرورگر ذخیره می‌شود. برای ذخیره‌ی واقعی و مشترک، نسخه PHP را اجرا کنید.'}</div>
      <div class="tabs" style="margin-bottom:16px"><button class="tab ${tab === 'prices' ? 'on' : ''}" data-tab="prices">منو و قیمت‌ها</button><button class="tab ${tab === 'orders' ? 'on' : ''}" data-tab="orders">سفارش‌ها</button></div>
      <div id="body"></div></div>`;
    app.onclick = async e => {
      const t = e.target.closest('[data-tab]'); if (t) { tab = t.dataset.tab; return view(); }
      if (e.target.id === 'out') { await Store.adminLogout(); loginView(); }
    };
    tab === 'prices' ? prices() : orders();
  }

  function prices() {
    const b = $('#body');
    b.innerHTML = `<div class="bar"><div style="display:flex;gap:8px;flex-wrap:wrap"><button class="btn" id="save">ذخیره تغییرات</button><button class="btn ghost" id="add">+ آیتم جدید</button><button class="btn ghost" id="exp">خروجی JSON</button><button class="btn danger" id="reset">بازگشت به پیش‌فرض</button></div></div>
      <div class="scroll"><table class="t"><thead><tr><th></th><th>نام</th><th>دسته</th><th>قیمت (تومان)</th><th>توضیح</th><th>فعال</th><th></th></tr></thead><tbody>
      ${menu.map((m, i) => `<tr data-i="${i}"><td><img src="${esc(fixImg(m.img))}" alt=""></td><td><input type="text" data-f="name" value="${esc(m.name)}"></td>
        <td><select data-f="cat">${N.categories.map(c => `<option value="${c.id}" ${c.id === m.cat ? 'selected' : ''}>${c.name}</option>`).join('')}</select></td>
        <td><input type="number" min="0" step="1000" data-f="price" value="${Number(m.price)}"></td><td><input type="text" data-f="desc" value="${esc(m.desc)}"></td>
        <td><input type="checkbox" data-f="on" ${m.on !== false ? 'checked' : ''}></td><td><button class="btn danger small" data-del="${i}">حذف</button></td></tr>`).join('')}
      </tbody></table></div>`;
    b.oninput = e => {
      const f = e.target.dataset.f; if (!f) return; const i = e.target.closest('tr').dataset.i;
      menu[i][f] = f === 'price' ? Number(e.target.value) : f === 'on' ? e.target.checked : e.target.value;
    };
    b.onchange = b.oninput;
    b.onclick = async e => {
      const d = e.target.closest('[data-del]'); if (d) { if (confirm('این آیتم حذف شود؟')) { menu.splice(d.dataset.del, 1); prices(); } return; }
      if (e.target.id === 'add') { menu.push({ id: 'item' + Date.now().toString(36), cat: 'hot', name: 'آیتم جدید', desc: '', price: 100000, img: 'assets/img/products/espresso.svg', on: true }); prices(); }
      if (e.target.id === 'save') { try { await Store.saveMenu(menu.map(m => ({ ...m, img: stripImg(m.img) }))); toast('ذخیره شد ✓'); } catch (er) { toast(er.message); } }
      if (e.target.id === 'reset') { if (confirm('همه‌ی تغییرات پاک و منوی پیش‌فرض برگردانده شود؟')) { await Store.resetMenu(); start(); toast('بازنشانی شد'); } }
      if (e.target.id === 'exp') { const a = document.createElement('a'); a.href = URL.createObjectURL(new Blob([JSON.stringify(menu, null, 2)], { type: 'application/json' })); a.download = 'menu.json'; a.click(); }
    };
  }

  async function orders() {
    const b = $('#body'); b.innerHTML = '<p>در حال بارگذاری…</p>';
    let os = []; try { os = await Store.allOrders(); } catch (e) { b.innerHTML = '<p>' + esc(e.message) + '</p>'; return; }
    b.innerHTML = os.length ? `<div class="scroll"><table class="t"><thead><tr><th>شماره</th><th>کاربر</th><th>اقلام</th><th>مبلغ</th><th>نوع</th><th>وضعیت</th></tr></thead><tbody>
      ${os.map(o => `<tr><td>${esc(o.id)}</td><td>${esc(o.user)}</td><td>${o.items.map(i => esc(i.name) + ' ×' + i.q).join('، ')}${o.note ? '<br><small>' + esc(o.note) + '</small>' : ''}</td>
      <td>${Number(o.total).toLocaleString('fa-IR')}</td><td>${{ dine: 'حضوری', pickup: 'بیرون‌بر', delivery: 'ارسال' }[o.type] || ''}${o.address ? '<br><small>' + esc(o.address) + '</small>' : ''}</td>
      <td><select data-id="${esc(o.id)}">${Object.keys(ST).map(k => `<option value="${k}" ${k === o.status ? 'selected' : ''}>${ST[k]}</option>`).join('')}</select></td></tr>`).join('')}</tbody></table></div>`
      : '<p style="color:var(--muted)">هنوز سفارشی ثبت نشده است.</p>';
    b.onchange = async e => { if (e.target.dataset.id) { try { await Store.setOrderStatus(e.target.dataset.id, e.target.value); toast('وضعیت به‌روز شد'); } catch (er) { toast(er.message); } } };
  }

  (async function () { await Store.detect(); (await Store.isAdmin()) ? start() : loginView(); })();
})();
