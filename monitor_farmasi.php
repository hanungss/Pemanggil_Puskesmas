<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Antrean Farmasi - Puskesmas Tamansari</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-page: #f0fdf4; /* Hijau sangat muda khas farmasi */
            --primary-green: #059669; /* Hijau Farmasi */
            --soft-green: #d1fae5;
            --text-dark: #064e3b;
            --accent-orange: #f59e0b;
        }

        body, html {
            height: 100%;
            margin: 0;
            background-color: var(--bg-page);
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }

        .main-wrapper {
            height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 15px;
        }

        /* --- HEADER --- */
        .navbar-custom {
            background: #ffffff;
            padding: 12px 25px;
            margin-bottom: 15px;
            border-radius: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            border: 1px solid var(--soft-green);
        }

        .logo-puskesmas { height: 50px; }

        .brand-text h1 {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--primary-green);
            margin: 0;
        }

        .time-section {
            text-align: right;
            border-left: 3px solid var(--soft-green);
            padding-left: 20px;
        }

        #clock { 
            font-size: 2rem; 
            font-weight: 900; 
            color: var(--text-dark);
            line-height: 1;
        }

        #date { 
            font-size: 0.85rem; 
            font-weight: 700; 
            color: var(--primary-green);
        }

        /* --- SPLIT LAYOUT --- */
        .split-container {
            display: flex;
            flex-grow: 1;
            gap: 15px;
            height: calc(100vh - 180px);
        }

        /* VIDEO */
        .video-section {
            flex: 1.3; 
            background: #000;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            position: relative;
            border: 4px solid #ffffff;
        }

        .video-section iframe {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            border: none;
        }

        /* ANTREAN */
        .queue-section {
            flex: 1;
            background: #ffffff;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            border: 1px solid var(--soft-green);
        }

        .poli-title-bar {
            background: var(--primary-green);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .poli-title-bar h2 {
            margin: 0; 
            font-size: 1.5rem; 
            font-weight: 900; 
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .queue-list {
            flex-grow: 1;
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background-color: #f9fafb;
        }

        .queue-item {
            display: flex;
            align-items: center;
            padding: 18px;
            background: #ffffff;
            border: 2px solid var(--soft-green);
            border-radius: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .queue-number {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--primary-green);
            flex: 0 0 120px;
            text-align: center;
            border-right: 4px solid var(--soft-green);
            margin-right: 20px;
        }

        .patient-info {
            flex: 1;
            min-width: 0;
        }

        .patient-name {
            font-size: 1.6rem;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--text-dark);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .status-tag {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--primary-green);
            display: block;
        }

        .total-footer {
            background: var(--soft-green);
            padding: 15px;
            text-align: center;
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--primary-green);
        }

        /* TICKER */
        .ticker-bar {
            background: #ffffff;
            padding: 12px;
            border-radius: 12px;
            margin-top: 15px;
            border: 1px solid var(--soft-green);
            display: flex;
            align-items: center;
        }

        .ticker-label {
            background: var(--primary-green);
            color: white;
            padding: 5px 15px;
            border-radius: 8px;
            font-weight: 900;
            margin-right: 15px;
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <nav class="navbar-custom">
        <div class="d-flex align-items-center gap-3">
            <img src="https://puskesmastamansari.boyolali.go.id/files/setting/thumb/190_115-1773108375-Logo_Puskesmas_Tanpa_Background.png" alt="Logo" class="logo-puskesmas">
            <div class="brand-text">
                <h1>Puskesmas Tamansari</h1>
                <p class="m-0 text-muted fw-bold small"><i class="fas fa-pills text-success me-1"></i> MONITOR PENGAMBILAN OBAT (FARMASI)</p>
            </div>
        </div>
        <div class="time-section">
            <div id="clock">00:00:00</div>
            <div id="date">MEMUAT...</div>
        </div>
    </nav>

    <div class="split-container">
        <div class="video-section">
            <iframe src="https://www.youtube.com/embed/jkS6glRPD_o?autoplay=1&mute=1&loop=1&playlist=jkS6glRPD_o" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>

        <div class="queue-section">
            <div class="poli-title-bar">
                <h2>ANTREAN LOKET FARMASI</h2>
            </div>
            <div class="queue-list" id="queue-display">
                </div>
            <div class="total-footer" id="total-antrean">
                Menunggu: 0 Pasien
            </div>
        </div>
    </div>

    <div class="ticker-bar">
        <span class="ticker-label">INFO</span>
        <marquee class="fw-bold text-dark fs-5">
            Harap siapkan kertas resep atau kartu berobat Anda • Pastikan nama yang dipanggil sesuai dengan identitas Anda • Budayakan antre dengan tertib demi kenyamanan bersama.
        </marquee>
    </div>
</div>

<script>
async function updateMonitor() {
    console.log("🔄 [LOG] Memperbarui antrean luar farmasi...");
    try {
        const response = await fetch('get_data_far.php');
        const data = await response.json();
        
        // --- LOGIKA FILTER: Hanya status "Proses" ---
        const activeQueue = data.filter(item => item.status === 'Proses');
        
        const container = document.getElementById('queue-display');
        document.getElementById('total-antrean').innerText = `Menunggu: ${activeQueue.length} Pasien`;

        // Tampilkan 4-5 antrean terlama (yang harus segera siap)
        // Karena get_data_far.php di-reverse di server, kita ambil bagian akhir lalu balik lagi
        const displayItems = activeQueue.slice(-5).reverse(); 

        let html = '';
        if (displayItems.length > 0) {
            displayItems.forEach((it) => {
                html += `
                    <div class="queue-item">
                        <div class="queue-number">${it.no_antrean}</div>
                        <div class="patient-info">
                            <div class="patient-name">${it.nama}</div>
                            <span class="status-tag"><i class="fas fa-hourglass-half me-1"></i> Sedang Disiapkan</span>
                        </div>
                    </div>`;
            });
        } else {
            html = `
                <div class="text-center mt-5 py-5 opacity-25">
                    <i class="fas fa-check-double fa-5x mb-3" style="color: var(--primary-green)"></i>
                    <h3>SEMUA OBAT SELESAI</h3>
                </div>`;
        }
        
        container.innerHTML = html;
        console.log(`✅ [LOG] Monitor diperbarui. Menampilkan ${displayItems.length} pasien.`);
    } catch (err) {
        console.error("❌ [LOG] Gagal ambil data:", err);
    }
}

// Jam & Tanggal
setInterval(() => {
    const now = new Date();
    document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID');
    document.getElementById('date').innerText = now.toLocaleDateString('id-ID', { 
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' 
    }).toUpperCase();
}, 1000);

// Update data setiap 5 detik
setInterval(updateMonitor, 5000);
updateMonitor();
</script>

</body>
</html>