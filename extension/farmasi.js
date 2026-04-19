// extension/farmasi.js
function syncFarmasi() {
    console.log("🔍 [DEBUG] Memulai scan tabel farmasi...");
    const table = document.querySelector("table.datatable");
    
    if (!table) {
        console.warn("⚠️ [DEBUG] Tabel farmasi tidak ditemukan.");
        return;
    }

    const rows = table.querySelectorAll("tbody tr");
    const dataKeirim = [];

    rows.forEach((row) => {
        const cols = row.querySelectorAll("td");
        
        // Tabel Farmasi punya banyak kolom (15), kita pastikan kolomnya ada
        if (cols.length < 14) return;

        // Status: class 'success' berarti obat sudah diproses/resep selesai
        const statusAntrean = row.classList.contains('success') ? 'Selesai' : 'Proses';

        // Penyesuaian Indeks berdasarkan HTML yang kamu kirim sebelumnya:
        const jam      = cols[1]?.innerText.trim() || "-"; // Tanggal Obat Pasien
        const nama     = cols[2]?.innerText.trim() || "-"; // Nama Pasien
        const asal     = cols[11]?.innerText.trim() || "-"; // Ruangan Asal
        const noAntrean = cols[14]?.innerText.trim() || "-"; // No Antrean (Kolom terakhir)

        dataKeirim.push({
            no_antrean: noAntrean,
            poli: "FARMASI",
            ruangan_asal: asal,
            nama: nama,
            jam: jam,
            status: statusAntrean
        });
    });

    if (dataKeirim.length > 0) {
        console.log(`🚀 [DEBUG] Mengirim ${dataKeirim.length} data farmasi...`);
        fetch('http://localhost/Pemanggil_Puskesmas/simpan_antrean_far.php', {
            method: 'POST',
            body: JSON.stringify(dataKeirim),
            headers: { 'Content-Type': 'application/json' }
        })
        .then(res => res.json())
        .then(res => console.log("✅ [DEBUG] Respon Server Farmasi:", res))
        .catch(err => console.error("❌ [DEBUG] Fetch Error Farmasi:", err));
    }
}