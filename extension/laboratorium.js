// extension/laboratorium.js
function syncLab() {
    console.log("🔍 [DEBUG] Memulai scan tabel laboratorium...");
    const table = document.querySelector("table.datatable");
    
    if (!table) {
        console.warn("⚠️ [DEBUG] Tabel .datatable tidak ditemukan.");
        return;
    }

    const rows = table.querySelectorAll("tbody tr");
    const dataKeirim = [];

    rows.forEach((row, index) => {
        const cols = row.querySelectorAll("td");
        if (cols.length < 5) return;

        // LOGIKA STATUS: class 'success' di e-Puskesmas berarti baris hijau (selesai)
        const isSuccess = row.classList.contains('success');
        const statusAntrean = isSuccess ? 'Selesai' : 'Proses';

        const infoAntrean = cols[1]?.innerText || "";
        const noAntrean = infoAntrean.match(/No\. Antrean\s*:\s*(\d+)/)?.[1] || "-";
        const jam = infoAntrean.match(/\d{2}:\d{2}:\d{2}/)?.[0] || "-";
        const namaPasien = cols[2]?.querySelector(".fw-700")?.innerText.trim() || "-";
        const ruanganAsal = cols[4]?.innerText.trim() || "-";

        dataKeirim.push({
            no_antrean: noAntrean,
            poli: "LABORATORIUM",
            ruangan_asal: ruanganAsal,
            nama: namaPasien,
            jam: jam,
            status: statusAntrean
        });
    });

    if (dataKeirim.length > 0) {
        console.log(`🚀 [DEBUG] Mengirim ${dataKeirim.length} data ke server...`);
        fetch('http://localhost:8080/simpan_antrean_lab.php', {
            method: 'POST',
            body: JSON.stringify(dataKeirim),
            headers: { 'Content-Type': 'application/json' }
        })
        .then(res => res.json())
        .then(res => console.log("✅ [DEBUG] Respon Server:", res))
        .catch(err => console.error("❌ [DEBUG] Fetch Error:", err));
    } else {
        console.log("ℹ️ [DEBUG] Tidak ada data dalam tabel.");
    }
}