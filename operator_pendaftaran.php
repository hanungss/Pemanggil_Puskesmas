<?php
// operator_pendaftaran.php
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
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            --success-color: #2ecc71;
            --accent-yellow: #f1c40f;
        }

        body { background-color: #f4f7f6; font-family: 'Inter', sans-serif; }

        /* Running Text Style */
        .marquee-container {
            background: #2c3e50;
            color: var(--accent-yellow);
            padding: 8px 0;
            font-weight: 600;
            font-size: 0.9rem;
            border-bottom: 2px solid var(--success-color);
        }

        .header-section {
            background: var(--primary-gradient);
            color: white;
            padding: 25px 0;
            margin-bottom: 25px;
        }

        .clock-box {
            background: rgba(255, 255, 255, 0.15);
            padding: 8px 18px;
            border-radius: 12px;
            backdrop-filter: blur(5px);
        }

        .main-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            background: white;
        }

        .table thead { background-color: #2c3e50; color: white; }
        .badge-number { background: var(--success-color); font-size: 0.9rem; padding: 6px 10px; }
        .confirmed-row { background-color: #f0fff4 !important; }

        /* Pagination Styles */
        .pagination-container {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>

<!-- Running Text Petunjuk -->
<div class="marquee-container">
    <marquee behavior="scroll" direction="left">
        Petunjuk: Cari nama pasien atau nomor antrean pada kolom pencarian. Klik tombol "Konfirmasi" untuk mengirim pasien ke meja skrining. Gunakan tombol Reset di bagian bawah hanya saat layanan hari ini telah berakhir.
    </marquee>
</div>

<div class="header-section shadow-sm">
    <div class="container-fluid px-4">
        <div class="row align-items-center">
            <div class="col-md-7">
                <div class="d-flex align-items-center">
                    <i class="bi bi-person-badge-fill fs-1 me-3 text-warning"></i>
                    <div>
                        <h2 class="fw-bold mb-0">Pendaftaran Pasien</h2>
                        <p class="mb-0 opacity-75">Puskesmas Tamansari Kabupaten Boyolali</p>
                    </div>
                </div>
            </div>
            <div class="col-md-5 text-md-end mt-3 mt-md-0">
                <div class="clock-box d-inline-block shadow-sm">
                    <h3 id="clock" class="fw-bold mb-0">00:00:00</h3>
                    <small class="opacity-75"><?php echo date('d F Y'); ?></small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-4">
    <div class="row g-3 mb-4">
        <div class="col-lg-8 col-md-7">
            <div class="input-group input-group-lg shadow-sm">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-primary"></i></span>
                <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari Nama atau No. Antrean...">
            </div>
        </div>
        <div class="col-lg-4 col-md-5">
            <div class="input-group input-group-lg shadow-sm">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-hospital text-primary"></i></span>
                <select id="filterPoli" class="form-select border-start-0">
                    <option value="">Semua Poli / Unit Kerja</option>
                </select>
            </div>
        </div>
    </div>

    <div class="card main-card mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="text-center">
                        <th width="5%">NO</th>
                        <th width="12%">ANTREAN</th>
                        <th class="text-start">NAMA PASIEN</th>
                        <th width="20%">POLI TUJUAN</th>
                        <th width="12%">JAM DATANG</th>
                        <th width="15%">STATUS</th>
                        <th width="15%">AKSI</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <!-- Data AJAX -->
                </tbody>
            </table>
        </div>
        
        <!-- Pagination UI -->
        <div class="pagination-container">
            <div class="text-muted small">
                Menampilkan <span id="start-count">0</span> - <span id="end-count">0</span> dari <span id="total-count">0</span> antrean
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0" id="pagination-list">
                    <!-- Tombol Halaman -->
                </ul>
            </nav>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-5">
        <button class="btn btn-danger btn-sm shadow-sm" onclick="resetAntrean()">
            <i class="bi bi-trash3-fill me-2"></i> Reset Antrean Hari Ini
        </button>
    </div>
</div>

<script>
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
        
        let filtered = allData.filter(item => {
            return (item.nama.toLowerCase().includes(search) || item.no_antrean.toLowerCase().includes(search)) &&
                   (poliFilter === "" || item.poli.includes(poliFilter));
        });

        const totalItems = filtered.length;
        const totalPages = Math.ceil(totalItems / rowsPerPage);
        
        // Atur Current Page jika filter mengubah jumlah data
        if (currentPage > totalPages) currentPage = totalPages || 1;

        const startIndex = (currentPage - 1) * rowsPerPage;
        const endIndex = Math.min(startIndex + rowsPerPage, totalItems);
        const paginatedItems = filtered.slice(startIndex, endIndex);

        // Update Info Text
        $('#start-count').text(totalItems > 0 ? startIndex + 1 : 0);
        $('#end-count').text(endIndex);
        $('#total-count').text(totalItems);

        let html = '';
        if(paginatedItems.length === 0) {
            html = '<tr><td colspan="7" class="text-center py-5 text-muted">Tidak ada data antrean aktif.</td></tr>';
        } else {
            paginatedItems.forEach((item, index) => {
                const globalIndex = startIndex + index + 1;
                const isConfirmed = confirmedData[item.no_antrean] !== undefined;
                const skrining = isConfirmed ? confirmedData[item.no_antrean] : null;
                const jamDatang = item.jam.includes(' ') ? item.jam.split(' ')[1] : item.jam;

                html += `
                    <tr class="${isConfirmed ? 'confirmed-row' : ''}">
                        <td class="text-center fw-bold text-muted">${globalIndex}</td>
                        <td class="text-center"><span class="badge badge-number">${item.no_antrean}</span></td>
                        <td class="fw-bold text-uppercase">
                            ${item.nama}
                            ${isConfirmed ? '<i class="bi bi-check-circle-fill text-success ms-1"></i>' : ''}
                        </td>
                        <td class="text-center"><span class="text-danger fw-bold small">${item.poli}</span></td>
                        <td class="text-center fw-bold text-muted small">${jamDatang}</td>
                        <td class="text-center">
                            ${isConfirmed ? 
                                `<span class="badge bg-primary rounded-pill">SKRINING NO: ${skrining.urut}</span>` : 
                                `<span class="badge bg-light text-dark border">Menunggu</span>`}
                        </td>
                        <td class="text-center">
                            ${isConfirmed ? 
                                `<button class="btn btn-xs btn-outline-success disabled w-100"><i class="bi bi-check-lg"></i> Hadir</button>` : 
                                `<button class="btn btn-sm btn-primary w-100 shadow-sm" onclick="doKonfirmasi('${item.no_antrean}','${item.nama.replace(/'/g, "\\'")}','${item.poli}','${item.umur}','${item.jam}')">
                                    <i class="bi bi-person-check"></i> Konfirmasi
                                </button>`
                            }
                        </td>
                    </tr>
                `;
            });
        }
        tbody.html(html);
        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        let paginationHtml = '';
        for (let i = 1; i <= totalPages; i++) {
            paginationHtml += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                </li>
            `;
        }
        $('#pagination-list').html(paginationHtml);
    }

    function changePage(page) {
        event.preventDefault();
        currentPage = page;
        renderTable();
    }

    function doKonfirmasi(no, nama, poli, umur, jam) {
        const btn = event.currentTarget;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        btn.disabled = true;

        $.ajax({
            url: 'konfirmasi_aksi.php',
            type: 'POST',
            data: { no_antrean: no, nama: nama, poli: poli, umur: umur, jam_datang: jam },
            dataType: 'json',
            success: function(res) {
                if(res.status === 'success') {
                    confirmedData[no] = { urut: res.urut };
                    renderTable();
                } else if(res.status === 'already_exists') {
                    alert('Pasien sudah dikonfirmasi petugas lain.');
                    loadData();
                }
            },
            error: function() {
                alert("Gagal koneksi server");
                loadData();
            }
        });
    }

    function resetAntrean() {
        if (!confirm('Hapus semua data antrean hari ini?')) return;
        $.ajax({
            url: 'reset_skrining_aksi.php',
            type: 'POST',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    alert('Berhasil direset!');
                    location.reload();
                }
            }
        });
    }

    setInterval(() => $('#clock').text(new Date().toLocaleTimeString('id-ID')), 1000);
    setInterval(loadData, 5000); 
    $(document).ready(loadData);
    $('#searchInput, #filterPoli').on('input change', () => {
        currentPage = 1; // Reset ke hal 1 setiap cari
        renderTable();
    });
</script>
</body>
</html>