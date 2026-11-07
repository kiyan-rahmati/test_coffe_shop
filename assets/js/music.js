/* پخش‌کننده‌ی آهنگ: هر تم یک آهنگ دارد؛ پخش/توقف، قبلی/بعدی و صدا */
window.Music = (function () {
  const T = window.NAROON.themes, keys = Object.keys(T), a = new Audio();
  a.loop = true; a.volume = .5; a.preload = 'none';
  let idx = 0;
  const $ = id => document.getElementById(id);
  function ui() {
    $('pp').textContent = a.paused ? '▶' : '⏸'; $('pp').setAttribute('aria-label', a.paused ? 'پخش' : 'توقف');
    $('song').textContent = T[keys[idx]].song;
  }
  function load(k) { idx = Math.max(0, keys.indexOf(k)); const was = !a.paused; a.src = T[keys[idx]].music; if (was) a.play().catch(() => {}); ui(); }
  function play() { if (!a.src) a.src = T[keys[idx]].music; return a.play().then(ui).catch(ui); }
  function pause() { a.pause(); ui(); }
  $('pp').onclick = () => (a.paused ? play() : pause());
  $('nx').onclick = () => load(keys[(idx + 1) % keys.length]);
  $('pv').onclick = () => load(keys[(idx + keys.length - 1) % keys.length]);
  $('vol').oninput = e => (a.volume = e.target.value / 100);
  a.addEventListener('play', ui); a.addEventListener('pause', ui);
  return { load, play, pause, get playing() { return !a.paused; } };
})();
