<?php
$fromLat = (float)($_GET['fromLat'] ?? 0.5071);
$fromLng = (float)($_GET['fromLng'] ?? 101.4478);
$toLat   = (float)($_GET['toLat']   ?? 0.5100);
$toLng   = (float)($_GET['toLng']   ?? 101.4500);
$toNama  = htmlspecialchars($_GET['toNama'] ?? 'Tujuan', ENT_QUOTES, 'UTF-8');

function haversine($la1,$ln1,$la2,$ln2){
    $R=6371; $r=M_PI/180;
    $a=sin(($la2-$la1)*$r/2)**2+cos($la1*$r)*cos($la2*$r)*sin(($ln2-$ln1)*$r/2)**2;
    return $R*2*atan2(sqrt($a),sqrt(1-$a));
}

$jarak = haversine($fromLat,$fromLng,$toLat,$toLng);
$zoom  = 15;
if ($jarak > 50) $zoom = 10;
elseif ($jarak > 20) $zoom = 11;
elseif ($jarak > 10) $zoom = 12;
elseif ($jarak > 5)  $zoom = 13;
elseif ($jarak > 2)  $zoom = 14;

function fmtJarak($km){
    if ($km < 1) return round($km*1000).'m';
    if ($km < 10) return number_format($km,1).' km';
    return round($km).' km';
}

// Ambil tile awal (zoom default) dan embed sebagai base64
function latLngToTileXY($lat,$lng,$z){
    $n = pow(2,$z);
    $x = (int)(($lng+180)/360*$n);
    $lr = $lat*M_PI/180;
    $y = (int)((1-log(tan($lr)+1/cos($lr))/M_PI)/2*$n);
    return [$x,$y];
}

function getTileBase64($z,$x,$y){
    $n = pow(2,$z);
    if ($x<0||$y<0||$x>=$n||$y>=$n) return null;
    $sub = ['a','b','c'][($x+$y)%3];
    $url = "https://{$sub}.tile.openstreetmap.org/{$z}/{$x}/{$y}.png";
    $ctx = stream_context_create([
        'http'=>['timeout'=>8,'user_agent'=>'SIGFasilitasApp/1.0 Mozilla/5.0'],
        'ssl' =>['verify_peer'=>false,'verify_peer_name'=>false],
    ]);
    $data = @file_get_contents($url,false,$ctx);
    if (!$data) return null;
    return 'data:image/png;base64,'.base64_encode($data);
}

$centerLat = ($fromLat+$toLat)/2;
$centerLng = ($fromLng+$toLng)/2;
[$cTX,$cTY] = latLngToTileXY($centerLat,$centerLng,$zoom);

