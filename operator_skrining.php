<?php
// operator_skrining.php
$folder = 'data';
$filename = $folder . "/skrining_" . date('Y-m-d') . ".txt";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Skrining - Puskesmas Tamansari</title>
    
    <!-- Bootstrap 5, Icons, & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="https://puskesmastamansari.boyolali.go.id/files/setting/thumb/190_115-1773108375-Logo_Puskesmas_Tanpa_Background.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        :root {
            --dark-bg: #2c3e50;
            --health-green: #2ecc71;
            --accent-yellow: #f1c40f;
            --light-bg: #ffffff;
            --call-blue: #3498db;
            --recall-orange: #f39c12;
        }

        body { 
            background-color: var(--dark-bg); 
            font-family: 'Segoe UI', sans-serif; 
            margin: 0;
            overflow-x: hidden;
        }

        /* RUNNING TEXT SECTION */
        .marquee-container {
            background: #1a252f;
            color: var(--accent-yellow);
            padding: 10px 0;
            font-weight: 600;
            border-bottom: 2px solid var(--health-green);
        }

        /* HEADER SECTION */
        .header-container {
            padding: 30px 20px;
            text-align: center;
            color: white;
            border-bottom: 8px solid var(--health-green);
        }

        .header-container h1 {
            font-size: 2.2rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 5px;
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
            display: block;
        }

        /* TABLE AREA */
        .main-content {
            background: var(--light-bg);
            padding: 30px;
            min-height: 80vh;
        }

        .card-main {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            background: white;
        }

        .badge-number {
            background: var(--dark-bg) !important;
            font-size: 1rem;
            padding: 8px 12px;
            font-weight: 800;
            color: white;
        }

        .patient-name-col {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark-bg);
            text-transform: uppercase;
        }

        .status-selesai { 
            background-color: #f8f9fa !important; 
            color: #bdc3c7 !important; 
        }
        
        .status-selesai .patient-name-col { text-decoration: line-through; color: #bdc3c7; }

        /* MODERN FLOATING BUTTONS */
        .btn-call-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            background: transparent;
        }

        .btn-call-action, .btn-success-finish {
            border: none;
            padding: 10px 18px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-panggil { 
            background: var(--call-blue); 
            color: white; 
        }

        .btn-recall-orange { 
            background: var(--recall-orange); 
            color: white; 
        }

        .btn-success-finish {
            background: var(--health-green);
            color: white;
        }

        /* Hover Effect: Floating */
        .btn-call-action:hover, .btn-success-finish:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2) !important;
            filter: brightness(110%);
        }

        /* PAGINATION */
        .pagination-container {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>

<div class="marquee-container">
    <marquee behavior="scroll" direction="left">
        Petunjuk Penggunaan: Gunakan tombol "Panggil" untuk memanggil pasien ke meja skrining. Klik tombol "Selesai" jika pemeriksaan fisik telah selesai dilakukan. Gunakan tombol Reset di bagian bawah hanya jika jam layanan telah berakhir.[cite: 7]
    </marquee>
</div>

<div class="header-container">
    <h1>Petugas Skrining & Tensi</h1>
    <h2 class="h4 fw-light opacity-75">Puskesmas Tamansari Kabupaten Boyolali</h2>
    
    <div class="clock-capsule shadow-sm">
        <span id="clock">00:00:00</span>
        <small id="date-text" class="text-white-50 d-block mt-1"></small>
    </div>
</div>

<div class="main-content">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-6 mx-auto">
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-success"></i></span>
                    <input type="text" id="skriningSearch" class="form-control form-control-lg border-start-0" placeholder="Cari nama pasien di daftar skrining...">
                </div>
            </div>
        </div>

        <div class="card card-main overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th>URUT</th>
                            <th>ID ANTREAN</th>
                            <th class="text-start">NAMA LENGKAP PASIEN</th>
                            <th>TUJUAN POLI</th>
                            <th>KEDATANGAN</th>
                            <th width="300">AKSI SKRINING</th>
                        </tr>
                    </thead>
                    <tbody id="body-skrining">
                        <!-- Data AJAX -->
                    </tbody>
                </table>
            </div>

            <div class="pagination-container">
                <div class="text-muted small">
                    Data ke <span id="info-start">0</span> - <span id="info-end">0</span> dari <span id="info-total">0</span> pasien dikonfirmasi.
                </div>
                <nav>
                    <ul class="pagination pagination-sm mb-0" id="pagination-list">
                        <!-- Tombol Navigasi Halaman -->
                    </ul>
                </nav>
            </div>
        </div>

        <!-- <div class="d-flex justify-content-end mt-4 mb-5">
            <button class="btn btn-danger btn-sm shadow-sm" onclick="resetAntrean()">
                <i class="bi bi-trash3-fill me-2"></i> Reset Antrean Hari Ini
            </button>
        </div> -->
    </div>
</div>


<script>
    let rawData = [];
    let currentPage = 1;
    const rowsPerPage = 15; 
    let calledStatus = {}; // Melacak status tombol yang diklik[cite: 6]

    async function loadSkrining() {
        try {
            const response = await fetch('get_skrining_data.php');
            rawData = await response.json(); // Mengambil data dari skrining.txt[cite: 2, 7]
            renderTable();
        } catch (e) { console.error("Gagal memuat data", e); }
    }

    function renderTable() {
        const search = $('#skriningSearch').val().toLowerCase();
        const tbody = $('#body-skrining');
        
        const filtered = rawData.filter(item => 
            item.nama.toLowerCase().includes(search) || item.no_antrean.toLowerCase().includes(search)
        );

        const totalItems = filtered.length;
        const totalPages = Math.ceil(totalItems / rowsPerPage);
        
        if (currentPage > totalPages) currentPage = totalPages || 1;

        const start = (currentPage - 1) * rowsPerPage;
        const end = Math.min(start + rowsPerPage, totalItems);
        const paginatedItems = filtered.slice(start, end);

        $('#info-start').text(totalItems > 0 ? start + 1 : 0);
        $('#info-end').text(end);
        $('#info-total').text(totalItems);

        let html = '';
        if(paginatedItems.length === 0) {
            html = '<tr><td colspan="6" class="text-center py-5 text-muted">Belum ada pasien yang dikonfirmasi.</td></tr>';
        } else {
            paginatedItems.forEach(item => {
                const isSelesai = item.status === 'SELESAI';
                const isCalled = calledStatus[item.no_antrean] === true;
                
                // Konfigurasi Dinamis Tombol Panggil[cite: 6, 7]
                const btnLabel = isCalled ? "PANGGIL ULANG" : "PANGGIL";
                const btnClass = isCalled ? "btn-recall-orange" : "btn-panggil";
                const btnIcon = isCalled ? "bi-arrow-repeat" : "bi-megaphone-fill";

                html += `
                    <tr class="${isSelesai ? 'status-selesai' : ''}">
                        <td class="text-center fw-bold fs-5 text-primary">${item.urut}</td>
                        <td class="text-center"><span class="badge badge-number">${item.no_antrean}</span></td>
                        <td class="patient-name-col">${item.nama}</td>
                        <td class="text-center"><span class="badge bg-light text-danger border border-danger px-3">${item.poli}</span></td>
                        <td class="text-center text-muted fw-bold small">${item.jam_datang}</td>
                        <td class="text-center">
                            ${isSelesai ? 
                                `<span class="text-success fw-bold"><i class="bi bi-check2-all me-1"></i> SKRINING SELESAI</span>` : 
                                `<div class="btn-call-group">
                                    <button class="btn-call-action ${btnClass} rounded-pill shadow-sm" onclick="panggilPasien('${item.no_antrean}','${item.nama.replace(/'/g, "\\'")}','${item.poli}')">
                                        <i class="bi ${btnIcon}"></i> <span>${btnLabel}</span>
                                    </button>
                                    <button class="btn-success-finish rounded-circle shadow-sm" style="width: 42px; height: 42px;" onclick="selesaiSkrining('${item.no_antrean}')" title="Selesai">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </div>`
                            }
                        </td>
                    </tr>`;
            });
        }
        tbody.html(html);
        updatePagination(totalPages);
    }

    function updatePagination(totalPages) {
        let pgHtml = '';
        for (let i = 1; i <= totalPages; i++) {
            pgHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="changePage(event, ${i})">${i}</a>
                       </li>`;
        }
        $('#pagination-list').html(pgHtml);
    }

    function changePage(e, page) {
        e.preventDefault();
        currentPage = page;
        renderTable();
    }

    function panggilPasien(no, nama, poli) {
        calledStatus[no] = true; // Tandai sudah dipanggil secara lokal[cite: 6]
        renderTable(); // Update teks tombol seketika[cite: 6]

        $.ajax({
            url: 'panggil_skrining_aksi.php',
            type: 'POST',
            data: { no_antrean: no, nama: nama, poli: "Meja Skrining" }, // Panggilan ke Pusher[cite: 3]
            dataType: 'json',
            success: function(res) { 
                if(res.status === 'success') console.log("📣 Memanggil: " + nama); 
            }
        });
    }

    function selesaiSkrining(no) {
        if(!confirm('Konfirmasi pemeriksaan fisik pasien selesai?')) return;
        $.ajax({
            url: 'update_skrining_status.php',
            type: 'POST',
            data: { no_antrean: no }, // Update status di file .txt[cite: 2]
            dataType: 'json',
            success: function(res) { if(res.status === 'success') loadSkrining(); }
        });
    }

    function resetAntrean() {
        if (!confirm('Hapus semua data antrean skrining hari ini?')) return;
        $.ajax({
            url: 'reset_skrining_aksi.php',
            type: 'POST',
            dataType: 'json',
            success: function(res) { if (res.status === 'success') location.reload(); }
        });
    }

    // Jam & Tanggal Real-time
    setInterval(() => {
        const now = new Date();
        $('#clock').text(now.toLocaleTimeString('id-ID'));
        $('#date-text').text(now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }));
    }, 1000);

    setInterval(loadSkrining, 5000); // Sinkronisasi data otomatis[cite: 7]
    $(document).ready(loadSkrining);
    $('#skriningSearch').on('input', () => { currentPage = 1; renderTable(); });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>