<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Antrean Split - Puskesmas Tamansari</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-page: #f0f9ff; /* Biru sangat muda */
            --primary-blue: #0284c7; /* Biru Medis */
            --soft-blue: #e0f2fe; /* Biru muda lembut */
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

        .logo-puskesmas { height: 45px; }

        .brand-text h1 {
            font-size: 1.4rem;
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
            font-size: 1.8rem; 
            font-weight: 900; 
            color: var(--text-dark);
            line-height: 1;
        }

        #date { 
            font-size: 0.8rem; 
            font-weight: 600; 
            color: var(--primary-blue);
            text-transform: uppercase;
        }

        /* --- SPLIT LAYOUT --- */
        .split-container {
            display: flex;
            flex-grow: 1;
            gap: 15px;
            height: calc(100vh - 180px);
        }

        /* KIRI: VIDEO */
        .video-section {
            flex: 1.4; 
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

        /* KANAN: ANTREAN */
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
            padding: 18px;
            text-align: center;
        }

        .poli-title-bar h2 {
            margin: 0; 
            font-size: 1.3rem; 
            font-weight: 800; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .queue-list {
            flex-grow: 1;
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background-image: radial-gradient(var(--soft-blue) 0.5px, transparent 0.5px);
            background-size: 15px 15px; /* Motif titik halus khas medis */
        }

        .queue-item {
    display: flex;
    align-items: center;
    padding: 15px; /* Sesuaikan padding agar tidak terlalu sesak */
    background: #ffffff;
    border: 1px solid var(--soft-blue);
    border-radius: 12px;
    margin-bottom: 8px;
    min-height: 80px; /* Memberi ruang jika nama ter-enter ke bawah */
}

.queue-number {
    font-size: 1.9rem;
    font-weight: 900;
    color: var(--primary-blue);
    
    /* KUNCI AGAR GARIS TETAP LURUS */
    flex: 0 0 120px; /* 0=tidak menyusut, 0=tidak melebar, 120px=lebar tetap */
    text-align: center;
    border-right: 3px solid var(--soft-blue);
    margin-right: 20px;
    padding-right: 10px;
}

.patient-info {
    flex: 1; /* Mengambil sisa ruang yang tersedia */
    min-width: 0; /* Penting agar text-wrap bekerja di dalam flexbox */
}

.patient-name {
    font-size: 1.6rem;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--text-dark);
    
    /* KUNCI AGAR NAMA OTOMATIS TER-ENTER */
    white-space: normal; /* Mengizinkan teks turun ke bawah */
    word-wrap: break-word; /* Memutus kata yang terlalu panjang */
    line-height: 1.2;
    display: block;
}

        .total-footer {
            background: var(--soft-blue);
            padding: 15px;
            text-align: center;
            font-weight: 800;
            font-size: 1.2rem;
            color: var(--primary-blue);
        }

        /* TICKER */
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
            padding: 2px 12px;
            border-radius: 6px;
            font-weight: 800;
            margin-right: 15px;
            font-size: 0.8rem;
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
                <p class="m-0 text-muted small fw-bold"><i class="fas fa-map-marker-alt text-danger"></i>Bendosari, Karangkendal, Tamansari, Boyolali</p>
            </div>
        </div>
        <div class="time-section">
            <div id="clock">00:00:00</div>
            <div id="date">MEMUAT...</div>
        </div>
    </nav>

    <div class="split-container">
        <div class="video-section">
            <iframe src="https://www.youtube.com/embed/jkS6glRPD_o?list=PLp4_ZpNRrQxoE1ylrekJSFm5KUG3KajdX&autoplay=1&mute=1&loop=1&playlist=jkS6glRPD_o" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>

        <div class="queue-section">
            <div class="poli-title-bar">
                <h2 id="poli-name">MEMUAT UNIT...</h2>
            </div>
            <div class="queue-list" id="queue-display">
                </div>
            <div class="total-footer" id="total-antrean">
                Antrean Tersisa: 0 Pasien
            </div>
        </div>
    </div>

    <div class="ticker-bar">
        <span class="ticker-label">INFO</span>
        <marquee class="fw-bold text-secondary">
            Gunakan masker saat berada di area Puskesmas • Jaga jarak aman dan tertib mengantre • Terima kasih telah mempercayakan kesehatan Anda kepada kami.
        </marquee>
    </div>
</div>

<script>
// KONFIGURASI KLASTER: Sesuaikan dengan nama poli di database Anda
const TARGET_POLI = "KIA"; 

async function updateMonitor() {
    try {
        const response = await fetch('get_data.php');
        const data = await response.json();
        
        // Filter Klaster
        const filteredData = data.filter(item => {
            let currentPoli = item.poli;
            if (currentPoli.includes("KIA")) currentPoli = "KIA";
            
            if (currentPoli.includes("K3")) {
                if (item.dokter === "ENDAH PUJIATININGSIH") {
                    return TARGET_POLI === "K3 - USIA DEWASA & LANSIA BP UTARA";
                } else {
                    return TARGET_POLI === "K3 - USIA DEWASA & LANSIA BP SELATAN";
                }
            }
            return currentPoli === TARGET_POLI;
        });

        const displayItems = filteredData.slice(0, 4); // Ambil 5 teratas
        const container = document.getElementById('queue-display');
        document.getElementById('poli-name').innerText = TARGET_POLI;
        document.getElementById('total-antrean').innerText = `Antrean Tersisa: ${filteredData.length} Pasien`;

        let html = '';
        if (displayItems.length > 0) {
            displayItems.forEach((it) => {
                html += `
                    <div class="queue-item">
                        <div class="queue-number">${it.no_antrean}</div>
                        <div class="patient-info">
                            <div class="patient-name">${it.nama}</div>
                        </div>
                    </div>`;
            });
        } else {
            html = '<div class="text-center mt-5 py-5 opacity-25"><h3>BELUM ADA ANTREAN</h3></div>';
        }
        
        container.innerHTML = html;
    } catch (err) {
        console.error("Gagal mengambil data:", err);
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

// Update Otomatis per 5 detik
setInterval(updateMonitor, 5000);
updateMonitor();
</script>

</body>
</html>