<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>TitipIn - Titipan Online</title>
<style>
  :root { --g:#03ac0e; --bg:#f3f4f6; --tx:#1f2937; --mu:#6b7280; }
  * { box-sizing:border-box; margin:0; font-family:system-ui,Arial,sans-serif; }
  body { background:var(--bg); color:var(--tx); }
  header { background:#fff; box-shadow:0 1px 4px #0002; position:sticky; top:0; z-index:5; }
  .bar { max-width:1100px; margin:auto; padding:12px 16px; display:flex; gap:12px; align-items:center; }
  .logo { font-weight:800; font-size:22px; color:var(--g); }
  .bar input, .bar select { padding:9px 12px; border:1px solid #d1d5db; border-radius:8px; }
  #search { flex:1; }
  button { background:var(--g); color:#fff; border:0; padding:9px 16px; border-radius:8px; cursor:pointer; font-weight:600; }
  button.out { background:#fff; color:var(--g); border:1px solid var(--g); }
  main { max-width:1100px; margin:20px auto; padding:0 16px; }
  #login { max-width:340px; margin:60px auto; background:#fff; padding:24px; border-radius:12px; box-shadow:0 2px 8px #0002; display:grid; gap:10px; }
  #login input { padding:10px; border:1px solid #d1d5db; border-radius:8px; }
  #login small, #msg { color:var(--mu); }
  #msg { margin-bottom:12px; }
  .grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(190px,1fr)); gap:14px; }
  .card { background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 1px 4px #0002; }
  .card img { width:100%; aspect-ratio:1; object-fit:cover; background:#e5e7eb; }
  .info { padding:10px; display:grid; gap:4px; }
  .name { font-size:14px; line-height:1.3; height:36px; overflow:hidden; }
  .price { font-weight:700; font-size:16px; }
  .meta { font-size:12px; color:var(--mu); }
  .tag { display:inline-block; font-size:11px; background:#e8f7e9; color:var(--g); border-radius:4px; padding:1px 6px; }
  .hide { display:none !important; }
</style>
</head>
<body>
<header>
  <div class="bar">
    <span class="logo">TitipIn</span>
    <input id="search" class="hide" placeholder="Cari barang titipan...">
    <select id="category" class="hide">
      <option value="">Semua kategori</option>
      <option>Makanan</option><option>Fashion</option><option>Kecantikan</option><option>Aksesoris</option>
    </select>
    <button id="logout" class="out hide">Logout</button>
  </div>
</header>

<div id="login">
  <h3>Masuk ke TitipIn</h3>
  <input id="email" type="email" value="admin@titipin.test" placeholder="Email">
  <input id="password" type="password" value="password123" placeholder="Password">
  <button id="btnLogin">Login</button>
  <small id="err"></small>
</div>

<main id="shop" class="hide">
  <p id="msg"></p>
  <div id="grid" class="grid"></div>
</main>

<script>
const $ = id => document.getElementById(id);
const rupiah = n => new Intl.NumberFormat('id-ID', {style:'currency', currency:'IDR', maximumFractionDigits:0}).format(n);
let token = sessionStorage.getItem('token');

async function api(path, opt = {}) {
  const res = await fetch('/api' + path, {
    ...opt,
    headers: {Accept:'application/json', 'Content-Type':'application/json',
              ...(token ? {Authorization:'Bearer ' + token} : {})}
  });
  return {status: res.status, body: await res.json()};
}

function show(loggedIn) {
  ['login'].forEach(i => $(i).classList.toggle('hide', loggedIn));
  ['shop','search','category','logout'].forEach(i => $(i).classList.toggle('hide', !loggedIn));
  if (loggedIn) load();
}

async function load() {
  const q = new URLSearchParams({status:'available', per_page:48, search:$('search').value, category:$('category').value});
  const {status, body} = await api('/items?' + q);
  if (status === 401) { token = null; sessionStorage.removeItem('token'); return show(false); }
  $('msg').textContent = body.meta.total + ' barang tersedia';
  const grid = $('grid'); grid.replaceChildren();
  body.data.forEach(it => {
    const c = document.createElement('div'); c.className = 'card';
    const img = new Image(); img.src = it.image_url || ''; img.alt = it.name; img.loading = 'lazy';
    const info = document.createElement('div'); info.className = 'info';
    const add = (cls, txt) => { const e = document.createElement('div'); e.className = cls; e.textContent = txt; info.append(e); return e; };
    add('name', it.name);
    add('price', rupiah(it.price));
    add('meta', 'Titipan dari ' + it.origin_city);
    add('meta', 'Stok: ' + it.stock);
    add('tag', it.category);
    c.append(img, info); grid.append(c);
  });
}

$('btnLogin').onclick = async () => {
  const {status, body} = await api('/login', {method:'POST', body:JSON.stringify({email:$('email').value, password:$('password').value})});
  if (status !== 200) { $('err').textContent = body.message; return; }
  token = body.token; sessionStorage.setItem('token', token); $('err').textContent = ''; show(true);
};
$('logout').onclick = async () => { await api('/logout', {method:'POST'}); token = null; sessionStorage.removeItem('token'); show(false); };
let t; $('search').oninput = () => { clearTimeout(t); t = setTimeout(load, 300); };
$('category').onchange = load;
show(!!token);
</script>
</body>
</html>
