<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Sistem Antrean Puskesmas Tamansari</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="https://puskesmastamansari.boyolali.go.id/files/setting/thumb/190_115-1773108375-Logo_Puskesmas_Tanpa_Background.png">

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #2ecc71;
            --accent-color: #3498db;
            --bg-light: #f4f7f6;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            padding-top: 90px; /* Ruang agar konten tidak tertutup navbar fixed */
        }

        /* NAVBAR CUSTOM */
        .navbar {
            background: rgba(44, 62, 80, 0.96) !important;
            backdrop-filter: blur(10px);
            border-bottom: 3px solid var(--secondary-color);
        }

        .navbar-brand img {
            width: 35px;
            margin-right: 10px;
        }

        /* MENU CARDS */
        .menu-card {
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            border: none;
            border-radius: 15px;
            height: 100%;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 25px 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            background: white;
        }

        .menu-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.1);
            color: inherit;
        }

        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            font-size: 1.8rem;
            transition: 0.3s;
        }

        .menu-card:hover .icon-box {
            transform: scale(1.1);
        }

        /* Grouping Colors */
        .bg-monitor { background-color: #e8f5e9; color: #2e7d32; }
        .bg-caller { background-color: #fff3e0; color: #ef6c00; } 
        
        .section-title {
            border-left: 5px solid var(--secondary-color);
            padding-left: 15px;
            margin-bottom: 30px;
            font-weight: 800;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-title {
            font-size: 0.85rem;
            font-weight: 700;
            text-align: center;
            color: #333;
            margin: 0;
            text-transform: uppercase;
        }

        footer {
            background: white;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="https://puskesmastamansari.boyolali.go.id/files/setting/thumb/190_115-1773108375-Logo_Puskesmas_Tanpa_Background.png" alt="Logo">
            <div>
                <span class="fw-bold">Sistem Antrean </span>
                <small class="d-block text-white-50" style="font-size: 0.65rem;">PUSKESMAS TAMANSARI BOYOLALI</small>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto text-uppercase small fw-bold">
                <li class="nav-item"><a class="nav-link px-3" href="#"><i class="bi bi-house-door me-1"></i> Beranda</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#monitor"><i class="bi bi-display me-1"></i> Monitor</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#petugas"><i class="bi bi-person-badge me-1"></i> Petugas</a></li>
                <li class="nav-item ms-lg-3">
                    <span id="liveClock" class="nav-link text-warning border border-warning rounded-pill px-3">00:00:00</span>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    
    <section id="monitor" class="mb-5">
        <h3 class="section-title">Monitor Tampilan TV Antrean Klaster</h3>
        <div class="row g-4">
            <div class="col-6 col-md-4 col-lg-3">
                <a href="home.php" target="_blank" class="menu-card bg-monitor">
                    <div class="icon-box bg-white shadow-sm"><i class="bi bi-grid-3x3-gap-fill"></i></div>
                    <p class="card-title">TV Besar Semua Klaster</p>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="monitor_gigi.php" target="_blank" class="menu-card bg-monitor">
                    <div class="icon-box bg-white shadow-sm"><i class="bi bi-person-bounding-box"></i></div>
                    <p class="card-title">TV Pelayanan Gigi</p>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="monitor_kia.php" target="_blank" class="menu-card bg-monitor">
                    <div class="icon-box bg-white shadow-sm"><i class="bi bi-gender-female"></i></div>
                    <p class="card-title">TV KIA</p>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="monitor_k3_selatan.php" target="_blank" class="menu-card bg-monitor">
                    <div class="icon-box bg-white shadow-sm"><i class="bi bi-arrow-down-circle"></i></div>
                    <p class="card-title">TV K3 BP USIA LANSIA</p>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="monitor_k3_utara.php" target="_blank" class="menu-card bg-monitor">
                    <div class="icon-box bg-white shadow-sm"><i class="bi bi-arrow-up-circle"></i></div>
                    <p class="card-title">TV K3 BP USIA DEWASA</p>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="monitor_ugd.php" target="_blank" class="menu-card bg-monitor">
                    <div class="icon-box bg-white shadow-sm"><i class="bi bi-universal-access-circle"></i></div>
                    <p class="card-title">TV Gawat Darurat</p>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="monitor_farmasi.php" target="_blank" class="menu-card bg-monitor">
                    <div class="icon-box bg-white shadow-sm"><i class="bi bi-capsule"></i></div>
                    <p class="card-title">TV Farmasi</p>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="monitor_resep.php" target="_blank" class="menu-card bg-monitor">
                    <div class="icon-box bg-white shadow-sm"><i class="bi bi-receipt"></i></div>
                    <p class="card-title">TV Resep</p>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="monitor_laboratorium.php" target="_blank" class="menu-card bg-monitor">
                    <div class="icon-box bg-white shadow-sm"><i class="bi bi-eyedropper"></i></div>
                    <p class="card-title">TV Laboratorium</p>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="monitor_utara.php" target="_blank" class="menu-card bg-monitor">
                    <div class="icon-box bg-white shadow-sm"><i class="bi bi-sign-turn-left-fill"></i></div>
                    <p class="card-title">TV Ruang Sayap Utara (BP DEWASA, BP LANSIA + GIGI)</p>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="monitor_selatan.php" target="_blank" class="menu-card bg-monitor">
                    <div class="icon-box bg-white shadow-sm"><i class="bi bi-sign-turn-right-fill"></i></div>
                    <p class="card-title">TV Ruang Sayap Selatan (KIA + LAB)</p>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="https://tamansari.rf.gd" target="_blank" class="menu-card bg-monitor">
                    <div class="icon-box bg-white shadow-sm"><i class="bi bi-person-video3"></i></div>
                    <p class="card-title">TV Pendaftaran</p>
                </a>
            </div>
        </div>
    </section>

    <section id="petugas" class="mb-5">
        <h3 class="section-title">Panel Pemanggil (Petugas)</h3>
        <div class="row g-4">
            <div class="col-12 col-md-4">
                <a href="operator.php" target="_blank" class="menu-card bg-caller shadow-sm">
                    <div class="icon-box bg-white shadow-sm"><i class="bi bi-megaphone-fill"></i></div>
                    <p class="card-title">Pemanggil Ruang Periksa</p>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="panggil_farmasi.php" target="_blank" class="menu-card bg-caller shadow-sm">
                    <div class="icon-box bg-white shadow-sm"><i class="bi bi-prescription2"></i></div>
                    <p class="card-title">Pemanggil Farmasi</p>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="operator_resep.php" target="_blank" class="menu-card bg-caller shadow-sm">
                    <div class="icon-box bg-white shadow-sm"><i class="bi bi-receipt"></i></div>
                    <p class="card-title">Pemanggil Resep</p>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="panggil_lab.php" target="_blank" class="menu-card bg-caller shadow-sm">
                    <div class="icon-box bg-white shadow-sm"><i class="bi bi-eyedropper"></i></div>
                    <p class="card-title">Pemanggil Laboratorium</p>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="https://tamansari.rf.gd/loketA.php" target="_blank" class="menu-card bg-caller shadow-sm">
                    <div class="icon-box bg-white shadow-sm"><i class="bi bi-person-add"></i></div>
                    <p class="card-title">Pemanggil Pendaftaran</p>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="operator_pendaftaran.php" target="_blank" class="menu-card bg-caller shadow-sm">
                    <div class="icon-box bg-white shadow-sm"><i class="bi bi-file-person"></i></div>
                    <p class="card-title">Panel Konfirmasi Kehadiran</p>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="operator_skrining.php" target="_blank" class="menu-card bg-caller shadow-sm">
                    <div class="icon-box bg-white shadow-sm"><i class="bi bi-heart-pulse-fill"></i></div>
                    <p class="card-title">Operator Skrining Pasien</p>
                </a>
            </div>
        </div>
    </section>

</div>

<footer class="text-center py-4 text-muted border-top">
    <div class="container">
        <small>&copy; 2026 Puskesmas Tamansari - Kabupaten Boyolali</small>
    </div>
</footer>

<script>
    // Fungsi Jam Digital
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('liveClock').innerText = `${hours}:${minutes}:${seconds}`;
    }
    setInterval(updateClock, 1000);
    updateClock(); // Jalankan langsung tanpa tunggu 1 detik
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>