<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Antrean - Puskesmas Tamansari</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --dark-bg: #2c3e50;
            --health-green: #2ecc71;
            --accent-yellow: #f1c40f;
            --light-bg: #ffffff;
        }

        body { 
            background-color: var(--dark-bg); 
            font-family: 'Segoe UI', sans-serif; 
            margin: 0;
        }

        /* HEADER SECTION */
        .header-container {
            padding: 40px 20px 30px 20px;
            text-align: center;
            color: white;
            border-bottom: 8px solid var(--health-green);
        }

        .header-container h1 {
            font-size: 2.5rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .clock-capsule {
            background: rgba(255, 255, 255, 0.1);
            display: inline-block;
            padding: 10px 30px;
            border-radius: 50px;
            margin: 15px 0;
        }

        #clock {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--accent-yellow);
            display: block;
        }

        .info-alert {
            max-width: 850px;
            margin: 10px auto;
            background: rgba(0, 0, 0, 0.2);
            padding: 15px;
            border-radius: 12px;
            border-left: 5px solid var(--accent-yellow);
        }

        /* TABLE AREA */
        .main-content {
            background: var(--light-bg);
            padding: 30px;
            min-height: 100vh;
        }

        .badge-number {
            background: var(--health-green) !important;
            font-size: 1.2rem;
            padding: 8px 15px;
            font-weight: 800;
            color: white;
        }

        .patient-name-col {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark-bg);
        }

        /* BUTTONS */
        .btn-call-group {
            display: flex;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .btn-main-call {
            background: var(--health-green);
            color: white;
            border: none;
            padding: 10px 20px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-main-call:hover { background: #27ae60; }

        .btn-side-call {
            background: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            transition: 0.2s;
        }

        .btn-side-call:hover { background: #2980b9; }
    </style>
</head>
<body>

<div class="header-container">
    <h1>Monitor Antrean Pasien Klaster</h1>
    <h2 class="h4 fw-light opacity-75">Puskesmas Tamansari Kabupaten Boyolali</h2>
    
    <div class="clock-capsule shadow-sm">
        <span id="clock">00:00:00</span>
        <small id="date-text" class="text-white-50 d-block mt-1"></small>
    </div>

    <div class="info-alert shadow-sm">
        <h6 class="mb-0 lh-base">
            <i class="fas fa-info-circle text-warning me-2"></i>
            Memanggil pasien yang <strong class="text-danger">BELUM DIPERIKSA</strong>. 
            Terhubung otomatis dengan <span class="badge bg-light text-dark">EPUSKESMAS</span>
            <span class="d-block mt-2 text-white-50 small fw-light">
                Selesaikan pemeriksaan di Epus agar data diperbarui secara otomatis. Daftar nama pasien akan otomatis hilang ketika pasien sudah diperiksa.
            </span>
        </h6>
    </div>
</div>

<div class="main-content">
    <div class="container-fluid">
        <div class="row g-3 mb-4">
            <div class="col-md-7">
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-success"></i></span>
                    <input type="text" id="searchInput" class="form-control form-control-lg border-start-0" placeholder="Cari nama pasien atau nomor antrean...">
                </div>
            </div>
            <div class="col-md-5">
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-filter text-success"></i></span>
                    <select id="filterPoli" class="form-select form-select-lg border-start-0">
                        <option value="">Semua Poli (Tampilkan Semua)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="table-responsive shadow-sm rounded border">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th>NO</th>
                        <th>NO. ANTREAN</th>
                        <th>NAMA PASIEN</th>
                        <th>POLI TUJUAN</th>
                        <th>JAM BERKUNJUNG</th>
                        <th>AKSI PANGGILAN</th>
                    </tr>
                </thead>
                <tbody id="display-antrean">
                    </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let allData = [];

    // Logika Pemisahan Poli & Penyatuan KIA
    function getDisplayPoli(item) {
        if (!item.poli) return "-";

        // 1. Logika Penyatuan KIA & KIA - promprev
        if (item.poli.includes("KIA")) {
            return "KIA";
        }

        // 2. Logika Pemisahan K3 (Utara/Selatan)
        if (item.poli.includes("K3")) {
            if (item.dokter === "ENDAH PUJIATININGSIH") return "K3 - USIA DEWASA BP UTARA";
            if (item.dokter === "MAGHFUR ARROZY") return "K3 - USIA LANSIA BP SELATAN";
        }

        return item.poli;
    }

    async function fetchData() {
        try {
            const response = await fetch('get_data.php');
            const data = await response.json();
            allData = data;
            
            // Update Opsi Filter
            const selectPoli = document.getElementById('filterPoli');
            const currentSelection = selectPoli.value;
            const polis = [...new Set(data.map(item => getDisplayPoli(item)))].sort();
            
            let options = '<option value="">Semua Poli (Tampilkan Semua)</option>';
            polis.forEach(p => options += `<option value="${p}" ${p === currentSelection ? 'selected' : ''}>${p}</option>`);
            if (selectPoli.innerHTML !== options) selectPoli.innerHTML = options;

            renderTable();
        } catch (e) { console.error("Gagal mengambil data:", e); }
    }

    function renderTable() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const filter = document.getElementById('filterPoli').value;
        const tbody = document.getElementById('display-antrean');

        const filtered = allData.filter(item => {
            const p = getDisplayPoli(item);
            return (item.nama.toLowerCase().includes(search) || item.no_antrean.toLowerCase().includes(search)) &&
                   (filter === "" || p === filter);
        });

        tbody.innerHTML = filtered.map((item, index) => {
            const p = getDisplayPoli(item);
            const namaSafe = item.nama.replace(/'/g, "\\'"); 

            return `
                <tr>
                    <td class="text-center fw-bold text-muted">${index + 1}</td>
                    <td class="text-center"><span class="badge badge-number">${item.no_antrean}</span></td>
                    <td class="patient-name-col text-uppercase">${index + 1}. ${item.nama}</td>
                    <td class="text-danger fw-bold"><i class="bi bi-geo-alt-fill me-2"></i>${p}</td>
                    <td class="text-center text-muted small fw-bold">${item.jam}</td>
                    <td class="text-center">
                        <div class="btn-call-group d-inline-flex">
                            <button class="btn-main-call" onclick="panggilSuara('${item.no_antrean}','${namaSafe}','${p}')">
                                <i class="bi bi-megaphone-fill me-2"></i>Panggil
                            </button>
                            <button class="btn-side-call" onclick="panggilSuara('${item.no_antrean}','${namaSafe}','${p}')" title="Ulangi">
                                <i class="bi bi-arrow-repeat me-2"></i>Ulangi
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function panggilSuara(nomor, nama, poli) {
        if (!('speechSynthesis' in window)) {
            console.error("Browser tidak mendukung Text-to-Speech");
            return;
        }

        window.speechSynthesis.cancel();

        const poliNatural = poli.toLowerCase()
            .replace('&', 'dan')
            .replace('-', ' ')
            .replace('pelayanan', ''); 

        const pesan = `Pasien atas nama ${nama.toLowerCase()}. Silakan menuju ${poliNatural}`;
        const utterance = new SpeechSynthesisUtterance(pesan);
        
        utterance.lang = 'id-ID'; 
        utterance.rate = 1.0; 
        utterance.pitch = 1.0; 

        window.speechSynthesis.speak(utterance);
    }

    setInterval(() => {
        const now = new Date();
        const clockEl = document.getElementById('clock');
        const dateEl = document.getElementById('date-text');
        if(clockEl) clockEl.innerText = now.toLocaleTimeString('id-ID');
        if(dateEl) dateEl.innerText = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    }, 1000);

    document.getElementById('searchInput').addEventListener('input', renderTable);
    document.getElementById('filterPoli').addEventListener('change', renderTable);

    setInterval(fetchData, 5000);
    fetchData();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>