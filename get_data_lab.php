<?php
// get_data_lab.php
header("Content-Type: application/json");
date_default_timezone_set('Asia/Jakarta');

$file = 'data/antrean_lab_' . date('Y-m-d') . '.txt';
$data = [];

if (file_exists($file)) {
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Regex untuk pecah data berdasarkan separator '|'
        if (preg_match('/No:\s*(.*?)\s*\|\s*Jam:\s*(.*?)\s*\|\s*Nama:\s*(.*?)\s*\|\s*Dari:\s*(.*?)\s*\|\s*Status:\s*(.*)/', $line, $m)) {
            $data[] = [
                'no_antrean' => $m[1],
                'jam' => $m[2],
                'nama' => $m[3],
                'ruangan_asal' => $m[4],
                'status' => $m[5]
            ];
        }
    }
}
echo json_encode($data);
?>