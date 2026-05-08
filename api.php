<?php

$file = "antrian.txt";

// Data Pusher
$app_id = "2146310";
$key = "8b7f969aee7f1ab6ea06";
$secret = "0437e5df64a8e621585d";
$cluster = "ap1";

function triggerPusher($data) {
    global $app_id, $key, $secret, $cluster;
    $channel = "antrian-channel";
    $event = "panggil-event";
    $timestamp = time();
    $body = json_encode(['name' => $event, 'channels' => [$channel], 'data' => json_encode($data)]);
    $auth_query = "auth_key=$key&auth_timestamp=$timestamp&auth_version=1.0&body_md5=" . md5($body);
    $auth_string = "POST\n/apps/$app_id/events\n$auth_query";
    $auth_signature = hash_hmac('sha256', $auth_string, $secret);
    
    $url = "https://api-$cluster.pusher.com/apps/$app_id/events?$auth_query&auth_signature=$auth_signature";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_exec($ch);
    curl_close($ch);
}

if(!file_exists($file)){
    file_put_contents($file,"0|-");
}

$data = file_get_contents($file);
list($nomor,$loket_last) = explode("|",$data);

$action = $_GET['action'] ?? '';

if($action == "next"){
    $loket = $_GET['loket'] ?? 'A';
    $nomor = (int)$nomor + 1;
    file_put_contents($file,$nomor."|".$loket);
    $res = ["nomor" => (string)$nomor, "loket" => $loket, "panggil" => time()];
    triggerPusher($res);
    echo json_encode($res);
    exit;
}

if($action == "before"){
    $loket = $_GET['loket'] ?? 'A';
    $nomor = (int)$nomor - 1;
    if($nomor < 0) $nomor = 0;
    file_put_contents($file, $nomor."|".$loket);
    $res = ["nomor" => (string)$nomor, "loket" => $loket, "panggil" => time()];
    triggerPusher($res);
    echo json_encode($res);
    exit;
}

if($action == "repeat"){
    $loket = $_GET['loket'] ?? 'A';
    $res = ["nomor" => (string)$nomor, "loket" => $loket, "panggil" => time()];
    triggerPusher($res);
    echo json_encode($res);
    exit;
}

if($action == "manual"){
    $nomor_input = $_GET['nomor'] ?? '0';
    $loket = $_GET['loket'] ?? 'A';
    file_put_contents($file, $nomor_input."|".$loket);
    $res = ["nomor" => (string)$nomor_input, "loket" => $loket, "panggil" => time()];
    triggerPusher($res);
    echo json_encode($res);
    exit;
}

if($action == "reset"){
    file_put_contents($file,"0|-");
    $res = ["nomor" => "0", "loket" => "-", "panggil" => time()];
    triggerPusher($res);
    echo json_encode($res);
    exit;
}