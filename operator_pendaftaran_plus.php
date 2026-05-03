<?php
// operator_pendaftaran_plus.php
$folder = 'data';
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Pendaftaran - Puskesmas Tamansari</title>
    
    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f1f5f9; }
        
        /* Layout Sidebar Panggilan */
        .sidebar-panggilan {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 2rem;
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        /* Layout Tabel Pendaftaran */
        .header-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 20px;
            border-radius: 15px 15px 0 0;
        }

        .main-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            background: white;
        }

        .marquee-container {
            background: #2c3e50;
            color: #f1c40f;
            padding: 8px 0;
            font-weight: 600;
            font-size: 0.85rem;
            border-radius: 10px 10px 0 0;
        }

        .btn-call-action { transition: all 0.2s ease; }
        .btn-call-action:active { transform: scale(0.95); }
    </style>
</head>
<body class="p-4">

<div class="container-fluid">
    <div class="row g-4">
        
        <!-- BAGIAN KIRI: PANEL PANGGILAN (Eks loketA.php) -->
        <div class="col-lg-3 col-md-4">
            <div class="sidebar-panggilan shadow-2xl border border-white p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-xl font-extrabold text-slate-800">LOKET A Pendaftaran</h1>
                        <p class="text-xs text-slate-500 font-medium">Puskesmas Tamansari</p>
                    </div>
                    <div class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase animate-pulse">● Online</div>
                </div>

                <div class="bg-slate-900 rounded-3xl p-6 text-center mb-6 shadow-inner relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-green-500"></div>
                    <span class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em]">Antrean Sekarang</span>
                    <h2 id="nomor-panggilan" class="text-7xl font-black text-white mt-2 mb-2">0</h2>
                </div>

                <div class="mb-4">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">Panggil Manual</label>
                    <div class="flex gap-2">
                        <input type="number" id="input-manual" class="flex-1 bg-white border-2 border-slate-200 rounded-xl px-3 py-2 text-sm focus:border-blue-500 outline-none">
                        <button onclick="panggilManual()" class="bg-blue-600 text-white px-4 rounded-xl font-bold text-sm">Panggil</button>
                    </div>
                </div>

                <div class="grid gap-3">
                    <button onclick="next()" class="btn-call-action bg-green-600 text-white rounded-2xl py-4 px-4 flex items-center justify-between shadow-lg shadow-green-200">
                        <div class="flex items-center gap-2">
                            <i data-lucide="user-plus" class="w-5 h-5"></i>
                            <span class="font-bold text-sm">Panggil Berikutnya</span>
                        </div>
                    </button>
                    <div class="grid grid-cols-2 gap-2">
                        <button onclick="ulang()" class="btn-call-action bg-blue-500 text-white rounded-2xl py-3 text-xs font-bold flex items-center justify-center gap-2">
                            <i data-lucide="refresh-cw" class="w-4 h-4"></i> Ulang
                        </button>
                        <button onclick="before()" class="btn-call-action bg-slate-200 text-slate-700 rounded-2xl py-3 text-xs font-bold flex items-center justify-center gap-2">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i> Sebelum
                        </button>
                    </div>
                </div>

                <div class="mt-8 pt-4 border-t border-slate-100 text-center">
                    <button onclick="resetLoket()" class="text-slate-400 hover:text-red-500 text-[10px] font-bold uppercase tracking-widest">
                        <i data-lucide="trash-2" class="inline w-3 h-3 mb-1"></i> Reset Antrean
                    </button>
                </div>
            </div>
        </div>

        <!-- BAGIAN KANAN: DAFTAR PASIEN (Eks operator_pendaftaran.php) -->
        <div class="col-lg-9 col-md-8">
            <div class="marquee-container shadow-sm mb-0">
                <marquee behavior="scroll" direction="left">
                    Petunjuk: Konfirmasi kehadiran pasien untuk memindahkan data ke bagian skrining. Tombol Reset digunakan hanya saat layanan berakhir.[cite: 7]
                </marquee>
            </div>

            <div class="header-section shadow-sm mb-4">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h2 class="fw-bold mb-0 text-xl"><i class="bi bi-person-badge-fill me-2"></i> Pendaftaran Pasien</h2>
                        <small class="opacity-75">Manajemen Konfirmasi Kehadiran Pasien</small>
                    </div>
                    <div class="col-4 text-end">
                        <h3 id="clock" class="fw-bold mb-0 text-lg">00:00:00</h3>
                        <small class="opacity-75"><?php echo date('d F Y'); ?></small>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-8">
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-primary"></i></span>
                        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari Nama atau No. Antrean...">
                    </div>
                </div>
                <div class="col-md-4">
                    <select id="filterPoli" class="form-select shadow-sm">
                        <option value="">Semua Poli / Unit Kerja</option>
                    </select>
                </div>
            </div>

            <div class="card main-card overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-sm">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>NO</th>
                                <th>ANTREAN</th>
                                <th class="text-start">NAMA PASIEN</th>
                                <th>POLI TUJUAN</th>
                                <th>JAM DATANG</th>
                                <th>STATUS</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            <!-- Data AJAX -->
                        </tbody>
                    </table>
                </div>
                <div class="p-3 border-top bg-light flex justify-between items-center text-xs">
                    <div id="info-count" class="text-muted">Menampilkan data...</div>
                    <ul class="pagination pagination-sm mb-0" id="pagination-list"></ul>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    lucide.createIcons();
    
    // KREDENSIAL PUSHER (Sinkron dengan api.php)[cite: 6]
    const pusher = new Pusher('8b7f969aee7f1ab6ea06', { cluster: 'ap1' });
    const channel = pusher.subscribe('antrian-channel');

    channel.bind('panggil-event', (data) => { 
        document.getElementById("nomor-panggilan").innerText = data.nomor || data.no_antrean; 
    });

    // LOGIKA PANEL PANGGILAN
    function next() { fetch("api.php?action=next&loket=A"); }
    function before() { fetch("api.php?action=before&loket=A"); }
    function ulang() { fetch("api.php?action=repeat&loket=A"); }
    function panggilManual() {
        const n = document.getElementById("input-manual").value;
        if(n) fetch(`api.php?action=manual&nomor=${n}&loket=A`).then(()=>document.getElementById("input-manual").value="");
    }

    // LOGIKA TABEL PENDAFTARAN
    let allData = [];
    let confirmedData = {};
    let currentPage = 1;
    const rowsPerPage = 15;

    async function loadData() {
        try {
            const [resData, resConf] = await Promise.all([
                fetch('get_data.php'),
                fetch('get_confirmed_list.php')
            ]);
            allData = await resData.json();
            confirmedData = await resConf.json();
            renderTable();
        } catch (e) { console.error("Sinkronisasi Gagal", e); }
    }

    function renderTable() {
        const search = $('#searchInput').val().toLowerCase();
        const poliFilter = $('#filterPoli').val();
        const tbody = $('#table-body');
        
        let filtered = allData.filter(item => 
            (item.nama.toLowerCase().includes(search) || item.no_antrean.toLowerCase().includes(search)) &&
            (poliFilter === "" || item.poli.includes(poliFilter))
        );

        const totalPages = Math.ceil(filtered.length / rowsPerPage);
        const start = (currentPage - 1) * rowsPerPage;
        const paginated = filtered.slice(start, start + rowsPerPage);

        $('#info-count').text(`Menampilkan ${start+1}-${Math.min(start+rowsPerPage, filtered.length)} dari ${filtered.length} pasien`);

        let html = '';
        paginated.forEach((item, index) => {
            const isConfirmed = confirmedData[item.no_antrean] !== undefined;
            const skrining = isConfirmed ? confirmedData[item.no_antrean] : null;

            html += `
                <tr class="${isConfirmed ? 'bg-green-50' : ''}">
                    <td class="text-center">${start + index + 1}</td>
                    <td class="text-center"><span class="badge bg-green-600">${item.no_antrean}</span></td>
                    <td class="fw-bold uppercase">${item.nama} ${isConfirmed ? '<i class="bi bi-check-circle-fill text-success ms-1"></i>' : ''}</td>
                    <td class="text-center text-danger small fw-bold">${item.poli}</td>
                    <td class="text-center text-muted">${item.jam.split(' ')[1] || item.jam}</td>
                    <td class="text-center">
                        ${isConfirmed ? `<span class="badge bg-primary">SKRINING NO: ${skrining.urut}</span>` : `<span class="badge bg-light text-dark border">Menunggu</span>`}
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm ${isConfirmed ? 'btn-outline-success disabled' : 'btn-primary'} w-100" 
                                onclick="doKonfirmasi('${item.no_antrean}','${item.nama.replace(/'/g, "\\'")}','${item.poli}','${item.umur}','${item.jam}')">
                            ${isConfirmed ? 'Hadir' : 'Konfirmasi'}
                        </button>
                    </td>
                </tr>`;
        });
        tbody.html(html);
        updatePagination(totalPages);
    }

    function updatePagination(total) {
        let h = '';
        for(let i=1; i<=total; i++) {
            h += `<li class="page-item ${i===currentPage?'active':''}"><a class="page-link" href="#" onclick="currentPage=${i};renderTable();return false;">${i}</a></li>`;
        }
        $('#pagination-list').html(h);
    }

    function doKonfirmasi(no, nama, poli, umur, jam) {
        $.ajax({
            url: 'konfirmasi_aksi.php',
            type: 'POST',
            data: { no_antrean: no, nama: nama, poli: poli, umur: umur, jam_datang: jam },
            success: function(res) {
                const r = JSON.parse(res);
                if(r.status === 'success') { confirmedData[no] = { urut: r.urut }; renderTable(); }
            }
        });
    }

    function resetLoket() {
        if(confirm("Reset Antrean?")) fetch("api.php?action=reset").then(()=>location.reload());
    }

    setInterval(() => $('#clock').text(new Date().toLocaleTimeString('id-ID')), 1000);
    setInterval(loadData, 5000);
    $(document).ready(loadData);
    $('#searchInput, #filterPoli').on('input change', () => { currentPage = 1; renderTable(); });
</script>
</body>
</html>