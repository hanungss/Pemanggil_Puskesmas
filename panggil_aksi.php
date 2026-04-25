<?php
// panggil_aksi.php
header('Content-Type: application/json');

// 1. KREDENSIAL PUSHER (Berdasarkan url_debug terbaru Anda)
$app_id  = "2146310"; 
$key     = "8b7f969aee7f1ab6ea06"; 
$secret  = "0437e5df64a8e621585d"; // <-- PASTIKAN SECRET INI PASANGAN DARI KEY DI ATAS
$cluster = "ap1";

// 2. TANGKAP DATA DARI OPERATOR
$no_antrean = isset($_POST['no_antrean']) ? $_POST['no_antrean'] : '';
$nama       = isset($_POST['nama']) ? $_POST['nama'] : '';
$poli       = isset($_POST['poli']) ? $_POST['poli'] : '';

if (empty($no_antrean) || empty($nama)) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Data tidak lengkap',
        'received' => $_POST
    ]);
    exit;
}

// 3. LOGIKA PUSHER cURL (STRUKTUR DIPERBAIKI)
// Pusher API memerlukan 'name', 'channels', dan 'data' di dalam payload
$payload = json_encode([
    'name' => 'panggil-event',
    'channels' => ['antrean-channel'],
    'data' => json_encode([
        'no_antrean' => $no_antrean,
        'nama' => $nama,
        'poli' => $poli
    ])
]);

$auth_timestamp = time();
$auth_version   = "1.0";
$body_md5       = md5($payload);
$query_string   = "auth_key=$key&auth_timestamp=$auth_timestamp&auth_version=$auth_version&body_md5=$body_md5";
$auth_string    = "POST\n/apps/$app_id/events\n$query_string";
$auth_signature = hash_hmac('sha256', $auth_string, $secret);

$url = "https://api-$cluster.pusher.com/apps/$app_id/events?$query_string&auth_signature=$auth_signature";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);

// 4. RESPON
if ($result === "{}") {
    echo json_encode([
        'status' => 'success',
        'message' => 'Sinyal terkirim ke Pusher!'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'pusher_response' => $result,
        'http_code' => $info['http_code']
    ]);
}