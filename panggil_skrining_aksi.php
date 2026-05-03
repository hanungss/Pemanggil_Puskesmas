<?php
// panggil_skrining_aksi.php
header('Content-Type: application/json');

// 1. KREDENSIAL PUSHER
$app_id  = "2146310"; 
$key     = "8b7f969aee7f1ab6ea06"; 
$secret  = "0437e5df64a8e621585d"; 
$cluster = "ap1";

// 2. TANGKAP DATA
$no_antrean = $_POST['no_antrean'] ?? '';
$nama       = $_POST['nama'] ?? '';
$tujuan     = $_POST['poli'] ?? 'Meja Skrining'; // Default ke Skrining

if (empty($no_antrean) || empty($nama)) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
    exit;
}

// 3. PAYLOAD UNTUK PUSHER
// 'poli' di sini akan berisi "Meja Skrining" agar suara panggilannya benar
$payload = json_encode([
    'name' => 'panggil-event',
    'channels' => ['antrean-channel'],
    'data' => json_encode([
        'no_antrean' => $no_antrean,
        'nama' => $nama,
        'poli' => $tujuan 
    ])
]);

// 4. LOGIKA AUTH & CURL
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
curl_close($ch);

if ($result === "{}") {
    echo json_encode(['status' => 'success', 'message' => 'Panggilan skrining terkirim!']);
} else {
    echo json_encode(['status' => 'error', 'pusher_response' => $result]);
}