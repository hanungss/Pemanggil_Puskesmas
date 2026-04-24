// POIN 1: JANGAN DISENGGOL (Running Well)
function ambilDataEpuskesmas() {
    const table = document.querySelector("table");
    if (!table) return;

    const rows = table.querySelectorAll("tbody tr");
    const listAntrean = [];

    rows.forEach(row => {
        const cols = row.querySelectorAll("td");
        if (cols.length >= 13) { // Disesuaikan minimal 13 kolom untuk umur
            listAntrean.push({
                no_antrean: cols[1]?.innerText.trim() || "-",
                poli: cols[2]?.innerText.trim() || "-",
                dokter: cols[4]?.innerText.trim() || "-",
                nama: cols[9]?.innerText.split('\n')[0].trim() || "-",
                jam: cols[5]?.innerText.trim() || "-",
                umur: cols[12]?.innerText.replace(/[^0-9]/g, '').trim() || "-" // Tambah umur tahun saja
            });
        }
    });

    if (listAntrean.length > 0) {
        fetch('http://localhost/Pemanggil_Puskesmas/simpan_antrean.php', {
            method: 'POST',
            body: JSON.stringify(listAntrean),
            headers: { 'Content-Type': 'application/json' }
        })
        .then(res => res.json())
        .catch(err => console.error('❌ Gagal Poli:', err));
    }
}

// ROUTER UTAMA
const currentUrl = window.location.href;

setTimeout(() => {
    if (currentUrl.includes("/pelayanan")) {
        console.log("🚀 Sync: Poli Umum");
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