<?php
/**
 * Tile Proxy — ambil tile OSM dari server dan kirim ke client
 * Route: GET /tile/{z}/{x}/{y}
 * 
 * Tambahkan route di Routes.php:
 * $routes->get('tile/(:num)/(:num)/(:num)', 'HomeController::tile/$1/$2/$3');
 */

// Langsung sebagai standalone file di public/tile-proxy.php
header('Access-Control-Allow-Origin: *');
header('Cache-Control: public, max-age=86400');

$z = (int)($_GET['z'] ?? 0);
$x = (int)($_GET['x'] ?? 0);
$y = (int)($_GET['y'] ?? 0);

// Validasi
if ($z < 0 || $z > 19 || $x < 0 || $y < 0) {
    http_response_code(400);
    exit('Invalid tile coordinates');
}

$subdomain = ['a', 'b', 'c'][($x + $y) % 3];
$url       = "https://{$subdomain}.tile.openstreetmap.org/{$z}/{$x}/{$y}.png";

$ctx = stream_context_create([
    'http' => [
        'timeout'       => 10,
        'user_agent'    => 'SIGFasilitas/1.0 (educational project)',
        'ignore_errors' => true,
    ],
    'ssl' => [
        'verify_peer'      => false,
        'verify_peer_name' => false,
    ],
]);

$data = @file_get_contents($url, false, $ctx);

if ($data === false || empty($data)) {
    http_response_code(502);
    exit('Failed to fetch tile');
}

header('Content-Type: image/png');
header('Content-Length: ' . strlen($data));
echo $data;
