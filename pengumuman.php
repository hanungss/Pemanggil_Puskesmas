<?php
// --- LOGIKA PENGIRIMAN PUSHER (Server-Side) ---
if (isset($_POST['trigger_panggilan'])) {
    $app_id  = "2146310"; 
    $key     = "8b7f969aee7f1ab6ea06"; 
    $secret  = "0437e5df64a8e621585d"; 
    $cluster = "ap1";

    $channel = "antrean-channel";
    $event   = "panggil-event";
    $pesan_user = $_POST['pesan_custom'] ?? "";

    // Struktur Data Antrean
    $isi_data = [
        'no_antrean' => "INFO",
        'nama'       => "PENGUMUMAN",
        'poli'       => "PUSKESMAS",
        'pesan'      => $pesan_user
    ];

    // PERBAIKAN: Payload Pusher harus menyertakan 'name', 'channels', dan 'data'
    $payload = json_encode([
        'name'     => $event,
        'channels' => [$channel],
        'data'     => json_encode($isi_data) // Data di dalam data harus di-string-kan lagi sesuai spek Pusher
    ]);

    $path = "/apps/$app_id/events";
    $timestamp = time();
    $auth_version = "1.0";

    $body_md5 = md5($payload);
    $auth_query = "auth_key=$key&auth_timestamp=$timestamp&auth_version=$auth_version&body_md5=$body_md5";
    $auth_string = "POST\n$path\n$auth_query";
    $auth_signature = hash_hmac('sha256', $auth_string, $secret);

    $url = "https://api-$cluster.pusher.com$path?$auth_query&auth_signature=$auth_signature";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload)
    ]);
    
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    header('Content-Type: application/json');
    if ($http_code == 200) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode([
            'status' => 'error', 
            'message' => "HTTP $http_code | Respon: $result"
        ]);
    }
    exit; 
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Broadcast Puskesmas Tamansari</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            background: linear-gradient(135deg, #e0f2fe, #f8fafc);
            font-family: 'Segoe UI', sans-serif;
        }

        .main-card {
            max-width: 650px;
            margin: 60px auto;
            border-radius: 20px;
            border: none;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        }

        .header-bg {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            color: white;
            padding: 30px;
        }

        .header-bg h4 {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        textarea {
            border-radius: 12px !important;
            resize: none;
            padding: 15px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        textarea:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14,165,233,0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            border: none;
            border-radius: 12px;
            transition: 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37,99,235,0.2);
        }

        #log-window {
            background: #020617;
            color: #ffffffff;
            font-family: monospace;
            font-size: 0.85rem;
            padding: 15px;
            border-radius: 12px;
            margin-top: 20px;
            height: 160px;
            overflow-y: auto;
            border: 1px solid #1e293b;
        }

        .label-title {
            font-weight: 600;
            color: #334155;
        }

        .footer-link {
            text-decoration: none;
            font-size: 0.85rem;
        }

        .footer-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="card main-card">

        <div class="header-bg text-center">
            <h4 class="mb-1">
                <i class="bi bi-megaphone-fill me-2"></i>
                Pusat Pengumuman
            </h4>
            <small>Sistem Broadcast Puskesmas Tamansari</small>
        </div>

        <div class="card-body p-4">

            <div class="mb-3">
                <label class="form-label label-title">
                    <i class="bi bi-chat-left-text me-1"></i> Isi Pesan
                </label>
                <textarea id="pesan_custom" class="form-control" rows="4">
Mohon perhatian, kepada seluruh Bapak dan Ibu staf Puskesmas Tamansari, dimohon segera berkumpul di halaman depan karena apel pagi akan segera dilaksanakan. Terima kasih.
                </textarea>
            </div>

            <div class="d-grid gap-2">
                <button class="btn btn-primary p-3 fw-semibold" onclick="kirimSiaran()">
                    <i class="bi bi-broadcast me-2"></i>
                    Kirim Pengumuman Sekarang
                </button>

                <a href="index.php" class="text-center text-muted footer-link">
                    <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>

            <div id="log-window">> Sistem siap digunakan...</div>

        </div>
    </div>
</div>

<script>
    function addLog(msg, color = '#38bdf8') {
        const log = document.getElementById('log-window');
        log.innerHTML += `<div style="color:${color}">[${new Date().toLocaleTimeString()}] ${msg}</div>`;
        log.scrollTop = log.scrollHeight;
    }

    function kirimSiaran() {
        const pesan = $('#pesan_custom').val();

        addLog("⏳ Mengirim pengumuman...");

        $.ajax({
            url: window.location.href,
            type: 'POST',
            data: { trigger_panggilan: true, pesan_custom: pesan },
            dataType: 'json',
            success: function(res) {
                if(res.status === 'success') {
                    addLog("✅ Pengumuman berhasil dikirim", "#22c55e");
                } else {
                    addLog("❌ Gagal: " + res.message, "#ef4444");
                }
            },
            error: function() {
                addLog("❌ Error koneksi ke server", "#ef4444");
            }
        });
    }
</script>

</body>
</html>