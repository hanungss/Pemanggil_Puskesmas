<?php
// get_skrining_data.php
header('Content-Type: application/json');
$filename = "data/skrining_" . date('Y-m-d') . ".txt";

$data = [];
if (file_exists($filename)) {
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $cols = explode('|', $line);
        $data[] = [
            'no_antrean' => $cols[0],
            'nama'       => $cols[1],
            'poli'       => $cols[2],
            'jam_datang' => $cols[4],
            'urut'       => $cols[6],
            'status'     => $cols[7] ?? 'MENUNGGU'
        ];
    }
}
echo json_encode($data);