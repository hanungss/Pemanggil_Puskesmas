<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Lab - Puskesmas Tamansari</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        :root {
            --dark-bg: #1e293b; /* Biru gelap modern */
            --lab-blue: #3b82f6; /* Warna khas laboratorium */
            --health-green: #10b981;
            --accent-yellow: #f59e0b;
            --light-bg: #f8fafc;
        }

        body { 
            background-color: var(--dark-bg); 
            font-family: 'Segoe UI', system-ui, sans-serif; 
            margin: 0;
        }

        .header-container {
            padding: 30px 20px;
            text-align: center;
            color: white;
            border-bottom: 6px solid var(--lab-blue);
        }

        .clock-capsule {
            background: rgba(255, 255, 255, 0.1);
            display: inline-block;
            padding: 8px 25px;
            border-radius: 50px;
            margin: 10px 0;
        }

        #clock {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--accent-yellow);
        }

        .main-content {
            background: var(--light-bg);
            padding: 20px;
            min-height: 100vh;
            border-radius: 30px 30px 0 0;
        }

        .badge-number {
            background: var(--lab-blue) !important;
            font-size: 1.1rem;
            padding: 6px 12px;
            font-weight: 800;
        }

        /* Styling status */
        .status-selesai { background-color: #d1fae5 !important; opacity: 0.8; }
        .status-proses { background-color: #ffffff; }

        /* BUTTONS */
        .btn-call-group {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn-main-call {
            background: var(--lab-blue);
            color: white; border: none;
            padding: 8px 16px; font-weight: 600;
        }

        .btn-main-call:hover { background: #2563eb; }

        .btn-side-call {
            background: #64748b;
            color: white; border: none;
            padding: 8px 12px;
        }
    </style>
</head>
<body>

<div class="header-container">
    <h1 class="fw-black"><i class="fas fa-microscope me-3"></i>OPERATOR LABORATORIUM</h1>
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
                    <span class="input-group-text bg-white"><i class="bi bi-search text-primary"></i></span>
                    <input type="text" id="searchInput" class="form-control form-control-lg" placeholder="Cari nama atau nomor antrean...">
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-funnel text-primary"></i></span>
                    <select id="filterStatus" class="form-select form-select-lg">
                        <option value="Proses">Tampilkan Belum Diperiksa (Proses)</option>
                        <option value="Selesai">Tampilkan Sudah Diperiksa (Selesai)</option>
                        <option value="">Tampilkan Semua</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="table-responsive shadow-sm rounded border bg-white">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark text-center">
                    <tr>
                        <th width="5%">NO</th>
                        <th width="15%">NO. ANTREAN</th>
                        <th width="30%">NAMA PASIEN</th>
                        <th width="20%">RUANGAN ASAL</th>
                        <th width="10%">STATUS</th>
                        <th width="20%">AKSI</th>
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
    let calledStatus = {}; // Menyimpan status tombol yang sudah diklik

    async function fetchData() {
        console.log("🔄 [LOG] Menyinkronkan data dari server...");
        try {
            // Mengambil data dari get_data_lab.php
            const response = await fetch('get_data_lab.php');
            allData = await response.json();
            console.log("📊 [LOG] Data diterima:", allData.length);
            renderTable();
        } catch (e) { 
            console.error("❌ [LOG] Gagal sinkronisasi data:", e); 
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
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-muted">Tidak ada data antrean ditemukan</td></tr>`;
            return;
        }

        tbody.innerHTML = filtered.map((item, index) => {
            const isSelesai = item.status === 'Selesai';
            const rowClass = isSelesai ? 'status-selesai' : 'status-proses';
            const namaSafe = item.nama.replace(/'/g, "\\'");
            
            // Logika penanda tombol Pusher
            const isCalled = calledStatus[item.no_antrean] === true;
            const callText = isCalled ? "Sudah Dipanggil" : "Panggil";
            const callIcon = isCalled ? "bi-check-circle-fill" : "bi-megaphone-fill";
            
            // Format umur
            const displayUmur = item.umur && item.umur !== "-" ? item.umur + " Thn" : "-";

            return `
                <tr class="${rowClass}">
                    <td class="text-center fw-bold text-muted">${index + 1}</td>
                    <td class="text-center"><span class="badge badge-number">${item.no_antrean}</span></td>
                    <td class="fw-bold text-uppercase">
                        ${item.nama}
                        <div class="small text-muted fw-normal" style="font-size: 0.75rem;">Umur: ${displayUmur}</div>
                    </td>
                    <td><i class="bi bi-arrow-right-short text-primary"></i> ${item.ruangan_asal}</td>
                    <td class="text-center">
                        <span class="badge ${isSelesai ? 'bg-success' : 'bg-warning text-dark'}">
                            ${item.status}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="btn-call-group d-inline-flex">
                            <button class="btn-main-call" onclick="triggerPanggilan('${item.no_antrean}','${namaSafe}','LABORATORIUM')">
                                <i class="bi ${callIcon} me-1"></i> ${callText}
                            </button>
                            <button class="btn-side-call" onclick="triggerPanggilan('${item.no_antrean}','${namaSafe}','LABORATORIUM')" title="Ulangi">
                                <i class="bi bi-arrow-repeat"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // FUNGSI UTAMA: MENGIRIM SINYAL KE PUSHER
    function triggerPanggilan(nomor, nama, poli) {
        calledStatus[nomor] = true;
        renderTable();

        console.log("📣 [LOG] Memanggil Laboratorium via Pusher:", nomor, nama);

        $.ajax({
            url: 'panggil_aksi.php',
            type: 'POST',
            data: {
                no_antrean: nomor,
                nama: nama,
                poli: poli // Mengirim teks 'LABORATORIUM' agar suara monitor sesuai
            },
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    console.log("✅ [LOG] Sinyal berhasil dikirim ke monitor.");
                } else {
                    console.error("❌ [LOG] Gagal mengirim sinyal:", response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("❌ [LOG] Koneksi Error:", error);
                alert("Gagal terhubung ke panggil_aksi.php");
            }
        });
    }

    // Interval Jam
    setInterval(() => {
        const clockEl = document.getElementById('clock');
        const dateEl = document.getElementById('date-text');
        const now = new Date();
        if(clockEl) clockEl.innerText = now.toLocaleTimeString('id-ID');
        if(dateEl) dateEl.innerText = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    }, 1000);

    // Event Listeners
    document.getElementById('searchInput').addEventListener('input', renderTable);
    document.getElementById('filterStatus').addEventListener('change', renderTable);

    // Refresh data tiap 5 detik
    setInterval(fetchData, 5000);
    fetchData(); // Load awal
</script>

</body>
</html>