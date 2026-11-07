/* افکت‌های پس‌زمینه: دود قهوه‌خانه (قلیان) و باران ماتریسی سایبرپانک */
window.FX = (function () {
  const cv = document.getElementById('fx'), ctx = cv.getContext('2d');
  let mode = 'none', parts = [], cols = [], boost = 0, raf = 0, last = 0;
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  function size() { cv.width = innerWidth; cv.height = innerHeight; cols = Array.from({ length: Math.ceil(innerWidth / 18) }, () => Math.random() * -50); }
  addEventListener('resize', size); size();
  function spawn(n) {
    for (let i = 0; i < n; i++) parts.push({ x: Math.random() * cv.width, y: cv.height + 40, r: 40 + Math.random() * 90, vy: .35 + Math.random() * .9, vx: (Math.random() - .5) * .5, a: .0, life: 0, max: 380 + Math.random() * 280, ph: Math.random() * 6 });
  }
  function smoke() {
    ctx.clearRect(0, 0, cv.width, cv.height);
    if (Math.random() < .12 + boost * .5) spawn(1 + (boost > 0 ? 2 : 0));
    parts = parts.filter(p => p.life < p.max);
    for (const p of parts) {
      p.life++; p.y -= p.vy; p.x += p.vx + Math.sin(p.life / 40 + p.ph) * .6; p.r += .22;
      const k = p.life / p.max, a = Math.sin(Math.PI * k) * (.1 + boost * .1);
      const g = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.r);
      g.addColorStop(0, `rgba(230,225,215,${a})`); g.addColorStop(1, 'rgba(230,225,215,0)');
      ctx.fillStyle = g; ctx.beginPath(); ctx.arc(p.x, p.y, p.r, 0, 7); ctx.fill();
    }
    if (boost > 0) boost = Math.max(0, boost - .003);
  }
  const chars = '01ﾊﾐﾋｰｳｼﾅﾓﾆｻﾜﾂｵﾘｱﾎﾃﾏｹﾒｴｶｷﾑﾕﾗｾﾈｽﾀﾇﾍ☕';
  function matrix() {
    ctx.fillStyle = 'rgba(7,7,13,.12)'; ctx.fillRect(0, 0, cv.width, cv.height);
    ctx.font = '16px monospace';
    cols.forEach((y, i) => {
      ctx.fillStyle = i % 9 === 0 ? '#ff2bd6' : '#00f0ff'; ctx.globalAlpha = .55;
      ctx.fillText(chars[Math.floor(Math.random() * chars.length)], i * 18, y * 18); ctx.globalAlpha = 1;
      cols[i] = y * 18 > cv.height && Math.random() > .975 ? 0 : y + 1;
    });
  }
  function loop(t) {
    raf = requestAnimationFrame(loop);
    if (t - last < 33) return; last = t;
    if (mode === 'smoke') smoke(); else if (mode === 'matrix') matrix();
  }
  return {
    set(m) {
      mode = reduce ? 'none' : m; parts = []; ctx.clearRect(0, 0, cv.width, cv.height);
      cancelAnimationFrame(raf); if (mode !== 'none') raf = requestAnimationFrame(loop);
      cv.style.opacity = mode === 'matrix' ? .35 : 1;
    },
    burst() { if (mode === 'smoke') boost = 1; }
  };
})();
