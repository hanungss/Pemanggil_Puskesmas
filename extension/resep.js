// resep.js
function syncResep() {
    console.log("🔍 [DEBUG] Memulai scan tabel resep...");
    const table = document.querySelector("table.datatable");
    
    if (!table) {
        console.warn("⚠️ [DEBUG] Tabel tidak ditemukan.");
        return;
    }

    const rows = table.querySelectorAll("tbody tr");
    const dataKeirim = [];

    rows.forEach((row) => {
        const cols = row.querySelectorAll("td");
        
        // Sesuai logika kamu, pastikan kolom tersedia (Tabel resep punya 20 kolom)
        if (cols.length < 14) return;

        // Status: class 'success' berarti obat sudah diproses/resep selesai
        const statusAntrean = row.classList.contains('success') ? 'Selesai' : 'Proses';

        // Penyesuaian Indeks berdasarkan struktur HTML tabel resep:
        // Index dimulai dari 0. Kolom ke-3 = index [2], Kolom ke-5 = index [4], dst.
        const noAntrean = cols[2]?.innerText.trim() || "-";
        const jam       = cols[3]?.innerText.trim() || "-";
        const nama      = cols[4]?.innerText.trim() || "-";
        const asal      = cols[17]?.innerText.trim() || "-";
        
        dataKeirim.push({
            no_antrean: noAntrean,
            poli: "RESEP",
            ruangan_asal: asal,
            nama: nama,
            jam: jam,
            status: statusAntrean
        });
    });

    if (dataKeirim.length > 0) {
        console.log(`🚀 [DEBUG] Mengirim ${dataKeirim.length} data resep...`);
        fetch('http://localhost:8080/simpan_antrean_res.php', {
            method: 'POST',
            body: JSON.stringify(dataKeirim),
            headers: { 'Content-Type': 'application/json' }
        })
        .then(res => res.json())
        .then(res => console.log("✅ [DEBUG] Respon Server:", res))
        .catch(err => console.error("❌ [DEBUG] Fetch Error:", err));
    }
}

// Jalankan otomatis setiap 5 detik
setInterval(syncResep, 5000);