<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Antrean Modern - Puskesmas Tamansari</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="https://puskesmastamansari.boyolali.go.id/files/setting/thumb/190_115-1773108375-Logo_Puskesmas_Tanpa_Background.png">
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <style>
        :root {
            --bg-body: #f1f5f9;
            --card-bg: #dae7fd;
            --accent-blue: #1e293b;
            --accent-green: #22c55e;
            --text-main: #1e293b;
            --info-orange: #f97316;
            --glass-white: rgba(255, 255, 255, 0.85);
        }

        body, html {
            height: 100%;
            margin: 0;
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }

        .main-wrapper {
            height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 12px;
        }

        #speaker-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(30, 41, 59, 0.98);
            display: flex; flex-direction: column;
            justify-content: center; align-items: center;
            z-index: 9999; color: white;
        }
        .btn-activate {
            background: var(--accent-green); color: white; border: none;
            padding: 20px 40px; border-radius: 50px; font-size: 1.5rem;
            font-weight: 800; cursor: pointer; box-shadow: 0 10px 20px rgba(34, 197, 94, 0.4);
            display: flex; align-items: center; gap: 15px;
        }

        .navbar-custom {
            background: var(--glass-white);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding: 10px 25px;
            margin-bottom: 12px;
            border-radius: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand-section { display: flex; align-items: center; gap: 15px; }
        .logo-puskesmas { height: 50px; width: auto; }
        .brand-text h1 { font-size: 1.3rem; font-weight: 900; margin: 0; color: var(--accent-blue); }
        .brand-text small { display: block; color: #64748b; font-size: 0.75rem; }

        .time-section {
            background: var(--accent-blue);
            color: white;
            padding: 8px 20px;
            border-radius: 12px;
            text-align: center;
        }

        #clock { font-size: 1.4rem; font-weight: 800; line-height: 1; }
        #date { font-size: 0.65rem; text-transform: uppercase; margin-top: 2px; }

        .ticker-bar {
            background: #fed7aa;
            padding: 8px 15px;
            border-radius: 10px;
            border: 1px solid var(--info-orange);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }

        .ticker-info-badge {
            background-color: var(--info-orange);
            color: white;
            padding: 2px 10px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 800;
            margin-right: 15px;
        }

        #display-antrean {
        display: grid;
        grid-template-columns: 1fr 1fr; /* Membagi 2 kolom sama besar (50:50) */
        gap: 15px;
        flex-grow: 1;
        align-items: start;
    }

        .poli-card {
        background: var(--card-bg);
        border-radius: 15px;
        display: flex;
        flex-direction: column;
        border: 2px solid rgba(30, 41, 59, 0.1);
        overflow: hidden;
        height: 100%; /* Agar tinggi kartu menyesuaikan layar */
    }

        .poli-header {
        background: var(--accent-blue);
        padding: 15px;
        text-align: center;
    }

    .poli-header span {
        font-size: 1.2rem; /* Ukuran judul poli lebih besar */
        color: white;
        font-weight: 900;
    }

        .queue-list { padding: 8px; display: flex; flex-direction: column; gap: 8px; }

        .queue-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .queue-number {
        font-size: 2.5rem; 
        font-weight: 900;
        color: var(--accent-green);
    }

        .patient-info { text-align: right; max-width: 65%; }
        .patient-name {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--accent-blue);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .total-antrean-footer {
            background: rgba(255, 255, 255, 0.5);
            padding: 10px;
            text-align: center;
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--accent-blue);
            border-top: 1px dashed rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div id="speaker-overlay">
    <i class="fas fa-volume-up fa-4x mb-4 text-success"></i>
    <h2 class="mb-4">SISTEM SUARA ANTREAN</h2>
    <button class="btn-activate" onclick="activateSpeaker()">
        <i class="fas fa-power-off"></i> AKTIFKAN SPEAKER
    </button>
</div>

<div class="main-wrapper">
    <nav class="navbar-custom">
        <div class="brand-section">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Seal_of_Boyolali_Regency.svg/120px-Seal_of_Boyolali_Regency.svg.png" alt="Logo" class="logo-puskesmas">
            <img src="https://puskesmastamansari.boyolali.go.id/files/setting/thumb/190_115-1773108375-Logo_Puskesmas_Tanpa_Background.png" alt="Logo" class="logo-puskesmas">
            <div class="brand-text">
                <h1>Puskesmas Tamansari Kabupaten Boyolali</h1>
                <small><i class="fas fa-map-marker-alt text-danger me-1"></i> Jl. Musuk Karanganyar KM6 Bendosari, Tamansari, Boyolali</small>
            </div>
        </div>
        <div class="time-section">
            <div id="clock">00.00.00</div>
            <div id="date">MEMUAT...</div>
        </div>
    </nav>

    <div class="ticker-bar">
        <span class="ticker-info-badge">📢 Info</span>
        <marquee class="ticker-text">Selamat datang di Puskesmas Tamansari • Mohon mengantre dengan tertib • Silakan menunggu nama Anda dipanggil petugas.</marquee>
    </div>

    <div id="display-antrean"></div>
</div>

<script>
let isSpeakerActive = false;

function activateSpeaker() {
    const msg = new SpeechSynthesisUtterance("Sistem suara antrean telah aktif");
    msg.lang = 'id-ID';
    window.speechSynthesis.speak(msg);
    document.getElementById('speaker-overlay').style.display = 'none';
    isSpeakerActive = true;
}

const poliIcons = {
    "KIA": "fa-baby-carriage",
    "LAB": "fa-flask"
};

async function updateMonitor() {
    try {
        // 1. Ambil Data KIA
        const resKia = await fetch('get_data.php');
        const dataKiaRaw = await resKia.json();
        
        // 2. Ambil Data LAB
        let dataLabRaw = [];
        try {
            const resLab = await fetch('get_data_lab.php');
            dataLabRaw = await resLab.json();
        } catch (e) { console.error("Gagal ambil data LAB", e); }

        // Grouping Data
        const grouped = {
            "KIA": dataKiaRaw.filter(item => item.poli && item.poli.includes("KIA")),
            "LAB": dataLabRaw.filter(item => item.status === 'Proses')
        };

        const container = document.getElementById('display-antrean');
        let html = '';

        Object.keys(poliIcons).forEach(poli => {
            const icon = poliIcons[poli];
            const list = grouped[poli] || [];
            const displayItems = list.slice(0, 4);
            const totalCount = list.length;

            let itemsHtml = '';
            if (displayItems.length > 0) {
                displayItems.forEach(it => {
                    const subInfo = poli === "LAB" ? (it.ruangan_asal || "Laboratorium") : (it.jam || "");
                    itemsHtml += `
                        <div class="queue-item">
                            <div class="queue-number">${it.no_antrean}</div>
                            <div class="patient-info">
                                <span class="patient-name text-uppercase">${it.nama}</span>
                                <div class="small text-muted" style="font-size:0.7rem">${subInfo}</div>
                            </div>
                        </div>`;
                });
            } else {
                itemsHtml = `<div class="text-center py-4 opacity-50 small"><i>Antrean Kosong</i></div>`;
            }

            html += `
                <div class="poli-card shadow-sm">
                    <div class="poli-header">
                        <span><i class="fas ${icon} me-2 text-info"></i>ANTREAN ${poli}</span>
                    </div>
                    <div class="queue-list">
                        ${itemsHtml}
                    </div>
                    <div class="total-antrean-footer">
                        Total Menunggu: ${totalCount} Pasien
                    </div>
                </div>`;
        });

        container.innerHTML = html;
    } catch (err) {
        console.error("Fetch error:", err);
    }
}

// Clock
setInterval(() => {
    const now = new Date();
    document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID').replace(/:/g, '.');
    document.getElementById('date').innerText = now.toLocaleDateString('id-ID', { 
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
    }).toUpperCase();
}, 1000);

// --- LOGIKA PUSHER LISTENER ---
const pusher = new Pusher('8b7f969aee7f1ab6ea06', {
    cluster: 'ap1'
});

const channel = pusher.subscribe('antrean-channel');

channel.bind('panggil-event', function(data) {
    if (isSpeakerActive) {
        // Hentikan suara TTS yang sedang berjalan agar tidak tumpang tindih
        window.speechSynthesis.cancel();

        // Normalisasi nama poli agar enak didengar
        const poliNatural = data.poli.toLowerCase()
            .replace('&', 'dan')
            .replace('-', ' ')
            .replace('pelayanan', '');

        // FORMAT PESAN SESUAI PERMINTAAN
        const pesan = `Pasien atas nama ${data.nama.toLowerCase()}. Silakan menuju ${poliNatural}`;
        
        // 1. Definisikan suara bel
        const bell = new Audio('suara/panggilan.mp3');

        // 2. Jalankan Google TTS HANYA setelah bel selesai berbunyi
        bell.onended = () => {
            const utterance = new SpeechSynthesisUtterance(pesan);
            
            utterance.lang = 'id-ID'; 
            utterance.rate = 1.0; 
            utterance.pitch = 1.0; 

            window.speechSynthesis.speak(utterance);
        };

        // 3. Putar suara bel terlebih dahulu
        bell.play().catch(e => console.log("Audio play blocked by browser:", e));
    }
});

setInterval(updateMonitor, 5000);
updateMonitor();
</script>
</body>
</html>