<?php
// get_confirmed_list.php
header('Content-Type: application/json');
$filename = "data/skrining_" . date('Y-m-d') . ".txt";

$confirmed = [];
if (file_exists($filename)) {
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $cols = explode('|', $line);
        // Index 0: no_antrean, Index 6: nomor urut skrining
        $confirmed[$cols[0]] = ['urut' => $cols[6]];
    }
}
echo json_encode($confirmed);