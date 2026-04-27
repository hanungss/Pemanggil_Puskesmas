<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Antrean Laboratorium - Puskesmas Tamansari</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-page: #f0f9ff;
            --primary-blue: #0284c7;
            --soft-blue: #e0f2fe;
            --text-dark: #0f172a;
            --accent-green: #10b981;
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
            border: 1px solid var(--soft-blue);
        }

        .logo-puskesmas { height: 50px; }

        .brand-text h1 {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--primary-blue);
            margin: 0;
        }

        .time-section {
            text-align: right;
            border-left: 3px solid var(--soft-blue);
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
            color: var(--primary-blue);
        }

        /* --- SPLIT LAYOUT --- */
        .split-container {
            display: flex;
            flex-grow: 1;
            gap: 15px;
            height: calc(100vh - 180px);
        }

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

        .queue-section {
            flex: 1;
            background: #ffffff;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            border: 1px solid var(--soft-blue);
        }

        .poli-title-bar {
            background: var(--primary-blue);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .poli-title-bar h2 {
            margin: 0; 
            font-size: 1.5rem; 
            font-weight: 900; 
            letter-spacing: 1px;
        }

        .queue-list {
            flex-grow: 1;
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background-color: #fafafa;
        }

        .queue-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #ffffff;
            border: 2px solid var(--soft-blue);
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .queue-number {
            font-size: 2.2rem;
            font-weight: 900;
            color: var(--primary-blue);
            flex: 0 0 110px;
            text-align: center;
            border-right: 3px solid var(--soft-blue);
            margin-right: 15px;
        }

        .patient-info {
            flex: 1;
            padding-left: 10px;
        }

        .patient-name {
            font-size: 1.4rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-dark);
            line-height: 1.1;
        }

        .patient-origin {
            font-size: 0.9rem;
            color: var(--primary-blue);
            font-weight: 600;
        }

        .total-footer {
            background: var(--soft-blue);
            padding: 15px;
            text-align: center;
            font-weight: 800;
            font-size: 1.3rem;
            color: var(--primary-blue);
        }

        .ticker-bar {
            background: #ffffff;
            padding: 10px;
            border-radius: 12px;
            margin-top: 15px;
            border: 1px solid var(--soft-blue);
            display: flex;
            align-items: center;
        }

        .ticker-label {
            background: var(--primary-blue);
            color: white;
            padding: 4px 15px;
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
                <p class="m-0 text-muted fw-bold small"><i class="fas fa-hospital-alt text-success me-1"></i> MONITOR ANTREAN LABORATORIUM</p>
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
                <h2 id="poli-name">UNIT LABORATORIUM</h2>
            </div>
            <div class="queue-list" id="queue-display">
                </div>
            <div class="total-footer" id="total-antrean">
                Menunggu Giliran: 0 Pasien
            </div>
        </div>
    </div>

    <div class="ticker-bar">
        <span class="ticker-label">INFO</span>
        <marquee class="fw-bold text-dark fs-5">
            Selamat Datang di Unit Laboratorium Puskesmas Tamansari • Harap menunggu nomor antrean Anda dipanggil • Pastikan membawa berkas pemeriksaan yang diperlukan.
        </marquee>
    </div>
</div>

<script>
async function updateMonitor() {
    try {
        const response = await fetch('get_data_lab.php');
        const data = await response.json();
        
        // --- LOGIKA FILTER: Hanya ambil yang statusnya 'Proses' ---
        const activeQueue = data.filter(item => item.status === 'Proses');
        
        const container = document.getElementById('queue-display');
        document.getElementById('total-antrean').innerText = `Menunggu Giliran: ${activeQueue.length} Pasien`;

        // Ambil 5 antrean terlama (yang harus segera dipanggil)
        // Karena data dari get_data_lab.php sudah di-reverse (terbaru di atas), 
        // kita ambil slice terakhir atau sesuaikan urutan panggilannya.
        const displayItems = activeQueue.slice(-5).reverse(); 

        let html = '';
        if (displayItems.length > 0) {
            displayItems.forEach((it) => {
                html += `
                    <div class="queue-item">
                        <div class="queue-number">${it.no_antrean}</div>
                        <div class="patient-info">
                            <div class="patient-name">${it.nama}</div>
                            <div class="patient-origin">Mohon Menunggu...</div>
                        </div>
                    </div>`;
            });
        } else {
            html = `
                <div class="text-center mt-5 py-5 opacity-25">
                    <i class="fas fa-users-slash fa-5x mb-3"></i>
                    <h3>ANTREAN KOSONG</h3>
                </div>`;
        }
        
        container.innerHTML = html;
        console.log(`✅ [DEBUG] Monitor Luar diperbarui: ${activeQueue.length} pasien menunggu.`);
    } catch (err) {
        console.error("Gagal mengambil data:", err);
    }
}

// Clock logic
setInterval(() => {
    const now = new Date();
    document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID');
    document.getElementById('date').innerText = now.toLocaleDateString('id-ID', { 
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' 
    }).toUpperCase();
}, 1000);

// Update tiap 5 detik
setInterval(updateMonitor, 5000);
updateMonitor();
</script>

</body>
</html>