function syncLab() {
    const table = document.querySelector("table.datatable");
    if (!table) return;

    const rows = table.querySelectorAll("tbody tr");
    const dataKeirim = [];

    rows.forEach(row => {
        const cols = row.querySelectorAll("td");
        if (cols.length < 5 || row.classList.contains('success')) return;

        const infoAntrean = cols[1]?.innerText || "";
        const noAntrean = infoAntrean.match(/No\. Antrean\s*:\s*(\d+)/)?.[1] || "-";
        const jam = infoAntrean.match(/\d{2}:\d{2}:\d{2}/)?.[0] || "-";

        dataKeirim.push({
            no_antrean: noAntrean,
            poli: "LABORATORIUM",
            dokter: cols[4]?.innerText.trim() || "-", // Ruangan Asal
            nama: cols[2]?.querySelector(".fw-700")?.innerText.trim() || "-",
            jam: jam
        });
    });

    if (dataKeirim.length > 0) {
        fetch('http://localhost/Antrean_Pusk/simpan_antrean.php', {
            method: 'POST',
            body: JSON.stringify(dataKeirim),
            headers: { 'Content-Type': 'application/json' }
        });
    }
}
setInterval(syncLab, 10000);