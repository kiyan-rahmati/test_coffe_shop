<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Coffee House</title>

<style>

/* ===== RESET ===== */
*{margin:0;padding:0;box-sizing:border-box;font-family:sans-serif;}

body{
background:#0d0d0d;
color:white;
}

/* ===== NAVBAR ===== */
nav{
display:flex;
justify-content:space-between;
align-items:center;
padding:15px 30px;
background:rgba(20,20,20,0.8);
backdrop-filter:blur(10px);
position:sticky;
top:0;
z-index:100;
}

nav h2{
color:#d6a86e;
}

nav a{
color:white;
text-decoration:none;
margin:0 10px;
opacity:0.8;
transition:0.3s;
}

nav a:hover{
opacity:1;
color:#d6a86e;
}

/* ===== HERO ===== */
.hero{
height:85vh;
background:url('https://images.unsplash.com/photo-1509042239860-f550ce710b93') center/cover;
display:flex;
align-items:center;
justify-content:center;
position:relative;
text-align:center;
}

.hero::after{
content:"";
position:absolute;
top:0;left:0;right:0;bottom:0;
background:linear-gradient(to bottom,rgba(0,0,0,0.4),rgba(0,0,0,0.9));
}

.hero-content{
position:relative;
max-width:600px;
}

.hero h1{
font-size:50px;
color:#fff;
}

.hero p{
opacity:0.8;
margin-top:10px;
}

/* ===== MENU ===== */
.section-title{
text-align:center;
margin:50px 0 20px;
color:#d6a86e;
font-size:30px;
}

.menu{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
gap:20px;
padding:20px 40px;
}

.card{
background:rgba(255,255,255,0.05);
backdrop-filter:blur(10px);
border-radius:15px;
overflow:hidden;
transition:0.3s;
border:1px solid rgba(255,255,255,0.1);
}

.card:hover{
transform:translateY(-8px);
box-shadow:0 10px 30px rgba(0,0,0,0.5);
}

.card img{
width:100%;
height:180px;
object-fit:cover;
}

.card-body{
padding:15px;
}

.price{
color:#d6a86e;
margin-top:5px;
}

.rate{
color:#ffd700;
margin:5px 0;
}

button{
width:100%;
padding:10px;
border:none;
background:#d6a86e;
color:black;
font-weight:bold;
cursor:pointer;
transition:0.3s;
}

button:hover{
background:#b88a4a;
}

/* ===== CART ===== */
.cart{
position:fixed;
bottom:20px;
right:20px;
background:#d6a86e;
color:black;
padding:15px 20px;
border-radius:50px;
cursor:pointer;
font-weight:bold;
}

#cartBox{
position:fixed;
right:20px;
bottom:80px;
width:260px;
background:#1b1b1b;
border-radius:10px;
padding:15px;
display:none;
}

#cartBox h4{
margin-bottom:10px;
color:#d6a86e;
}

.cart-item{
font-size:14px;
margin:5px 0;
}

/* ===== RESPONSIVE ===== */
@media(max-width:600px){
.hero h1{font-size:30px;}
nav{flex-direction:column;gap:10px;}
}

</style>
</head>

<body>

<!-- NAV -->
<nav>
<h2>☕ Coffee House</h2>
<div>
<a href="#">Home</a>
<a href="#menu">Menu</a>
<a href="#">Contact</a>
</div>
</nav>

<!-- HERO -->
<div class="hero">
<div class="hero-content">
<h1>Premium Coffee Experience</h1>
<p>Fresh roasted beans • Cozy atmosphere • Fast service</p>
</div>
</div>

<!-- MENU -->
<h2 class="section-title" id="menu">Our Menu</h2>

<div class="menu">

<div class="card">
<img src="https://images.unsplash.com/photo-1511920170033-f8396924c348">
<div class="card-body">
<h3>Espresso</h3>
<p class="rate">⭐⭐⭐⭐☆ 4.8</p>
<p class="price">$3.00</p>
<button onclick="add('Espresso')">Add to Cart</button>
</div>
</div>

<div class="card">
<img src="https://images.unsplash.com/photo-1461023058943-07fcbe16d735">
<div class="card-body">
<h3>Latte</h3>
<p class="rate">⭐⭐⭐⭐⭐ 4.9</p>
<p class="price">$4.00</p>
<button onclick="add('Latte')">Add to Cart</button>
</div>
</div>

<div class="card">
<img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93">
<div class="card-body">
<h3>Cappuccino</h3>
<p class="rate">⭐⭐⭐⭐☆ 4.7</p>
<p class="price">$4.5</p>
<button onclick="add('Cappuccino')">Add to Cart</button>
</div>
</div>

</div>

<!-- CART -->
<div class="cart" onclick="toggleCart()">🛒 Cart</div>

<div id="cartBox">
<h4>Your Order</h4>
<div id="items"></div>
</div>

<script>

let cart = [];

function add(item){
cart.push(item);
updateCart();
}

function updateCart(){
let box = document.getElementById("items");
box.innerHTML = "";

cart.forEach((c,i)=>{
box.innerHTML += "<div class='cart-item'>"+(i+1)+". "+c+"</div>";
});
}

function toggleCart(){
let box = document.getElementById("cartBox");
box.style.display = box.style.display==="block"?"none":"block";
}

</script>

</body>
</html>
