<?php
// get_data_far.php
header("Content-Type: application/json");
date_default_timezone_set('Asia/Jakarta');

$file = 'data/antrean_res_' . date('Y-m-d') . '.txt';
$data = [];

if (file_exists($file)) {
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Pola Regex harus sangat akurat
        $pattern = '/No:\s*(.*?)\s*\|\s*Jam:\s*(.*?)\s*\|\s*Nama:\s*(.*?)\s*\|\s*Dari:\s*(.*?)\s*\|\s*Status:\s*(.*)/';
        if (preg_match($pattern, $line, $m)) {
            $data[] = [
                'no_antrean' => trim($m[1]),
                'jam'        => trim($m[2]),
                'nama'       => trim($m[3]),
                'ruangan_asal' => trim($m[4]),
                'status'     => trim($m[5])
            ];
        }
    }
}
echo json_encode(array_reverse($data));