<?php
// simpan_antrean_far.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

error_reporting(0);
date_default_timezone_set('Asia/Jakarta');

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!empty($data)) {
    $folder = 'data';
    if (!is_dir($folder)) mkdir($folder, 0777, true);

    $file = $folder . '/antrean_far_' . date('Y-m-d') . '.txt';
    $content = "";

    foreach ($data as $row) {
        $no     = $row['no_antrean'] ?? '-';
        $jam    = $row['jam'] ?? '-';
        $nama   = $row['nama'] ?? '-';
        $asal   = $row['ruangan_asal'] ?? '-';
        $status = $row['status'] ?? 'Proses';

        $content .= "No: $no | Jam: $jam | Nama: $nama | Dari: $asal | Status: $status\n";
    }

    if (file_put_contents($file, $content)) {
        echo json_encode(["status" => "success", "message" => "Data farmasi updated"]);
    } else {
        echo json_encode(["status" => "error"]);
    }
}
?>