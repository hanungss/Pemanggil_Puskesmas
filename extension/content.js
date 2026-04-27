// POIN 1: JANGAN DISENGGOL (Running Well)
function ambilDataEpuskesmas() {
    console.log("🔍 Memulai proses pengambilan data dari tabel...");

    const table = document.querySelector("table.datatable");
    if (!table) {
        console.warn("⚠️ Tabel tidak ditemukan! Pastikan halaman sudah termuat sempurna.");
        return;
    }

    const rows = table.querySelectorAll("tbody tr");
    const listAntrean = [];

    console.log(`📊 Ditemukan ${rows.length} baris dalam tabel.`);

    rows.forEach((row, index) => {
        const cols = row.querySelectorAll("td");
        
        // Log jumlah kolom pada baris pertama untuk memastikan indeks
        if (index === 0) {
            console.log(`📏 Baris pertama memiliki ${cols.length} kolom.`);
        }

        if (cols.length >= 13) {
            const rawUmur = cols[12]?.innerText || "";
            const cleanUmur = rawUmur.replace(/[^0-9]/g, '').trim();

            const dataPasien = {
                no_antrean: cols[1]?.innerText.trim() || "-",
                poli: cols[2]?.innerText.trim() || "-",
                dokter: cols[4]?.innerText.trim() || "-",
                nama: cols[9]?.innerText.split('\n')[0].trim() || "-",
                jam: cols[5]?.innerText.trim() || "-",
                umur: cleanUmur || "-" 
            };

            listAntrean.push(dataPasien);
            // Log tiap pasien yang berhasil diproses
            console.log(`✅ Berhasil memproses: ${dataPasien.no_antrean} - ${dataPasien.nama} (Umur: ${dataPasien.umur})`);
        } else {
            console.warn(`❌ Baris ${index + 1} dilewati karena kolom hanya ${cols.length} (Butuh minimal 13).`);
        }
    });

    if (listAntrean.length > 0) {
        console.log(`📤 Mengirim ${listAntrean.length} data ke simpan_antrean.php...`);

        fetch('http://localhost:8080/simpan_antrean.php', {
            method: 'POST',
            body: JSON.stringify(listAntrean),
            headers: { 'Content-Type': 'application/json' }
        })
        .then(res => res.json())
        .then(res => {
            console.log("🎉 Sinkronisasi Sukses:", res);
        })
        .catch(err => {
            console.error("❌ Gagal mengirim data ke server lokal:", err);
        });
    } else {
        console.warn("ℹ️ Tidak ada data antrean yang valid untuk dikirim.");
    }
}

// ROUTER UTAMA
const currentUrl = window.location.href;

setTimeout(() => {
    console.log("🌐 URL Terdeteksi:", currentUrl);

    if (currentUrl.includes("/pelayanan") || currentUrl.includes("/antrean")) {
        console.log("🚀 Menjalankan Sinkronisasi Otomatis (Poli)...");
        ambilDataEpuskesmas();
        setInterval(ambilDataEpuskesmas, 10000); 
    } 
    else if (currentUrl.includes("/laboratorium")) {
        console.log("🚀 Sync: Lab");
        if (typeof syncLab === "function") {
            syncLab();
            setInterval(syncLab, 10000);
        }
    } 
    else if (currentUrl.includes("/obatpasien")) {
        console.log("🚀 Sync: Farmasi");
        if (typeof syncFarmasi === "function") {
            syncFarmasi();
            setInterval(syncFarmasi, 10000);
        }
    }
}, 5000);
