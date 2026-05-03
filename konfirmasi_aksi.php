<?php
// konfirmasi_aksi.php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $folder = 'data';
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $filename = $folder . "/skrining_" . date('Y-m-d') . ".txt";
    
    $no_antrean = $_POST['no_antrean'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $poli = $_POST['poli'] ?? '';
    $umur = $_POST['umur'] ?? '';
    $jam_datang = $_POST['jam_datang'] ?? '';
    $jam_konfirmasi = date('H:i:s');

    // Gunakan mode ab+ agar bisa membaca dan menambah data dengan aman
    $fp = fopen($filename, 'ab+');

    if (flock($fp, LOCK_EX)) {
        // Baca semua isi file untuk validasi dan hitung urutan
        rewind($fp);
        $lines = [];
        while (($line = fgets($fp)) !== false) {
            $trimmed = trim($line);
            if ($trimmed !== '') {
                $lines[] = $trimmed;
            }
        }
        
        // 1. Cek Duplikasi (Jangan biarkan nomor antrean yang sama masuk dua kali)
        $is_exists = false;
        $max_urut = 0;
        
        foreach ($lines as $l) {
            $cols = explode('|', $l);
            if ($cols[0] === $no_antrean) {
                $is_exists = true;
            }
            // Ambil nomor urut terakhir (index ke-6)
            $urut_saat_ini = isset($cols[6]) ? intval($cols[6]) : 0;
            if ($urut_saat_ini > $max_urut) {
                $max_urut = $urut_saat_ini;
            }
        }

        if (!$is_exists) {
            // Nomor urut baru adalah nomor terbesar yang ada + 1
            $nomor_urut_skrining = $max_urut + 1;
            
            $data_row = implode("|", [
                $no_antrean,
                $nama,
                $poli,
                $umur,
                $jam_datang,
                $jam_konfirmasi,
                $nomor_urut_skrining
            ]) . PHP_EOL;

            fwrite($fp, $data_row);
            fflush($fp);
            
            echo json_encode(['status' => 'success', 'urut' => $nomor_urut_skrining]);
        } else {
            echo json_encode(['status' => 'already_exists']);
        }

        flock($fp, LOCK_UN);
    }
    fclose($fp);
}