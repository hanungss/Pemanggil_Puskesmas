<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Resep - Puskesmas Tamansari</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        :root {
            --dark-bg: #064e3b; 
            --far-green: #10b981;
            --soft-green: #ecfdf5;
            --accent-yellow: #fbbf24;
            --light-bg: #f9fafb;
        }

        body { background-color: var(--dark-bg); font-family: 'Segoe UI', sans-serif; margin: 0; }
        .header-container { padding: 30px 20px; text-align: center; color: white; border-bottom: 6px solid var(--far-green); }
        .clock-capsule { background: rgba(255, 255, 255, 0.1); display: inline-block; padding: 8px 25px; border-radius: 50px; margin: 10px 0; }
        #clock { font-size: 1.6rem; font-weight: 700; color: var(--accent-yellow); }
        .main-content { background: var(--light-bg); padding: 20px; min-height: 100vh; border-radius: 30px 30px 0 0; }
        .badge-number { background: var(--far-green) !important; font-size: 1.1rem; padding: 6px 12px; font-weight: 800; color: white; }
        .status-selesai { background-color: #d1fae5 !important; opacity: 0.8; }
        .status-proses { background-color: #ffffff; }
        .btn-call-group { border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn-main-call { background: var(--far-green); color: white; border: none; padding: 8px 16px; font-weight: 600; }
        .btn-main-call:hover { background: #059669; }
    </style>
</head>
<body>

<div class="header-container">
    <h1 class="fw-bold"><i class="fas fa-file-medical me-3"></i>OPERATOR RESEP</h1>
    <h2 class="h5 fw-light opacity-75">Puskesmas Tamansari - Boyolali</h2>
    <div class="clock-capsule">
        <span id="clock">00:00:00</span>
        <small id="date-text" class="text-white-50 d-block"></small>
    </div>
</div>

<div class="main-content">
    <div class="container-fluid">
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-success"></i></span>
                    <input type="text" id="searchInput" class="form-control form-control-lg" placeholder="Cari nama pasien...">
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-funnel text-success"></i></span>
                    <select id="filterStatus" class="form-select form-select-lg">
                        <option value="Proses">Belum Selesai (Proses)</option>
                        <option value="Selesai">Sudah Selesai (Selesai)</option>
                        <option value="">Tampilkan Semua</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="table-responsive shadow-sm rounded border bg-white">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-success text-center">
                    <tr>
                        <th width="5%">NO</th>
                        <th width="15%">ANTREAN</th>
                        <th width="30%">NAMA PASIEN</th>
                        <th width="20%">DARI UNIT</th>
                        <th width="10%">STATUS</th>
                        <th width="20%">AKSI</th>
                    </tr>
                </thead>
                <tbody id="display-antrean"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let allData = [];
    let calledStatus = {};

    async function fetchData() {
        try {
            const response = await fetch('get_data_res.php'); // Mengambil data resep[cite: 2]
            allData = await response.json();
            renderTable();
        } catch (e) { 
            console.error("Gagal sinkronisasi data resep:", e); 
        }
    }

    function renderTable() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const filterStatus = document.getElementById('filterStatus').value;
        const tbody = document.getElementById('display-antrean');

        const filtered = allData.filter(item => {
            const matchesSearch = item.nama.toLowerCase().includes(search) || item.no_antrean.includes(search);
            const matchesFilter = filterStatus === "" || item.status === filterStatus;
            return matchesSearch && matchesFilter;
        });

        if (filtered.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-muted">Data resep tidak ditemukan</td></tr>`;
            return;
        }

        tbody.innerHTML = filtered.map((item, index) => {
            const isSelesai = item.status === 'Selesai';
            const rowClass = isSelesai ? 'status-selesai' : 'status-proses';
            const isCalled = calledStatus[item.no_antrean] === true;
            const callText = isCalled ? "Sudah Dipanggil" : "Panggil";
            const callIcon = isCalled ? "bi-check-circle-fill" : "bi-megaphone-fill";

            return `
                <tr class="${rowClass}">
                    <td class="text-center fw-bold text-muted">${index + 1}</td>
                    <td class="text-center"><span class="badge badge-number">${item.no_antrean}</span></td>
                    <td class="fw-bold text-uppercase text-dark">
                        ${item.nama}
                        <div class="small text-muted fw-normal" style="font-size: 0.75rem;">Status: ${item.status}</div>
                    </td>
                    <td>${item.ruangan_asal}</td>
                    <td class="text-center">
                        <span class="badge ${isSelesai ? 'bg-success' : 'bg-warning text-dark'}">${item.status}</span>
                    </td>
                    <td class="text-center">
                        <button class="btn-main-call rounded shadow-sm" onclick="triggerPanggilan('${item.no_antrean}','${item.nama.replace(/'/g, "\\'")}','RESEP')">
                            <i class="bi ${callIcon} me-1"></i> ${callText}
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function triggerPanggilan(nomor, nama, poli) {
        calledStatus[nomor] = true;
        renderTable();

        $.ajax({
            url: 'panggil_aksi.php',
            type: 'POST',
            data: { no_antrean: nomor, nama: nama, poli: poli },
            dataType: 'json',
            success: function(response) {
                console.log("Sinyal panggil resep terkirim");
            }
        });
    }

    setInterval(() => {
        const now = new Date();
        const clockEl = document.getElementById('clock');
        if(clockEl) clockEl.innerText = now.toLocaleTimeString('id-ID');
    }, 1000);

    document.getElementById('searchInput').addEventListener('input', renderTable);
    document.getElementById('filterStatus').addEventListener('change', renderTable);

    setInterval(fetchData, 10000);
    fetchData();
</script>
</body>
</html>