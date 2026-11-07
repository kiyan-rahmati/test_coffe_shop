/* منطق اصلی سایت: تم‌ها، خوش‌آمدگویی بر اساس شغل، منو، سبد، ورود و داشبورد */
(function () {
  const N = window.NAROON, T = N.themes, $ = (s, r) => (r || document).querySelector(s);
  const fmt = n => Number(n).toLocaleString('fa-IR') + ' تومان';
  const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
  const ACCENTS = ['', '#e0a340', '#ff2d6f', '#00c2a8', '#7c4dff', '#2f3cff', '#e8590c'];
  const ST = { new: 'جدید', preparing: 'در حال آماده‌سازی', ready: 'آماده', delivered: 'تحویل شد', canceled: 'لغو شد' };
  const S = { theme: 'cafe', job: '', name: '', user: null, cart: [], menu: [], cat: 'all', q: '', prefs: {} };

  function toast(msg) { const t = document.createElement('div'); t.className = 'toast'; t.textContent = msg; document.body.appendChild(t); setTimeout(() => t.remove(), 3200); }

  /* ---------- تم و متن‌ها ---------- */
  function greetText() {
    const j = N.jobs.find(x => x[0] === S.job); if (!j) return '';
    const nm = S.name ? ' ' + S.name : '';
    switch (S.theme) {
      case 'ghahve': return `خوش اومدی ${j[3]}${j[0] === 'other' ? '' : ' جان'}! قدمت روی چشم، بشین قهوه‌ت رو بیارن.`;
      case 'cafe': return `${j[2]}${nm} گرامی، به کافه نارون خوش آمدید.`;
      case 'snap': return `سلام ${j[2]}${nm}! سفارشت رو ثبت کن، سریع می‌رسه.`;
      case 'cyber': return `> ACCESS GRANTED // خوش اومدی ${j[2]}${nm}`;
      default: return `${j[2]}${nm}، خوش اومدی! امروز چه رنگی قهوه می‌خوری؟`;
    }
  }
  function applyTheme(k, opt) {
    opt = opt || {}; if (!T[k]) k = 'cafe'; S.theme = k;
    document.body.dataset.theme = k;
    const t = T[k];
    document.querySelectorAll('[data-t]').forEach(el => { const v = t.t[el.dataset.t]; if (v != null) el.textContent = v; });
    $('#heroImg').src = t.hero;
    $('#drawer .btn#checkout').textContent = t.t.checkout;
    FX.set(t.fx); if (k === 'ghahve' && opt.burst) FX.burst();
    Music.load(k);
    applyLook(); renderMenu(); renderCart(); updateGreet();
  }
  function applyLook() {
    const p = S.prefs, b = document.body;
    if (p.accent) b.style.setProperty('--accent', p.accent); else b.style.removeProperty('--accent');
    b.style.setProperty('--fs', p.fs || 1);
  }
  function updateGreet() { const g = $('#greet'), x = greetText(); g.classList.toggle('hidden', !x); g.textContent = x; }
  function setMapping() {
    const q = encodeURIComponent(N.config.mapQuery);
    $('#mapFrame').src = 'https://www.google.com/maps?q=' + q + '&hl=fa&output=embed';
    $('#mapLink').href = 'https://www.google.com/maps/search/?api=1&query=' + q;
  }

  /* ---------- منو ---------- */
  function renderMenu() {
    const tabs = [{ id: 'all', name: 'همه' }, ...N.categories];
    $('#tabs').innerHTML = tabs.map(c => `<button class="tab ${S.cat === c.id ? 'on' : ''}" data-c="${c.id}" role="tab">${c.name}</button>`).join('');
    const q = S.q.trim();
    const list = S.menu.filter(m => m.on !== false && (S.cat === 'all' || m.cat === S.cat) && (!q || (m.name + m.desc).includes(q)));
    const add = T[S.theme].t.add;
    $('#grid').innerHTML = list.length ? list.map(m => `<article class="card"><div class="pic"><img src="${esc(m.img)}" alt="${esc(m.name)}" loading="lazy">${m.tag ? `<span class="tagb">${esc(m.tag)}</span>` : ''}</div>
      <div class="body"><h3>${esc(m.name)}</h3><p>${esc(m.desc)}</p><div class="row"><span class="price">${fmt(m.price)}</span><button class="btn small" data-add="${m.id}">${add}</button></div></div></article>`).join('')
      : '<p style="color:var(--muted)">موردی پیدا نشد.</p>';
  }
  $('#tabs').onclick = e => { const c = e.target.closest('[data-c]'); if (c) { S.cat = c.dataset.c; renderMenu(); } };
  $('#q').oninput = e => { S.q = e.target.value; renderMenu(); };
  $('#grid').onclick = e => { const b = e.target.closest('[data-add]'); if (b) addToCart(b.dataset.add); };

  /* ---------- سبد ---------- */
  function loadCart() { try { S.cart = JSON.parse(localStorage.getItem('naroon:cart')) || []; } catch (e) { S.cart = []; } }
  function saveCart() { localStorage.setItem('naroon:cart', JSON.stringify(S.cart)); }
  function addToCart(id) { const c = S.cart.find(x => x.id === id); if (c) c.q++; else S.cart.push({ id, q: 1 }); saveCart(); renderCart(); toast('به سبد اضافه شد'); }
  function cartLines() { return S.cart.map(c => { const m = S.menu.find(x => x.id === c.id); return m ? { ...m, q: c.q } : null; }).filter(Boolean); }
  function renderCart() {
    const L = cartLines(), n = L.reduce((a, b) => a + b.q, 0), tot = L.reduce((a, b) => a + b.q * b.price, 0);
    const bd = $('#cartN'); bd.textContent = n; bd.classList.toggle('hidden', !n);
    $('#cartList').innerHTML = L.length ? L.map(m => `<div class="ci"><img src="${esc(m.img)}" alt=""><div><b>${esc(m.name)}</b><div class="price">${fmt(m.price * m.q)}</div></div>
      <div class="qty"><button data-d="1" data-id="${m.id}" aria-label="افزودن">+</button><span>${m.q.toLocaleString('fa-IR')}</span><button data-d="-1" data-id="${m.id}" aria-label="کم کردن">−</button></div></div>`).join('')
      : `<p style="color:var(--muted)">${T[S.theme].t.empty}</p>`;
    $('#cartTotal').textContent = fmt(tot);
  }
  $('#cartList').onclick = e => {
    const b = e.target.closest('[data-d]'); if (!b) return;
    const c = S.cart.find(x => x.id === b.dataset.id); c.q += Number(b.dataset.d);
    if (c.q <= 0) S.cart = S.cart.filter(x => x !== c); saveCart(); renderCart();
  };
  $('#cartBtn').onclick = () => $('#drawer').classList.add('open');
  $('#closeCart').onclick = () => $('#drawer').classList.remove('open');
  $('#otype').onchange = e => $('#addrF').classList.toggle('hidden', e.target.value !== 'delivery');
  $('#checkout').onclick = async () => {
    const L = cartLines(); if (!L.length) return toast(T[S.theme].t.empty);
    if (!S.user) { toast('برای ثبت سفارش اول وارد شوید'); return openAuth(); }
    const type = $('#otype').value, address = $('#oaddr').value.trim();
    if (type === 'delivery' && !address) return toast('آدرس را وارد کنید');
    try {
      await Store.createOrder({ items: L.map(m => ({ id: m.id, name: m.name, price: m.price, q: m.q })), total: L.reduce((a, b) => a + b.q * b.price, 0), type, note: $('#onote').value.trim(), address });
      S.cart = []; saveCart(); renderCart(); $('#onote').value = ''; $('#drawer').classList.remove('open');
      toast(T[S.theme].t.ordered);
    } catch (e) { toast(e.message); }
  };

  /* ---------- مودال‌ها ---------- */
  function modal(html, closable) {
    const r = $('#modalRoot'); r.innerHTML = `<div class="overlay"><div class="modal" role="dialog" aria-modal="true">${html}</div></div>`;
    if (closable !== false) r.firstChild.onclick = e => { if (e.target === r.firstChild) closeModal(); };
    return r.firstChild;
  }
  function closeModal() { $('#modalRoot').innerHTML = ''; }
  function themePicker(sel) {
    return `<div class="themepick">${Object.keys(T).map(k => `<div class="tp ${k === sel ? 'on' : ''}" data-theme-pick="${k}" tabindex="0" role="button"><div class="ic">${T[k].icon}</div><b>${T[k].label}</b><small>${T[k].desc}</small></div>`).join('')}</div>`;
  }
  function jobOptions(sel) { return N.jobs.map(j => `<option value="${j[0]}" ${j[0] === sel ? 'selected' : ''}>${j[1]}</option>`).join(''); }

  function openWelcome() {
    let pick = S.theme;
    const m = modal(`<h3>به کافه نارون خوش آمدید ☕</h3><p class="sub">چند ثانیه وقت بذارید تا سایت را برای خودتان بسازیم.</p>
      <div class="row2"><div class="field"><label for="wn">اسم شما</label><input id="wn" placeholder="مثلاً علی"></div>
      <div class="field"><label for="wj">شغل شما چیست؟</label><select id="wj">${jobOptions('')}</select></div></div>
      <label style="color:var(--muted);font-size:.9rem">یک تم انتخاب کنید (بعداً از داشبورد هم عوض می‌شود)</label>${themePicker(pick)}
      <button class="btn" id="wgo" style="width:100%;justify-content:center">ورود به کافه</button>`, false);
    m.querySelector('#wj').selectedIndex = 0;
    m.onclick = e => { const t = e.target.closest('[data-theme-pick]'); if (!t) return; pick = t.dataset.themePick; m.querySelectorAll('.tp').forEach(x => x.classList.toggle('on', x === t)); document.body.dataset.theme = pick; };
    m.querySelector('#wgo').onclick = () => {
      S.name = m.querySelector('#wn').value.trim(); S.job = m.querySelector('#wj').value;
      Store.guest.set({ name: S.name, job: S.job, theme: pick, done: 1 });
      closeModal(); applyTheme(pick, { burst: true }); Music.play();
      if (pick === 'ghahve') toast('خوش اومدی مشتی 🫖');
    };
  }
  function openAuth(tab) {
    tab = tab || 'login';
    const m = modal(`<h3>${tab === 'login' ? 'ورود به حساب' : 'ساخت حساب'}</h3><p class="sub">سفارش‌های شما در داشبورد ذخیره می‌شود.</p>
      <div class="tabs" style="margin-bottom:14px"><button class="tab ${tab === 'login' ? 'on' : ''}" data-tab="login">ورود</button><button class="tab ${tab === 'reg' ? 'on' : ''}" data-tab="reg">ثبت‌نام</button></div>
      ${tab === 'reg' ? `<div class="field"><label for="an">نام</label><input id="an" value="${esc(S.name)}"></div><div class="field"><label for="aj">شغل</label><select id="aj">${jobOptions(S.job)}</select></div>` : ''}
      <div class="field"><label for="au">نام کاربری</label><input id="au" autocomplete="username"></div>
      <div class="field"><label for="ap">رمز عبور</label><input id="ap" type="password" autocomplete="${tab === 'login' ? 'current-password' : 'new-password'}"></div>
      <p id="aerr" style="color:#e74c3c;min-height:1.6em"></p>
      <button class="btn" id="ago" style="width:100%;justify-content:center">${tab === 'login' ? 'ورود' : 'ساخت حساب'}</button>`);
    m.onclick = e => { const t = e.target.closest('[data-tab]'); if (t) openAuth(t.dataset.tab); else if (e.target === m) closeModal(); };
    const go = async () => {
      const u = m.querySelector('#au').value.trim(), p = m.querySelector('#ap').value;
      if (u.length < 3 || p.length < 4) { m.querySelector('#aerr').textContent = 'نام کاربری حداقل ۳ و رمز حداقل ۴ کاراکتر باشد'; return; }
      try {
        const r = tab === 'login' ? await Store.login(u, p)
          : await Store.register({ username: u, password: p, name: m.querySelector('#an').value.trim(), job: m.querySelector('#aj').value, prefs: { theme: S.theme } });
        setUser(r.user); closeModal(); toast('خوش آمدید ' + (r.user.name || r.user.username));
      } catch (e) { m.querySelector('#aerr').textContent = e.message; }
    };
    m.querySelector('#ago').onclick = go; m.querySelector('#ap').onkeydown = e => { if (e.key === 'Enter') go(); };
  }
  function setUser(u) {
    S.user = u; const b = $('#userBtn');
    if (u) {
      b.textContent = '👤 ' + (u.name || u.username);
      if (u.job) S.job = u.job; if (u.name) S.name = u.name;
      S.prefs = Object.assign({}, S.prefs, u.prefs || {});
      applyTheme(S.prefs.theme || S.theme);
    } else b.textContent = 'ورود';
  }
  $('#userBtn').onclick = () => (S.user ? openDash() : openAuth());
  $('#themeBtn').onclick = () => {
    const m = modal(`<h3>تغییر تم</h3>${themePicker(S.theme)}`);
    m.onclick = e => { const t = e.target.closest('[data-theme-pick]'); if (t) { choose(t.dataset.themePick); closeModal(); } else if (e.target === m) closeModal(); };
  };
  async function choose(k) {
    S.prefs.theme = k; applyTheme(k, { burst: true });
    if (S.user) Store.savePrefs({ theme: k }); else Store.guest.set(Object.assign(Store.guest.get(), { theme: k }));
    if (k === 'ghahve') toast('خوش اومدی مشتی 🫖');
  }

  /* ---------- داشبورد ---------- */
  function openDash(tab) {
    tab = tab || 'orders'; const r = $('#dashRoot');
    r.innerHTML = `<div class="dash"><div class="wrap">
      <div style="display:flex;justify-content:space-between;align-items:center;gap:10px"><h2 style="font-family:var(--font-head)">داشبورد ${esc(S.user.name || S.user.username)}</h2>
      <div style="display:flex;gap:8px"><button class="btn ghost small" id="dLogout">خروج</button><button class="btn small" id="dClose">بازگشت به کافه</button></div></div>
      <div class="dtabs"><button class="tab ${tab === 'orders' ? 'on' : ''}" data-dt="orders">سفارش‌های من</button><button class="tab ${tab === 'look' ? 'on' : ''}" data-dt="look">ظاهر و تم</button><button class="tab ${tab === 'profile' ? 'on' : ''}" data-dt="profile">پروفایل</button></div>
      <div id="dBody"></div></div></div>`;
    r.onclick = async e => {
      const t = e.target.closest('[data-dt]'); if (t) return openDash(t.dataset.dt);
      if (e.target.id === 'dClose') r.innerHTML = '';
      if (e.target.id === 'dLogout') { await Store.logout(); S.user = null; S.prefs = {}; r.innerHTML = ''; $('#userBtn').textContent = 'ورود'; applyLook(); toast('خارج شدید'); }
      const p = e.target.closest('[data-theme-pick]'); if (p) { await choose(p.dataset.themePick); openDash('look'); }
      const sw = e.target.closest('[data-acc]'); if (sw) { S.prefs.accent = sw.dataset.acc; applyLook(); Store.savePrefs({ accent: S.prefs.accent }); openDash('look'); }
    };
    const body = $('#dBody');
    if (tab === 'orders') {
      body.innerHTML = '<p style="color:var(--muted)">در حال بارگذاری…</p>';
      Store.myOrders().then(os => {
        body.innerHTML = os.length ? os.map(o => `<div class="order"><div class="oh"><b>سفارش ${esc(o.id)}</b><span class="st ${esc(o.status)}">${ST[o.status] || o.status}</span></div>
          <div>${o.items.map(i => `${esc(i.name)} × ${Number(i.q).toLocaleString('fa-IR')}`).join('، ')}</div>
          <div style="color:var(--muted);font-size:.9rem">${new Date(o.created).toLocaleString('fa-IR')} · ${{ dine: 'حضوری', pickup: 'بیرون‌بر', delivery: 'ارسال' }[o.type] || ''}${o.address ? ' · ' + esc(o.address) : ''}</div>
          <div class="price">${fmt(o.total)}</div></div>`).join('')
          : '<p style="color:var(--muted)">هنوز سفارشی ثبت نکرده‌اید. از منو شروع کنید ☕</p>';
      });
    } else if (tab === 'look') {
      body.innerHTML = `<div class="panel"><h3 style="font-family:var(--font-head)">تم داشبورد و سایت</h3>${themePicker(S.theme)}
        <h3 style="font-family:var(--font-head);margin-top:10px">رنگ اصلی</h3><div class="swatches">${ACCENTS.map(c => `<button class="sw ${(S.prefs.accent || '') === c ? 'on' : ''}" data-acc="${c}" style="background:${c || 'var(--surface2)'}" aria-label="رنگ ${c || 'پیش‌فرض'}">${c ? '' : '↺'}</button>`).join('')}</div>
        <div class="field" style="margin-top:14px"><label for="fs">اندازه‌ی نوشته‌ها</label><input id="fs" type="range" min="0.9" max="1.3" step="0.05" value="${S.prefs.fs || 1}"></div></div>`;
      $('#fs').oninput = e => { S.prefs.fs = Number(e.target.value); applyLook(); };
      $('#fs').onchange = () => Store.savePrefs({ fs: S.prefs.fs });
    } else {
      body.innerHTML = `<div class="panel"><div class="row2"><div class="field"><label for="pn">نام</label><input id="pn" value="${esc(S.user.name)}"></div>
        <div class="field"><label for="pj">شغل</label><select id="pj">${jobOptions(S.user.job)}</select></div></div><button class="btn" id="psave">ذخیره</button></div>`;
      $('#psave').onclick = async () => {
        const name = $('#pn').value.trim(), job = $('#pj').value; await Store.savePrefs({}, { name, job });
        S.user.name = name; S.user.job = job; S.name = name; S.job = job; $('#userBtn').textContent = '👤 ' + (name || S.user.username); updateGreet(); toast('ذخیره شد');
      };
    }
  }

  /* ---------- شروع ---------- */
  async function init() {
    loadCart(); setMapping();
    const mode = await Store.detect();
    $('#modeLbl').textContent = mode === 'php' ? 'نسخه‌ی PHP (ذخیره روی سرور)' : 'نسخه‌ی استاتیک (ذخیره در مرورگر)';
    S.menu = await Store.getMenu();
    const g = Store.guest.get(); S.name = g.name || ''; S.job = g.job || ''; S.prefs = { theme: g.theme || 'cafe' };
    const u = await Store.me(); applyTheme(S.prefs.theme);
    if (u) setUser(u); else if (!g.done) openWelcome();
    addEventListener('keydown', e => { if (e.key === 'Escape') { closeModal(); $('#drawer').classList.remove('open'); } });
  }
  init();
})();
