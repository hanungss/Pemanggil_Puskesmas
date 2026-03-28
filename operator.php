<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Antrean Puskesmas Tamansari</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .header-panel { background: #2c3e50; color: white; padding: 20px; text-align: center; margin-bottom: 30px; border-bottom: 5px solid #27ae60; }
        .table-container { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .table thead { background-color: #27ae60; color: white; }
        .badge-antrean { font-size: 1rem; padding: 8px 12px; }
        .btn-call { background-color: #2ecc71; color: white; border: none; transition: 0.2s; }
        .btn-call:hover { background-color: #27ae60; color: white; transform: scale(1.05); }
        .btn-recall { background-color: #3498db; color: white; border: none; transition: 0.2s; }
        .btn-recall:hover { background-color: #2980b9; color: white; transform: scale(1.05); }
        #clock { font-weight: 300; letter-spacing: 1px; }
    </style>
</head>
<body>

<div class="header-panel">
    <h1 class="fw-bold">MONITOR ANTREAN PASIEN</h1>
    <h4 id="clock">Memuat waktu...</h4>
</div>

<div class="container-fluid px-4">
    <div class="table-container">
        <div class="row mb-4 g-3">
            <div class="col-md-7">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchInput" class="form-control form-control-lg" placeholder="Cari nama pasien atau nomor antrean...">
                </div>
            </div>
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-filter"></i></span>
                    <select id="filterPoli" class="form-select form-select-lg">
                        <option value="">Semua Poli (Tampilkan Semua)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle border">
                <thead class="text-center">
                    <tr>
                        <th width="5%">No</th>
                        <th width="12%">No. Antrean</th>
                        <th width="28%">Nama Pasien</th>
                        <th width="25%">Poli Tujuan</th>
                        <th width="10%">Jam</th>
                        <th width="20%">Aksi Panggilan</th>
                    </tr>
                </thead>
                <tbody id="display-antrean">
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <div class="spinner-border spinner-border-sm me-2"></div> Memuat data antrean...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let allData = [];

    // 1. Ambil data dari server (get_data.php)
    async function updateMonitor() {
        try {
            const response = await fetch('get_data.php');
            const data = await response.json();
            allData = data;
            
            updatePoliOptions(data);
            applyFilters();
        } catch (err) {
            console.error("❌ Error fetch data:", err);
        }
    }

    // 2. Update opsi dropdown Poli secara dinamis
    function updatePoliOptions(data) {
        const selectPoli = document.getElementById('filterPoli');
        const currentSelection = selectPoli.value;
        const processedPoliNames = new Set();
        
        data.forEach(item => {
            // Logika pemisahan K3 sesuai instruksi
            if (item.poli === "K3 - USIA DEWASA & LANSIA") {
                if (item.no_antrean.startsWith("DC")) processedPoliNames.add("K3 - USIA DEWASA & LANSIA SELATAN");
                else if (item.no_antrean.startsWith("DD")) processedPoliNames.add("K3 - USIA DEWASA & LANSIA UTARA");
                else processedPoliNames.add(item.poli);
            } else {
                processedPoliNames.add(item.poli);
            }
        });
        
        const sortedPoli = Array.from(processedPoliNames).sort();
        let options = '<option value="">Semua Poli (Tampilkan Semua)</option>';
        sortedPoli.forEach(poli => {
            const selected = (poli === currentSelection) ? 'selected' : '';
            options += `<option value="${poli}" ${selected}>${poli}</option>`;
        });
        selectPoli.innerHTML = options;
    }

    // 3. Logika Filter Pencarian & Dropdown
    function applyFilters() {
        const searchKeyword = document.getElementById('searchInput').value.toLowerCase();
        const selectedFilter = document.getElementById('filterPoli').value;

        const filtered = allData.filter(item => {
            const matchSearch = item.nama.toLowerCase().includes(searchKeyword) || 
                               item.no_antrean.toLowerCase().includes(searchKeyword);
            
            let currentItemDisplayPoli = item.poli;
            if (item.poli === "K3 - USIA DEWASA & LANSIA") {
                if (item.no_antrean.startsWith("DC")) currentItemDisplayPoli = "K3 - USIA DEWASA & LANSIA SELATAN";
                else if (item.no_antrean.startsWith("DD")) currentItemDisplayPoli = "K3 - USIA DEWASA & LANSIA UTARA";
            }

            const matchPoli = selectedFilter === "" || currentItemDisplayPoli === selectedFilter;
            return matchSearch && matchPoli;
        });

        renderTable(filtered);
    }

    // 4. Gambar Tabel ke HTML
    function renderTable(dataToDisplay) {
        let html = '';
        if (dataToDisplay.length > 0) {
            dataToDisplay.forEach((item, index) => {
                let displayPoli = item.poli;
                if (item.poli === "K3 - USIA DEWASA & LANSIA") {
                    if (item.no_antrean.startsWith("DC")) displayPoli = "K3 - USIA DEWASA & LANSIA SELATAN";
                    else if (item.no_antrean.startsWith("DD")) displayPoli = "K3 - USIA DEWASA & LANSIA UTARA";
                }

                // Bersihkan nama dari tanda kutip agar tidak merusak fungsi onclick
                const namaSafe = item.nama.replace(/'/g, "\\'");

                html += `
                    <tr>
                        <td class="text-center fw-bold">${index + 1}</td>
                        <td class="text-center">
                            <span class="badge bg-success badge-antrean">${item.no_antrean}</span>
                        </td>
                        <td class="fw-bold text-uppercase">${item.nama}</td>
                        <td>${displayPoli}</td>
                        <td class="text-center text-muted">${item.jam}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-call" onclick="panggilSuara('${item.no_antrean}', '${namaSafe}', '${displayPoli}')">
                                <i class="bi bi-megaphone-fill"></i> Panggil
                            </button>
                            <button class="btn btn-sm btn-recall ms-1" onclick="panggilSuara('${item.no_antrean}', '${namaSafe}', '${displayPoli}')">
                                <i class="bi bi-arrow-repeat"></i> Ulang
                            </button>
                        </td>
                    </tr>`;
            });
        } else {
            html = '<tr><td colspan="6" class="text-center py-4 alert alert-warning">Tidak ada data antrean yang cocok.</td></tr>';
        }
        document.getElementById('display-antrean').innerHTML = html;
    }

    // 5. Fitur Suara (Text-to-Speech)
    function panggilSuara(nomor, nama, poli) {
    // 1. Ubah Nama dan Poli menjadi lowercase agar tidak dieja (menghindari error singkatan)
    const namaNatural = nama.toLowerCase();
    const poliNatural = poli.toLowerCase();

    // 2. Buat kalimat yang lebih santai
    // Kita gunakan jeda koma (,) agar intonasinya naik-turun seperti manusia
    const pesan = `Pasien atas nama, ${namaNatural}, silahkan menuju, ${poliNatural}`;

    const utara = new SpeechSynthesisUtterance(pesan);
    
    // Pengaturan Suara Bahasa Indonesia
    utara.lang = 'id-ID'; 
    utara.rate = 1.0;  // Kecepatan sedikit melambat agar jelas
    utara.pitch = 1.1; // Nada sedikit tinggi agar terdengar ramah

    // Hentikan suara yang sedang berjalan jika operator klik tombol lain
    window.speechSynthesis.cancel(); 
    window.speechSynthesis.speak(utara);
}

    // Event Listeners
    document.getElementById('searchInput').addEventListener('input', applyFilters);
    document.getElementById('filterPoli').addEventListener('change', applyFilters);

    // Update otomatis tiap 5 detik jika tidak sedang mencari
    setInterval(() => {
        if (document.getElementById('searchInput').value === "" && document.getElementById('filterPoli').value === "") {
            updateMonitor();
        }
    }, 5000);

    // Jam Digital
    setInterval(() => {
        const skrg = new Date();
        document.getElementById('clock').innerText = skrg.toLocaleString('id-ID', { 
            weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', 
            hour: '2-digit', minute: '2-digit', second: '2-digit' 
        });
    }, 1000);

    // Jalankan pertama kali
    updateMonitor();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>