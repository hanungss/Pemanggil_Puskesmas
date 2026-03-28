# 🏥 Sistem Antrean Puskesmas Tamansari (Operator Monitor)

Sistem antrean berbasis web yang dirancang khusus untuk manajemen pelayanan pasien di **Puskesmas Tamansari**. Aplikasi ini berfungsi sebagai dashboard operator untuk memantau, menyaring, dan memanggil pasien secara otomatis menggunakan teknologi *Text-to-Speech*.

---

## 📋 Deskripsi Proyek
Aplikasi ini dikembangkan dengan fokus pada **efisiensi operasional** dan **kemudahan akses**. Berbeda dengan sistem antrean konvensional yang berat, sistem ini menggunakan arsitektur ringan:
* **Backend:** PHP (XAMPP) tanpa database SQL (menggunakan `.txt` sebagai penyimpanan data sementara).
* **Frontend:** HTML5, CSS3 (Bootstrap 5), dan JavaScript (ES6+).
* **Fitur Suara:** Web Speech API untuk panggilan nama pasien secara natural tanpa perlu aset file MP3 tambahan.

## ✨ Fitur Utama
1.  **Tabel Dinamis & Real-time:** Memantau daftar pasien yang masuk secara otomatis setiap 5 detik.
2.  **Filter Cerdas:** * Pencarian teks berdasarkan Nama atau Nomor Antrean.
    * Dropdown otomatis untuk kategori Poli.
3.  **Logika Wilayah K3:** Secara otomatis membagi Poli "K3 - USIA DEWASA & LANSIA" menjadi wilayah **UTARA (DD)** dan **SELATAN (DC)** berdasarkan awalan nomor antrean.
4.  **Panggilan Suara (Voice):** Memanggil nama pasien dan poli tujuan dengan intonasi bahasa Indonesia yang natural (Human-like).
5.  **Responsif:** Tampilan yang optimal baik di monitor PC maupun tablet/layar sentuh.

---

## 🛠️ Instalasi & Persiapan

1.  **Requirement:**
    * XAMPP (PHP 7.4 atau yang lebih baru).
    * Browser modern (Google Chrome/Microsoft Edge direkomendasikan untuk fitur Suara).

2.  **Struktur Folder:**
    ```text
    /htdocs/antrean-tamansari/
    ├── operator.php      (Dashboard utama)
    ├── get_data.php      (Script penarik data dari .txt ke JSON)
    └── antrian.txt       (Database berbasis teks)
    ```

3.  **Cara Menjalankan:**
    * Copy seluruh file ke folder `C:/xampp/htdocs/antrean-tamansari/`.
    * Nyalakan **Apache** pada XAMPP Control Panel.
    * Buka browser dan akses `http://localhost/antrean-tamansari/operator.php`.

---

## ⚙️ Logika Panggilan Suara
Sistem menggunakan **Web Speech API** dengan konfigurasi:
* **Language:** `id-ID` (Bahasa Indonesia).
* **Rate:** `0.9` (Kecepatan natural).
* **Pitch:** `1.1` (Nada ramah).
* **Format Pesan:** *"Pasien atas nama [NAMA], silahkan menuju [POLI]."*

---

## 📝 Catatan Teknis (Development)
* Data ditarik dari `get_data.php` dalam format JSON.
* Pemisahan wilayah K3 dilakukan di sisi *Client-side* (JavaScript) untuk menjaga struktur data asli di file `.txt` tetap sederhana.
* Fitur *Voice* memerlukan interaksi pengguna (seperti klik tombol) pada halaman setidaknya satu kali setelah *refresh* karena kebijakan keamanan browser mengenai *autoplay audio*.

---

**Dikembangkan oleh:** Hanung  
**Update Terakhir:** Maret 2026