(() => {
  "use strict";
  const $ = (selector, root = document) => root.querySelector(selector);
  const $$ = (selector, root = document) => [...root.querySelectorAll(selector)];
  const products = {
    latte:{name:"لاته نارون",price:120000}, americano:{name:"آمریکانو",price:95000},
    chocolate:{name:"هات چاکلت",price:135000}, iced:{name:"آیس لاته",price:125000},
    cake:{name:"چیزکیک روز",price:150000}, tea:{name:"چای دارچین",price:75000}
  };
  const persian = value => new Intl.NumberFormat("fa-IR").format(value);
  let cart = JSON.parse(localStorage.getItem("narvan-cart") || "{}");
  let toastTimer;
  const toast = message => {
    const el = $("#toast"); el.textContent = message; el.classList.add("show");
    clearTimeout(toastTimer); toastTimer = setTimeout(() => el.classList.remove("show"), 2300);
  };
  const saveCart = () => localStorage.setItem("narvan-cart", JSON.stringify(cart));
  const cartCount = () => Object.values(cart).reduce((sum, qty) => sum + qty, 0);
  const total = () => Object.entries(cart).reduce((sum, [id, qty]) => sum + products[id].price * qty, 0);
  function renderCart(){
    $(".cart-count").textContent = persian(cartCount());
    $("#cartTotal").textContent = persian(total()) + " تومان";
    const host = $("#cartItems");
    const entries = Object.entries(cart).filter(([,qty]) => qty > 0);
    if(!entries.length){host.innerHTML='<div class="cart-empty">سبدت هنوز خالیه ☕<small>از منو یه چیزی انتخاب کن.</small></div>';return;}
    host.innerHTML = entries.map(([id,qty]) => {
      const p = products[id];
      return '<div class="cart-row"><div><strong>'+p.name+'</strong><small>'+persian(p.price)+' تومان</small></div><div class="qty-control"><button data-qty="'+id+'" data-step="-1" aria-label="کم کردن">−</button><b>'+persian(qty)+'</b><button data-qty="'+id+'" data-step="1" aria-label="زیاد کردن">+</button></div></div>';
    }).join("");
  }
  function addToCart(id){
    if(!products[id]) return;
    cart[id] = (cart[id] || 0) + 1; saveCart(); renderCart(); toast(products[id].name + " به سبد اضافه شد");
  }
  function setDrawer(open){
    $("#cartDrawer").classList.toggle("open",open);
    $("#cartDrawer").setAttribute("aria-hidden",String(!open));
    $("#overlay").classList.toggle("show",open || $("#searchModal").classList.contains("show"));
    document.body.style.overflow = open || $("#searchModal").classList.contains("show") ? "hidden" : "";
  }
  function closeLayers(){
    $("#cartDrawer").classList.remove("open"); $("#cartDrawer").setAttribute("aria-hidden","true");
    $("#searchModal").classList.remove("show"); $("#searchModal").setAttribute("aria-hidden","true");
    $("#overlay").classList.remove("show"); document.body.style.overflow="";
  }
  $$(".quick-add").forEach(btn => btn.addEventListener("click", () => addToCart(btn.dataset.add)));
  $(".cart-button").addEventListener("click", () => setDrawer(true));
  $(".drawer-close").addEventListener("click", closeLayers);
  $("#overlay").addEventListener("click", closeLayers);
  $("#cartItems").addEventListener("click", event => {
    const btn = event.target.closest("[data-qty]"); if(!btn) return;
    const id = btn.dataset.qty; cart[id] = (cart[id] || 0) + Number(btn.dataset.step);
    if(cart[id] <= 0) delete cart[id]; saveCart(); renderCart();
  });
  $("#checkoutButton").addEventListener("click", () => {
    if(!cartCount()){toast("اول یه چیزی به سبدت اضافه کن 🙂");return;}
    cart={};saveCart();renderCart();closeLayers();toast("این فقط یک دموی نمایشی بود؛ سفارش ثبت نشد ✳");
  });

  // Category filters and live menu search work together.
  let activeFilter = "all";
  const searchInput = $("#menuSearch");
  function filterProducts(){
    const query = searchInput.value.trim().toLocaleLowerCase("fa");
    let visible = 0;
    $$(".product-card").forEach(card => {
      const matchesCategory = activeFilter === "all" || card.dataset.category === activeFilter;
      const matchesText = !query || (card.dataset.name + " " + card.textContent).toLocaleLowerCase("fa").includes(query);
      card.hidden = !(matchesCategory && matchesText); if(!card.hidden) visible++;
    });
    $("#emptyState").hidden = visible !== 0;
  }
  $$(".filter-tab").forEach(tab => tab.addEventListener("click", () => {
    $$(".filter-tab").forEach(item => item.classList.remove("active"));
    tab.classList.add("active"); activeFilter=tab.dataset.filter; filterProducts();
  }));
  searchInput.addEventListener("input",filterProducts);

  // Global search opens the menu and filters it as the visitor types.
  $(".search-open").addEventListener("click", () => {
    $("#searchModal").classList.add("show"); $("#searchModal").setAttribute("aria-hidden","false");
    $("#overlay").classList.add("show"); document.body.style.overflow="hidden";
    setTimeout(() => $("#globalSearch").focus(),100);
  });
  $(".search-close").addEventListener("click",closeLayers);
  $("#globalSearch").addEventListener("input",event => {
    searchInput.value=event.target.value; activeFilter="all";
    $$(".filter-tab").forEach(tab => tab.classList.toggle("active",tab.dataset.filter==="all"));
    filterProducts();
  });
  $("#globalSearch").addEventListener("keydown",event => {
    if(event.key==="Enter"){closeLayers();$("#menu").scrollIntoView({behavior:"smooth"});searchInput.focus();}
  });
  document.addEventListener("keydown",event => {if(event.key==="Escape")closeLayers();});

  // Mobile navigation closes after selecting a section.
  const menuToggle = $(".menu-toggle"), nav = $(".main-nav");
  menuToggle.addEventListener("click", () => {
    const open=nav.classList.toggle("open"); menuToggle.setAttribute("aria-expanded",String(open));
  });
  $$(".nav-link").forEach(link => link.addEventListener("click",() => {
    nav.classList.remove("open");menuToggle.setAttribute("aria-expanded","false");
  }));

  // Scroll reveal and active navigation state.
  const revealTargets = $$(".section-heading,.product-card,.story-image,.story-copy,.book-card,.music-copy,.music-art,.visit-section>div");
  revealTargets.forEach(el => el.classList.add("reveal"));
  if("IntersectionObserver" in window){
    const observer = new IntersectionObserver(entries => entries.forEach(entry => {
      if(entry.isIntersecting){entry.target.classList.add("visible");observer.unobserve(entry.target);}
    }),{threshold:.12});
    revealTargets.forEach(el => observer.observe(el));
    const sectionObserver = new IntersectionObserver(entries => entries.forEach(entry => {
      if(entry.isIntersecting){$$(".nav-link").forEach(link => link.classList.toggle("active",link.hash==="#"+entry.target.id));}
    }),{rootMargin:"-35% 0px -55% 0px"});
    ["home","menu","story","library","playlist"].forEach(id => {const el=$("#"+id);if(el)sectionObserver.observe(el);});
  } else revealTargets.forEach(el=>el.classList.add("visible"));

  // Demo player: animated progress indicator, no audio file or external service required.
  let playing=false, progress=0, playerTimer=null;
  const playButton=$("#playButton"), vinyl=$(".vinyl"), progressBar=$("#progressBar"), trackTime=$("#trackTime");
  function updatePlayer(){
    progress=Math.min(100,progress+1.25);progressBar.style.width=progress+"%";
    const seconds=Math.floor(progress/100*180);
    trackTime.textContent=persian(Math.floor(seconds/60))+":"+persian(String(seconds%60).padStart(2,"0"));
    if(progress>=100){pausePlayer();progress=0;progressBar.style.width="0%";trackTime.textContent="۰:۰۰";}
  }
  function pausePlayer(){playing=false;clearInterval(playerTimer);playButton.textContent="▶";vinyl.classList.remove("playing");}
  playButton.addEventListener("click",() => {
    if(playing){pausePlayer();$("#musicHint").textContent="پخش نمایشی متوقف شد.";return;}
    playing=true;playButton.textContent="Ⅱ";vinyl.classList.add("playing");
    $("#musicHint").textContent="نمایش تعاملی پلیر؛ برای نسخه نهایی می‌توان فایل موسیقی واقعی اضافه کرد.";
    playerTimer=setInterval(updatePlayer,180);
  });

  // Respect saved state while keeping the preview entirely client-side.
  renderCart();
})();