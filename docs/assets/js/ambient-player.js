(() => {
  const audio = document.getElementById('naroonAudio');
  const button = document.getElementById('playButton');
  const hint = document.getElementById('musicHint');
  const vinyl = document.querySelector('.vinyl');
  if (!audio || !button) return;
  audio.volume = 0.28;
  const sync = () => { const on = !audio.paused; button.textContent = on ? 'Ⅱ' : '▶'; vinyl?.classList.toggle('playing', on); };
  button.addEventListener('click', async (event) => {
    event.preventDefault(); event.stopImmediatePropagation();
    if (audio.paused) { try { await audio.play(); hint.textContent = 'موسیقی آرام نارون در حال پخش است.'; } catch { hint.textContent = 'برای پخش موسیقی، یک بار دیگر دکمه را بزن.'; } }
    else { audio.pause(); hint.textContent = 'موسیقی متوقف شد.'; }
    sync();
  }, true);
  audio.addEventListener('play', sync); audio.addEventListener('pause', sync); audio.addEventListener('ended', sync);
  audio.addEventListener('timeupdate', () => { const bar = document.getElementById('progressBar'); if (bar && audio.duration) bar.style.width = (audio.currentTime / audio.duration * 100) + '%'; });
  // Browsers may block audible autoplay. Try once, then gracefully wait for a user gesture.
  audio.play().then(() => { hint.textContent = 'موسیقی نارون در حال پخش است؛ هر زمان خواستی می‌توانی متوقفش کنی.'; sync(); }).catch(() => { hint.textContent = 'برای شروع موسیقی، دکمه پخش را بزن.'; });
})();
