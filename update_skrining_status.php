<?php
// update_skrining_status.php
header('Content-Type: application/json');
$filename = "data/skrining_" . date('Y-m-d') . ".txt";
$no_antrean = $_POST['no_antrean'] ?? '';

if (!$no_antrean) exit;

$fp = fopen($filename, 'r+');
if (flock($fp, LOCK_EX)) {
    $lines = [];
    while (($line = fgets($fp)) !== false) {
        $cols = explode('|', trim($line));
        // Jika nomor cocok, tambahkan status SELESAI di kolom index ke-7
        if ($cols[0] === $no_antrean) {
            $cols[7] = 'SELESAI';
            $line = implode('|', $cols) . PHP_EOL;
        }
        $lines[] = $line;
    }

    ftruncate($fp, 0);
    rewind($fp);
    foreach ($lines as $l) fwrite($fp, $l);
    
    flock($fp, LOCK_UN);
    echo json_encode(['status' => 'success']);
}
fclose($fp);