# 📝 Todo and Note App

Aplikasi manajemen tugas dan catatan harian berbasis web yang sederhana, responsif, dan fungsional.

## 🚀 Fitur Utama

Aplikasi ini memiliki dua fitur utama yang terintegrasi dalam satu halaman dasbor:

### ✅ Todo List
- **CRUD:** Tambah, Baca, Edit, dan Hapus tugas.
- **Image Support:** Unggah gambar pendukung untuk setiap tugas.
- **Status:** Menandai tugas selesai (Checklist) dengan visual coret (strikethrough).

### 📒 Notes (Catatan)
- **Grid Layout:** Tampilan catatan rapi dengan kartu (Card).
- **Responsive:** Berubah menjadi 1 kolom di HP dan 3 kolom di Desktop.
- **Rich Content:** Mendukung judul, isi teks panjang, dan lampiran gambar.

## 🛠️ Teknologi yang Digunakan

- **Backend:** PHP Native
- **Database:** PostgreSQL
- **Frontend:** HTML5, Tailwind CSS (via CDN)
- **Server:** Apache (XAMPP) / Localhost

## 📂 Struktur Folder

```text
todoNote/
├── Handlers/           # Logika pemrosesan data (Create, Update, Delete)
│   ├── uploadTodo.php
│   ├── deleteNote.php
│   └── ...
├── uploads/            # Folder penyimpanan gambar user
├── todonote.sql 
├── index.php           # Halaman Utama (Dashboard)
├── koneksi.php         # Konfigurasi koneksi database
└── style.css           # (Opsional) Styling tambahan
