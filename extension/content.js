function ambilDataEpuskesmas() {
    const table = document.querySelector("table");

    if (!table) {
        console.log("❌ Tabel belum ditemukan");
        return;
    }

    const rows = table.querySelectorAll("tbody tr");
    console.log("Jumlah row:", rows.length);

    const listAntrean = [];

    rows.forEach(row => {
        const cols = row.querySelectorAll("td");

        if (cols.length >= 10) {
            listAntrean.push({
                no_antrean: cols[1]?.innerText.trim() || "-",
                poli: cols[2]?.innerText.trim() || "-",
                dokter: cols[4]?.innerText.trim() || "-",
                nama: cols[9]?.innerText.split('\n')[0].trim() || "-",
                jam: cols[5]?.innerText.trim() || "-"
            });
        }
    });

    console.log("Data diambil:", listAntrean);

    if (listAntrean.length === 0) {
        console.log("⚠️ Tidak ada data dikirim");
        return;
    }

    fetch('http://localhost/Pemanggil_Puskesmas/simpan_antrean.php', {
        method: 'POST',
        body: JSON.stringify(listAntrean),
        headers: { 'Content-Type': 'application/json' }
    })
    .then(res => res.json())
    .then(res => console.log("✅ Server:", res))
    .catch(err => console.error('❌ Gagal kirim:', err));
}

// Delay awal biar tabel sempat load
setTimeout(() => {
    ambilDataEpuskesmas();
    setInterval(ambilDataEpuskesmas, 10000);
}, 5000);