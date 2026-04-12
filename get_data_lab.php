<?php
header("Content-Type: application/json");

$tanggal = date('Y-m-d');
$filename = "data/antrean_lab_$tanggal.txt";

if (!file_exists($filename)) {
    echo json_encode([]);
    exit;
}

$content = file_get_contents($filename);
$data = json_decode($content, true);

if (!$data) {
    echo json_encode([]);
    exit;
}

echo json_encode($data);