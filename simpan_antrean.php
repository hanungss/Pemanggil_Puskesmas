<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

$json = file_get_contents('php://input');

$data = json_decode($json, true);

if (!$data || !is_array($data)) {
    echo json_encode([
        "status" => "error",
        "message" => "JSON tidak valid"
    ]);
    exit;
}

$folder = 'data';
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

$filename = $folder . "/antrean_" . date('Y-m-d') . ".txt";

// Simpan JSON yang sudah rapi
file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));

echo json_encode([
    "status" => "success",
    "total" => count($data)
]);

