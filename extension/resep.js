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
        if (cols.length < 19) return;[cite: 1]

        // Status berdasarkan class sesuai footer HTML resep
        let statusAntrean = 'Proses';
        if (row.classList.contains('success')) {
            statusAntrean = 'Selesai';[cite: 1]
        }

        // Penyesuaian Indeks berdasarkan HTML resep yang diupload
        const noAntrean = cols[2]?.innerText.trim() || "-"; // No. Antrean[cite: 1]
        const jam       = cols[3]?.innerText.trim() || "-"; // Tanggal Resep[cite: 1]
        const nama      = cols[4]?.innerText.trim() || "-"; // Nama Pasien[cite: 1]
        const asal      = cols[17]?.innerText.trim() || "-"; // Ruangan Asal[cite: 1]

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
        // Mengirim ke file simpan baru: simpan_antrean_res.php
        fetch('http://localhost:8080/simpan_antrean_res.php', {
            method: 'POST',
            body: JSON.stringify(dataKeirim),
            headers: { 'Content-Type': 'application/json' }
        })
        .then(res => res.json())
        .then(res => console.log("✅ [DEBUG] Respon Server Resep:", res))
        .catch(err => console.error("❌ [DEBUG] Fetch Error:", err));
    }
}
setInterval(syncResep, 5000);