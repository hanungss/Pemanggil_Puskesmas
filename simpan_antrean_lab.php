<?php
// simpan_antrean_lab.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Matikan laporan error agar tidak merusak format JSON jika ada warning
error_reporting(0);
date_default_timezone_set('Asia/Jakarta');

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!empty($data)) {
    $folder = 'data';
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $file = $folder . '/antrean_lab_' . date('Y-m-d') . '.txt';
    
    // PERBAIKAN: Definisikan string kosong dulu agar tidak Undefined Variable
    $currentContent = ""; 
    
    foreach ($data as $row) {
        // Ambil data dengan proteksi jika key tidak ada
        $no     = $row['no_antrean'] ?? '-';
        $jam    = $row['jam'] ?? '-';
        $nama   = $row['nama'] ?? '-';
        $asal   = $row['ruangan_asal'] ?? '-';
        $status = $row['status'] ?? 'Proses';

        $currentContent .= "No: $no | Jam: $jam | Nama: $nama | Dari: $asal | Status: $status\n";
    }

    // Tulis ke file (menimpa data lama agar status sinkron)
    if (file_put_contents($file, $currentContent) !== false) {
        echo json_encode(["status" => "success", "message" => "File updated"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menulis file. Cek izin folder!"]);
    }
} else {
    echo json_encode(["status" => "empty", "message" => "Data kosong"]);
}