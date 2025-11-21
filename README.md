# Technical Documentation & Setup

Dokumentasi ini fokus pada struktur teknis, _tech stack_, dan cara menjalankan aplikasi (Local Development).

---

## Tech Stack & Frameworks

### Core

- **PHP** (Native) - Logika backend
- **MySQL** - Database management
- **HTML5 & CSS3** - Struktur dasar

### Styling & Tooling

- **[Tailwind CSS](https://tailwindcss.com/)** (v3.x) - Utility-first CSS framework
- **Node.js & NPM** - Package manager untuk mengelola Tailwind
- **Concurrently** - Menjalankan PHP Server & Tailwind Watcher secara bersamaan

---

## Struktur Folder

Berikut adalah struktur direktori proyek yang digunakan (Non-MVC / Modular Pattern):

```
/nama-project
├── assets/
│   ├── css/
│   │   ├── input.css       # Source Tailwind (@tailwind directives)
│   │   └── style.css       # Output CSS (Hasil compile, jangan diedit manual)
│   ├── img/                # Aset gambar
│   └── js/                 # Aset JavaScript custom
│
├── config/
│   └── connect.php         # Konfigurasi koneksi database
│
├── functions/
│   └── func.php            # Kumpulan fungsi PHP (CRUD, Helpers)
│
├── includes/
│   ├── header.php          # Potongan HTML Head & Navbar
│   └── footer.php          # Potongan HTML Footer & Script
│
├── node_modules/           # Dependencies Node.js (Di-ignore oleh Git)
├── index.php               # Halaman Utama
├── package.json            # Skrip NPM (Server & Build)
├── tailwind.config.js      # Konfigurasi Tailwind
└── .gitignore              # File yang diabaikan Git
```

---

## Cara Menjalankan Project (How to Run)

Karena project ini menggunakan **Tailwind CSS** dan **PHP Native** secara bersamaan, ikuti langkah berikut agar fitur _auto-refresh_ dan styling berjalan lancar.

### 1. Persiapan Awal (Prerequisites)

Pastikan software berikut sudah terinstall di komputer:

- **XAMPP** (untuk Database MySQL & PHP)
- **Node.js** (Wajib ada untuk memproses Tailwind CSS)

### 2. Setup Database

1. Buka **XAMPP Control Panel**, nyalakan module **Apache** dan **MySQL**
2. Buka browser akses `http://localhost/phpmyadmin`
3. Buat database baru (sesuai nama di file `config/connect.php`)
4. Import file database `.sql` (jika ada)

### 3. Install Library (Hanya saat pertama kali)

Jika ini pertama kalinya folder dibuka setelah download/clone, kita perlu menginstall library Node.js.

1. Klik kanan di area kosong folder project, pilih **Open in Terminal** (atau Git Bash)
2. Ketik perintah berikut dan tunggu sampai selesai:

```bash
npm install
```

> Folder `node_modules` akan muncul otomatis setelah ini.

### 4. Jalankan Aplikasi (Mode Development)

Kita menggunakan perintah khusus agar PHP Server dan Tailwind berjalan bersamaan.

1. Di terminal (folder project), ketik perintah:

```bash
npm run dev
```

2. Terminal akan menampilkan status server berjalan
3. Buka browser dan akses alamat yang muncul (biasanya):

   👉 **http://localhost:8000**

> **Catatan:**
>
> - Jangan tutup terminal selama proses coding
> - Jika mengubah class Tailwind di file PHP, tampilan di browser akan otomatis terupdate setelah di-save
> - Tekan `CTRL + C` di terminal untuk mematikan server

---

## Anggota Kelompok

<div align="center">
  <table>
    <tr>
      <td align="center" width="160px">
        <a href="https://github.com/suryamaulana98">
          <img src="https://github.com/suryamaulana98.png" width="120px;" alt="Foto Anggota 1"/>
          <br />
          <sub><b>Surya Maulana Akhmad</b></sub>
        </a>
        <br />
        <sub>NIM: 240411100160</sub>
      </td>
      <td align="center" width="160px">
        <a href="https://github.com/zeedandp">
          <img src="https://github.com/zeedandp.png" width="120px;" alt="Foto Anggota 2"/>
          <br />
          <sub><b>M Zidan Dhikrulloh P</b></sub>
        </a>
        <br />
        <sub>NIM: 240411100083</sub>
      </td>
      <td align="center" width="160px">
        <a href="https://github.com/RByakin">
          <img src="https://github.com/RByakin.png" width="120px;" alt="Foto Anggota 3"/>
          <br />
          <sub><b>RB. Ainul Yakin</b></sub>
        </a>
        <br />
        <sub>NIM: 240411100129</sub>
      </td>
      <td align="center" width="160px">
        <a href="https://github.com/Roti18">
          <img src="https://github.com/Roti18.png" width="120px;" alt="Foto Anggota 3"/>
          <br />
          <sub><b>Moch. Zamroni Fahreza</b></sub>
        </a>
        <br />
        <sub>NIM: 240411100085</sub>
      </td>
    </tr>
  </table>
</div>
