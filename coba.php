<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testing Sistem Antrean - Puskesmas Tamansari</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body { 
            background-color: #f8f9fa; 
            font-family: 'Segoe UI', sans-serif; 
        }
        .test-card {
            max-width: 600px;
            margin: 50px auto;
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .header-test {
            background: #1e293b;
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
            text-align: center;
        }
        .btn-test {
            padding: 15px;
            font-size: 1.2rem;
            font-weight: bold;
            border-radius: 10px;
        }
        #log-container {
            font-family: monospace;
            font-size: 0.85rem;
            background: #000;
            color: #00ff00;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card test-card">
        <div class="header-test">
            <h4 class="mb-0"><i class="bi bi-gear-fill me-2"></i>Mode Uji Coba Sistem</h4>
            <p class="small opacity-75 mb-0">Lakukan pengecekan sebelum pelayanan dimulai</p>
        </div>
        <div class="card-body p-4">
            
            <div class="alert alert-info">
                <strong>Data Uji Coba:</strong><br>
                <i class="bi bi-person-fill"></i> Nama: <b>UJI COBA</b><br>
                <i class="bi bi-hash"></i> Nomor: <b>TEST-01</b><br>
                <i class="bi bi-geo-alt-fill"></i> Tujuan: <b>RUANGAN UJI COBA</b>
            </div>

            <div class="d-grid gap-3">
                <button class="btn btn-primary btn-test" onclick="panggilTest()">
                    <i class="bi bi-megaphone-fill me-2"></i> KLIK UNTUK TEST PANGGILAN
                </button>
                
                <a href="index.php" class="btn btn-outline-secondary">
                    <i class="bi bi-house-door me-2"></i> Kembali ke Home
                </a>
            </div>

            <div id="log-container">
                > Sistem siap dilakukan pengujian...
            </div>
        </div>
    </div>
</div>

<script>
    function addLog(message) {
        const log = document.getElementById('log-container');
        const time = new Date().toLocaleTimeString();
        log.innerHTML += `<br> [${time}] ${message}`;
        log.scrollTop = log.scrollHeight;
    }

    function panggilTest() {
        const dataTest = {
            no_antrean: "TEST-01",
            nama: "UJI COBA",
            poli: "RUANGAN UJI COBA"
        };

        addLog("⏳ Mengirim sinyal test ke Pusher...");

        $.ajax({
            url: 'panggil_aksi.php',
            type: 'POST',
            data: dataTest,
            dataType: 'json',
            success: function(response) {
                console.log("Respon Server:", response);
                if(response.status === 'success') {
                    addLog("✅ BERHASIL: Sinyal terkirim ke monitor.");
                } else {
                    addLog("❌ GAGAL: " + (response.pusher_response || "Cek kredensial Pusher"));
                }
            },
            error: function(xhr, status, error) {
                addLog("❌ ERROR KONEKSI: Pastikan Apache jalan dan file panggil_aksi.php ada.");
            }
        });
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>