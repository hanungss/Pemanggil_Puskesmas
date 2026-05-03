<?php
// reset_skrining_aksi.php
header('Content-Type: application/json');

$folder = 'data';
$filename = $folder . "/skrining_" . date('Y-m-d') . ".txt";

if (file_exists($filename)) {
    // Membuka file dengan mode 'w' akan mengosongkan isinya (truncate)
    $fp = fopen($filename, 'w');
    
    if (flock($fp, LOCK_EX)) { // Tetap gunakan penguncian agar aman
        ftruncate($fp, 0);
        fflush($fp);
        flock($fp, LOCK_UN);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'File berhasil dikosongkan'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal mengunci file'
        ]);
    }
    fclose($fp);
} else {
    echo json_encode([
        'status' => 'success',
        'message' => 'File memang belum ada, tidak perlu direset'
    ]);
}
?>