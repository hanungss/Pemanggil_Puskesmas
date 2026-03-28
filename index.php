<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Antrean - Puskesmas Tamansari</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #ffffffff;
            --card-bg: #1e293b;
            --accent-blue: #ffffffff;
            --accent-green: #22c55e;
            --text-main: #f8fafc;
            --info-orange: #f97316; /* Define orange accent */
        }

        /* Full Screen No Scroll */
        body, html {
            height: 100%;
            margin: 0;
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            overflow: hidden; /* Mengunci layar agar tidak scroll */
        }

        .main-wrapper {
            height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 10px;
        }

        /* Compact Header */
        .navbar-custom {
            background: linear-gradient(90deg, #1e293b 0%, #334155 100%);
            border-bottom: 2px solid var(--accent-blue);
            padding: 5px 15px;
            margin-bottom: 10px;
            border-radius: 8px;
        }

        /* Ticker Bar (Replacement for Video) */
        .ticker-bar {
            background: #fed7aa; /* Pale orange background */
            padding: 8px 15px;
            border-radius: 10px;
            border: 1px solid var(--info-orange);
            margin-bottom: 15px;
        }

        .ticker-info-badge {
            background-color: var(--info-orange);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: -0.5px;
            display: inline-block;
            margin-right: 15px;
        }

        .ticker-text {
            color: #9a3412; /* Darker orange text */
            font-weight: 700;
            font-size: 1.1rem;
        }

        /* Content Area */
        .content-area {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* Grid System untuk Poli */
        #display-antrean {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 Kolom */
            grid-template-rows: repeat(2, 1fr);    /* 2 Baris */
            gap: 10px;
            flex-grow: 1;
        }

        /* Card Styling */
        .poli-card {
            background: var(--card-bg);
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(255,255,255,0.1);
            overflow: hidden;
        }

        .poli-header {
            background: rgba(56, 189, 248, 0.15);
            padding: 8px;
            text-align: center;
            border-bottom: 2px solid var(--accent-blue);
        }

        .poli-header span {
            font-weight: 800;
            font-size: 0.85rem;
            text-transform: uppercase;
            color: var(--accent-blue);
        }

        .queue-list {
            flex-grow: 1;
            padding: 5px;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
        }

        .queue-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 4px 10px;
            background: rgba(255,255,255,0.03);
            border-radius: 6px;
            margin-bottom: 2px;
        }

        .queue-number {
            font-weight: 900;
            font-size: 1.4rem;
            color: var(--accent-green);
            min-width: 60px;
        }

        .patient-info {
            text-align: right;
            overflow: hidden;
        }

        .patient-name {
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }

        .patient-time {
            font-size: 0.7rem;
            color: #94a3b8;
        }

        .empty-state {
            color: #475569;
            font-style: italic;
            font-size: 0.8rem;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <nav class="navbar-custom d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h5 mb-0 fw-bold">Puskesmas Tamansari Kabupaten Boyolali</h1>
            <small style="font-size: 0.7rem;"><i class="fas fa-circle text-danger"></i> Jl Musuk Karanganyar KM6 Bendosari, RT.21 RW3, Karangkendal, Kecamatan Tamansari, Kabupaten Boyolali, Jawa Tengah 57361</small>
        </div>
        <div class="text-end">
            <div id="clock" class="fw-bold" style="font-size: 1rem;"></div>
            <div id="date" style="font-size: 0.7rem; color: #94a3b8;"></div>
        </div>
    </nav>

    <div class="content-area">
        <div class="ticker-bar d-flex align-items-center">
            <span class="ticker-info-badge">📢 Info</span>
            <marquee class="ticker-text">Selamat datang di Puskesmas Tamansari • Mohon menunggu antrian dengan tertib • Silahkan menunggu nama anda dipanggil.</marquee>
        </div>

        <div id="display-antrean">
            </div>
    </div>
</div>

<script>
const poliIcons = {
    "LINTAS KLUSTER - PELAYANAN GIGI": "fa-tooth",
    "KIA": "fa-baby-carriage",
    "K3 - USIA DEWASA & LANSIA BP SELATAN": "fa-stethoscope",
    "K3 - USIA DEWASA & LANSIA BP UTARA": "fa-user-md",
    "LINTAS KLUSTER - GAWAT DARURAT": "fa-ambulance",
    "FISIOTERAPI": "fa-wheelchair"
};

async function updateMonitor() {
    try {
        const response = await fetch('get_data.php');
        const data = await response.json();
        const container = document.getElementById('display-antrean');

        const categories = Object.keys(poliIcons);
        const grouped = {};
        categories.forEach(cat => grouped[cat] = []);

        data.forEach(item => {
            if (item.poli.includes("K3")) {
                if (item.no_antrean.startsWith("DC-")) grouped["K3 - USIA DEWASA & LANSIA BP SELATAN"].push(item);
                else if (item.no_antrean.startsWith("DD-")) grouped["K3 - USIA DEWASA & LANSIA BP UTARA"].push(item);
            } else if (grouped[item.poli]) {
                grouped[item.poli].push(item);
            }
        });

        let html = '';
        categories.forEach(poli => {
            const icon = poliIcons[poli];
            const items = grouped[poli].slice(0, 3); // AMBIL 3 NAMA SAJA

            html += `
                <div class="poli-card">
                    <div class="poli-header">
                        <span><i class="fas ${icon} me-2"></i>${poli}</span>
                    </div>
                    <div class="queue-list">
            `;

            if (items.length > 0) {
                items.forEach(it => {
                    html += `
                        <div class="queue-item">
                            <div class="queue-number">${it.no_antrean}</div>
                            <div class="patient-info">
                                <span class="patient-name text-uppercase">${it.nama}</span>
                                <span class="patient-time">${it.jam}</span>
                            </div>
                        </div>
                    `;
                });
            } else {
                html += `<div class="empty-state">Menunggu Antrean...</div>`;
            }

            html += `</div></div>`;
        });

        container.innerHTML = html;
    } catch (err) {
        console.error("Fetch error:", err);
    }
}

// Clock & Date
setInterval(() => {
    const now = new Date();
    document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID');
    document.getElementById('date').innerText = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
}, 1000);

setInterval(updateMonitor, 5000);
updateMonitor();
</script>
</body>
</html>