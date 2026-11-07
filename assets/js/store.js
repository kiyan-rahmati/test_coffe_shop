/* لایه‌ی ذخیره‌سازی: اگر بک‌اند PHP در دسترس باشد از آن، وگرنه localStorage (مناسب GitHub Pages) */
window.Store = (function () {
  const N = window.NAROON, C = N.config;
  let mode = 'local';
  const LS = {
    get(k, d) { try { const v = JSON.parse(localStorage.getItem('naroon:' + k)); return v == null ? d : v; } catch (e) { return d; } },
    set(k, v) { try { localStorage.setItem('naroon:' + k, JSON.stringify(v)); } catch (e) {} },
    del(k) { try { localStorage.removeItem('naroon:' + k); } catch (e) {} }
  };
  const SS = {
    get(k) { try { return JSON.parse(sessionStorage.getItem('naroon:' + k)); } catch (e) { return null; } },
    set(k, v) { try { sessionStorage.setItem('naroon:' + k, JSON.stringify(v)); } catch (e) {} },
    del(k) { try { sessionStorage.removeItem('naroon:' + k); } catch (e) {} }
  };
  async function api(action, body) {
    const opt = { credentials: 'same-origin', headers: { 'Content-Type': 'application/json' } };
    if (body !== undefined) { opt.method = 'POST'; opt.body = JSON.stringify(body); }
    const r = await fetch(C.apiBase + '?action=' + action, opt);
    const j = await r.json();
    if (!r.ok || j.error) throw new Error(j.error || 'خطای سرور');
    return j;
  }
  async function detect() {
    try {
      const r = await fetch(C.apiBase + '?action=ping', { cache: 'no-store' });
      const j = await r.json();
      if (j && j.ok) mode = 'php';
    } catch (e) { mode = 'local'; }
    return mode;
  }
  const hash = s => { let h = 5381; for (let i = 0; i < s.length; i++) h = ((h << 5) + h + s.charCodeAt(i)) | 0; return String(h); };
  const uid = () => Date.now().toString(36) + Math.random().toString(36).slice(2, 6);

  const S = {
    detect, get mode() { return mode; },
    /* منو */
    async getMenu() {
      if (mode === 'php') { const j = await api('menu'); if (j.menu) return j.menu; return N.menu; }
      return LS.get('menu', N.menu);
    },
    async saveMenu(menu) { if (mode === 'php') return api('menu_save', { menu }); LS.set('menu', menu); return { ok: true }; },
    async resetMenu() { if (mode === 'php') return api('menu_save', { menu: N.menu }); LS.del('menu'); return { ok: true }; },
    /* کاربر */
    async register(u) {
      if (mode === 'php') return api('register', u);
      const users = LS.get('users', {});
      if (users[u.username]) throw new Error('این نام کاربری قبلاً ثبت شده است');
      users[u.username] = { username: u.username, name: u.name, job: u.job, pass: hash(u.password), prefs: u.prefs || {}, created: Date.now() };
      LS.set('users', users); SS.set('user', u.username);
      return { user: pub(users[u.username]) };
    },
    async login(username, password) {
      if (mode === 'php') return api('login', { username, password });
      const us = LS.get('users', {})[username];
      if (!us || us.pass !== hash(password)) throw new Error('نام کاربری یا رمز عبور اشتباه است');
      SS.set('user', username); return { user: pub(us) };
    },
    async logout() { if (mode === 'php') { try { await api('logout', {}); } catch (e) {} } SS.del('user'); },
    async me() {
      if (mode === 'php') { try { return (await api('me')).user || null; } catch (e) { return null; } }
      const n = SS.get('user'); if (!n) return null; const u = LS.get('users', {})[n]; return u ? pub(u) : null;
    },
    async savePrefs(prefs, extra) {
      if (mode === 'php') return api('prefs', { prefs, ...(extra || {}) });
      const users = LS.get('users', {}), n = SS.get('user'); if (!users[n]) return;
      users[n].prefs = Object.assign(users[n].prefs || {}, prefs);
      if (extra) { if (extra.name) users[n].name = extra.name; if (extra.job) users[n].job = extra.job; }
      LS.set('users', users); return { user: pub(users[n]) };
    },
    /* سفارش */
    async createOrder(o) {
      if (mode === 'php') return api('order', o);
      const orders = LS.get('orders', []), n = SS.get('user');
      const ord = { id: 'N' + String(orders.length + 1001), user: n, items: o.items, total: o.total, type: o.type, note: o.note, address: o.address, status: 'new', created: Date.now() };
      orders.push(ord); LS.set('orders', orders); return { order: ord };
    },
    async myOrders() {
      if (mode === 'php') return (await api('orders')).orders;
      const n = SS.get('user'); return LS.get('orders', []).filter(o => o.user === n).reverse();
    },
    /* مدیریت */
    async adminLogin(user, pass) {
      if (mode === 'php') return api('admin_login', { user, pass });
      if (user === C.admin.user && pass === C.admin.pass) { SS.set('admin', true); return { ok: true }; }
      throw new Error('نام کاربری یا رمز ادمین اشتباه است');
    },
    async adminLogout() { if (mode === 'php') { try { await api('admin_logout', {}); } catch (e) {} } SS.del('admin'); },
    async isAdmin() { if (mode === 'php') { try { return !!(await api('admin_me')).admin; } catch (e) { return false; } } return !!SS.get('admin'); },
    async allOrders() { if (mode === 'php') return (await api('admin_orders')).orders; return LS.get('orders', []).slice().reverse(); },
    async setOrderStatus(id, status) {
      if (mode === 'php') return api('admin_order_status', { id, status });
      const orders = LS.get('orders', []); const o = orders.find(x => x.id === id); if (o) o.status = status; LS.set('orders', orders); return { ok: true };
    },
    /* ترجیحات مهمان (قبل از ورود) */
    guest: { get: () => LS.get('guest', {}), set: v => LS.set('guest', v) },
    uid
  };
  function pub(u) { return { username: u.username, name: u.name, job: u.job, prefs: u.prefs || {} }; }
  return S;
})();