// Pre-load tile untuk zoom awal saja (3x3 grid = 9 tile)
$initTiles = [];
for ($dy=-2;$dy<=2;$dy++){
    for ($dx=-2;$dx<=2;$dx++){
        $tx=$cTX+$dx; $ty=$cTY+$dy;
        $b64=getTileBase64($zoom,$tx,$ty);
        if ($b64) $initTiles["{$zoom}/{$tx}/{$ty}"] = $b64;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    <title>Navigasi</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        html,body{width:100%;height:100%;overflow:hidden;background:#aad3df;}
        .info-bar{
            position:fixed;top:0;left:0;right:0;height:48px;
            background:rgba(5,10,24,0.97);
            display:flex;align-items:center;padding:0 14px;gap:10px;
            z-index:100;border-bottom:1px solid rgba(0,229,255,0.3);
        }
        .dest-name{
            font-family:-apple-system,sans-serif;font-size:13px;font-weight:700;
            color:#00e5ff;flex:1;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;
        }
        .dist-badge{
            font-family:monospace;font-size:11px;padding:3px 10px;
            border-radius:100px;border:1px solid rgba(57,255,110,0.4);
            background:rgba(57,255,110,0.1);color:#39ff6e;white-space:nowrap;
        }
        #map-wrap{position:fixed;top:48px;left:0;right:0;bottom:0;}
        #map-canvas{display:block;cursor:grab;}
        #map-canvas:active{cursor:grabbing;}
        .fab-group{
            position:fixed;right:12px;bottom:72px;
            display:flex;flex-direction:column;gap:6px;z-index:100;
        }
        .fab{
            width:40px;height:40px;background:rgba(5,10,24,0.9);
            border:1px solid rgba(255,255,255,0.2);border-radius:10px;
            color:white;font-size:20px;display:flex;
            align-items:center;justify-content:center;cursor:pointer;
            user-select:none;-webkit-user-select:none;
        }
        .back-btn{
            position:fixed;bottom:16px;left:50%;transform:translateX(-50%);
            background:rgba(5,10,24,0.92);border:1px solid rgba(255,255,255,0.2);
            border-radius:100px;color:white;
            font-family:-apple-system,sans-serif;font-size:14px;font-weight:600;
            padding:10px 24px;cursor:pointer;z-index:100;white-space:nowrap;
        }
        #loading{
            position:fixed;inset:0;background:#050a18;
            display:flex;flex-direction:column;align-items:center;justify-content:center;
            z-index:999;transition:opacity .4s;
        }
        #loading.out{opacity:0;pointer-events:none;}
        .spin{
            width:36px;height:36px;
            border:3px solid rgba(0,229,255,0.15);
            border-top-color:#00e5ff;border-radius:50%;
            animation:sp .8s linear infinite;
        }
        @keyframes sp{to{transform:rotate(360deg);}}
        #loading p{margin-top:12px;font-family:sans-serif;font-size:13px;color:#6b7a99;}
    </style>
</head>
<body>

<div id="loading"><div class="spin"></div><p>Memuat peta...</p></div>

<div class="info-bar">
    <span style="font-size:15px">📍</span>
    <span class="dest-name"><?= $toNama ?></span>
    <span class="dist-badge" id="dist"><?= fmtJarak($jarak) ?></span>
</div>

<div id="map-wrap">
    <canvas id="map-canvas"></canvas>
</div>

<div class="fab-group">
    <div class="fab" id="zin">+</div>
    <div class="fab" id="zout">−</div>
</div>

<button class="back-btn" id="btn-back">← Kembali</button>

<script>
// ── Konstanta dari PHP ──
const FROM_LAT = <?= $fromLat ?>;
const FROM_LNG = <?= $fromLng ?>;
const TO_LAT   = <?= $toLat ?>;
const TO_LNG   = <?= $toLng ?>;
const TO_NAMA  = <?= json_encode($toNama) ?>;
const JARAK    = <?= round($jarak,3) ?>;

// Base URL untuk tile proxy (path ke public/)
const BASE = (() => {
    const p = window.location.pathname;           // /sig-backend/public/navigate
    const i = p.indexOf('/public/');
    return i >= 0
        ? window.location.origin + p.substring(0, i+8)  // http://10.0.2.2/sig-backend/public/
        : window.location.origin + '/';
})();

// ── State ──
let zoom      = <?= $zoom ?>;
let centerLat = (FROM_LAT + TO_LAT) / 2;
let centerLng = (FROM_LNG + TO_LNG) / 2;
let routePts  = null;

// ── Tile cache — menyimpan Image objects per "z/x/y" ──
const tileCache = {};

// Pre-load tile awal dari PHP (base64)
const INIT_TILES = <?= json_encode($initTiles) ?>;
Object.entries(INIT_TILES).forEach(([key, b64]) => {
    const img = new Image();
    img.src   = b64;
    img.onload = () => draw();
    tileCache[key] = img;
});

// ── Tile proxy fetch — untuk zoom baru atau tile di luar pre-load ──
const fetchingTiles = new Set();

function fetchTile(z, tx, ty) {
    const n = Math.pow(2, z);
    if (tx < 0 || ty < 0 || tx >= n || ty >= n) return;
    const key = `${z}/${tx}/${ty}`;
    if (tileCache[key] || fetchingTiles.has(key)) return;

    fetchingTiles.add(key);
    const url = `${BASE}tile-proxy.php?z=${z}&x=${tx}&y=${ty}`;

    fetch(url)
        .then(r => r.blob())
        .then(blob => {
            const img      = new Image();
            img.onload     = () => { tileCache[key] = img; draw(); };
            img.src        = URL.createObjectURL(blob);
            fetchingTiles.delete(key);
        })
        .catch(() => {
            // Tandai sebagai gagal agar tidak retry terus
            tileCache[key] = null;
            fetchingTiles.delete(key);
        });
}

// ── Canvas ──
const wrap   = document.getElementById('map-wrap');
const canvas = document.getElementById('map-canvas');
const ctx    = canvas.getContext('2d');
const DPR    = window.devicePixelRatio || 1;
let W = 0, H = 0;

function resizeCanvas() {
    const r = wrap.getBoundingClientRect();
    if (r.width === 0 || r.height === 0) { setTimeout(resizeCanvas, 50); return; }
    W = r.width; H = r.height;
    canvas.width  = Math.round(W * DPR);
    canvas.height = Math.round(H * DPR);
    canvas.style.width  = W + 'px';
    canvas.style.height = H + 'px';
    ctx.setTransform(DPR, 0, 0, DPR, 0, 0);
}

// ── Mercator helpers ──
function mercY(lat) {
    const r = Math.sin(lat * Math.PI / 180);
    return 0.5 - Math.log((1+r)/(1-r)) / (4*Math.PI);
}

function toPixel(lat, lng) {
    const n  = Math.pow(2, zoom);
    const ts = 256;
    const cx = (centerLng + 180) / 360 * n;
    const cy = mercY(centerLat) * n;
    return {
        x: W/2 + ((lng+180)/360*n - cx) * ts,
        y: H/2 + (mercY(lat)*n      - cy) * ts,
    };
}

// ── Draw ──
function draw() {
    if (W === 0 || H === 0) return;
    ctx.clearRect(0, 0, W, H);

    // Background warna OSM default
    ctx.fillStyle = '#aad3df';
    ctx.fillRect(0, 0, W, H);

    // Gambar tile
    const n  = Math.pow(2, zoom);
    const ts = 256;
    const cx = (centerLng + 180) / 360 * n;
    const cy = mercY(centerLat) * n;

    const tilesX = Math.ceil(W / ts) + 2;
    const tilesY = Math.ceil(H / ts) + 2;
    const startTX = Math.floor(cx - tilesX/2);
    const startTY = Math.floor(cy - tilesY/2);

    for (let dy = 0; dy <= tilesY; dy++) {
        for (let dx = 0; dx <= tilesX; dx++) {
            const tx = startTX + dx;
            const ty = startTY + dy;
            if (tx < 0 || ty < 0 || tx >= n || ty >= n) continue;

            const key = `${zoom}/${tx}/${ty}`;
            const px  = W/2 + (tx - cx) * ts;
            const py  = H/2 + (ty - cy) * ts;

            if (tileCache[key] && tileCache[key].complete && tileCache[key].naturalWidth > 0) {
                ctx.drawImage(tileCache[key], px, py, ts, ts);
            } else {
                // Placeholder
                ctx.fillStyle   = '#b8cfa0';
                ctx.fillRect(px+1, py+1, ts-2, ts-2);
                ctx.strokeStyle = 'rgba(0,0,0,0.08)';
                ctx.lineWidth   = 0.5;
                ctx.strokeRect(px, py, ts, ts);
                // Fetch tile ini
                fetchTile(zoom, tx, ty);
            }
        }
    }

    // ── Rute ──
    if (routePts && routePts.length >= 2) {
        const pts = routePts.map(p => toPixel(p.lat, p.lng));

        ctx.beginPath();
        pts.forEach((p,i) => i===0 ? ctx.moveTo(p.x,p.y) : ctx.lineTo(p.x,p.y));
        ctx.strokeStyle = 'rgba(0,80,180,0.25)';
        ctx.lineWidth   = 14; ctx.lineCap = ctx.lineJoin = 'round';
        ctx.stroke();

        ctx.beginPath();
        pts.forEach((p,i) => i===0 ? ctx.moveTo(p.x,p.y) : ctx.lineTo(p.x,p.y));
        ctx.strokeStyle = '#1a6fcc';
        ctx.lineWidth   = 5; ctx.stroke();

        ctx.beginPath();
        pts.forEach((p,i) => i===0 ? ctx.moveTo(p.x,p.y) : ctx.lineTo(p.x,p.y));
        ctx.strokeStyle = 'rgba(120,200,255,0.7)';
        ctx.lineWidth   = 2; ctx.stroke();

        if (routePts.length === 2) {
            ctx.setLineDash([12,8]);
            ctx.beginPath();
            pts.forEach((p,i) => i===0 ? ctx.moveTo(p.x,p.y) : ctx.lineTo(p.x,p.y));
            ctx.strokeStyle='#00e5ff'; ctx.lineWidth=3; ctx.stroke();
            ctx.setLineDash([]);
        }
    }

    // ── Marker: Lokasi Saya ──
    const fp = toPixel(FROM_LAT, FROM_LNG);
    ctx.beginPath(); ctx.arc(fp.x,fp.y,16,0,Math.PI*2);
    ctx.fillStyle='rgba(0,229,255,0.2)'; ctx.fill();
    ctx.beginPath(); ctx.arc(fp.x,fp.y,9,0,Math.PI*2);
    ctx.fillStyle='#00e5ff'; ctx.fill();
    ctx.strokeStyle='white'; ctx.lineWidth=3; ctx.stroke();
    ctx.font='bold 10px sans-serif'; ctx.fillStyle='rgba(0,0,0,0.8)'; ctx.textAlign='center';
    ctx.fillText('Anda', fp.x, fp.y+22);

    // ── Marker: Tujuan ──
    const tp = toPixel(TO_LAT, TO_LNG);
    const pr = 11;
    ctx.beginPath();
    ctx.ellipse(tp.x, tp.y+3, pr*0.5, pr*0.15, 0,0,Math.PI*2);
    ctx.fillStyle='rgba(0,0,0,0.2)'; ctx.fill();
    ctx.beginPath(); ctx.arc(tp.x, tp.y-pr*2.5, pr, 0,Math.PI*2);
    ctx.fillStyle='#ff6b35'; ctx.fill();
    ctx.strokeStyle='white'; ctx.lineWidth=2.5; ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(tp.x-pr*0.5, tp.y-pr*1.6);
    ctx.lineTo(tp.x, tp.y);
    ctx.lineTo(tp.x+pr*0.5, tp.y-pr*1.6);
    ctx.fillStyle='#ff6b35'; ctx.fill();
    ctx.beginPath(); ctx.arc(tp.x, tp.y-pr*2.5, pr*0.3, 0,Math.PI*2);
    ctx.fillStyle='rgba(255,255,255,0.7)'; ctx.fill();
    // Label
    const label = TO_NAMA.length > 18 ? TO_NAMA.substring(0,16)+'…' : TO_NAMA;
    ctx.font='bold 10px sans-serif';
    const tw = ctx.measureText(label).width;
    ctx.fillStyle='rgba(255,255,255,0.9)';
    ctx.fillRect(tp.x-tw/2-5, tp.y-pr*5-2, tw+10, 16);
    ctx.fillStyle='#331100'; ctx.textAlign='center';
    ctx.fillText(label, tp.x, tp.y-pr*5+11);
}

// ── Pan (drag) ──
let drag = null;
canvas.addEventListener('touchstart', e=>{
    e.preventDefault();
    if(e.touches.length===1)
        drag={x:e.touches[0].clientX,y:e.touches[0].clientY,lat:centerLat,lng:centerLng};
},{passive:false});

canvas.addEventListener('touchmove', e=>{
    e.preventDefault();
    if(e.touches.length===1&&drag){
        const dx=e.touches[0].clientX-drag.x, dy=e.touches[0].clientY-drag.y;
        const n=Math.pow(2,zoom), ts=256;
        centerLng = drag.lng - dx/(n*ts)*360;
        centerLat = drag.lat + dy/(n*ts)*180/Math.PI*Math.cos(centerLat*Math.PI/180)*1.3;
        draw();
    }
},{passive:false});

canvas.addEventListener('touchend',()=>drag=null);

// ── Zoom ──
document.getElementById('zin').addEventListener('click', ()=>{
    if(zoom<18){ zoom++; draw(); }
});
document.getElementById('zout').addEventListener('click', ()=>{
    if(zoom>8){ zoom--; draw(); }
});

// ── Back ──
document.getElementById('btn-back').addEventListener('click', ()=>{
    if(window.history.length>1) window.history.back();
    else window.close();
});

// ── OSRM Routing ──
async function loadRoute(){
    routePts=[{lat:FROM_LAT,lng:FROM_LNG},{lat:TO_LAT,lng:TO_LNG}];
    draw();
    try{
        const url=`https://router.project-osrm.org/route/v1/driving/${FROM_LNG},${FROM_LAT};${TO_LNG},${TO_LAT}?overview=full&geometries=geojson`;
        const resp=await fetch(url,{signal:AbortSignal.timeout(7000)});
        const data=await resp.json();
        if(data.code!=='Ok'||!data.routes?.length) throw new Error();
        routePts=data.routes[0].geometry.coordinates.map(c=>({lat:c[1],lng:c[0]}));
        const km=(data.routes[0].distance/1000).toFixed(1);
        const mn=Math.round(data.routes[0].duration/60);
        document.getElementById('dist').textContent=`${km} km · ~${mn} mnt`;
        const lats=routePts.map(p=>p.lat),lngs=routePts.map(p=>p.lng);
        centerLat=(Math.min(...lats)+Math.max(...lats))/2;
        centerLng=(Math.min(...lngs)+Math.max(...lngs))/2;
        draw();
    } catch{ /* garis lurus */ }
}

// ── Init ──
function init(){
    resizeCanvas();
    if(W===0||H===0){ setTimeout(init,100); return; }
    draw();
    loadRoute();
    // Hilangkan loading setelah draw pertama
    setTimeout(()=>{
        const el=document.getElementById('loading');
        if(el){el.classList.add('out');setTimeout(()=>el.remove(),400);}
    }, 800);
}

window.addEventListener('load',()=>requestAnimationFrame(init));
window.addEventListener('resize',()=>{resizeCanvas();draw();});
</script>
</body>
</html>