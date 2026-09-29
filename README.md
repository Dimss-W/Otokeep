# 🚗 OtoKeep - Smart Vehicle Maintenance & Fleet Care Platform

<p align="center">
  <img src="public/assets/images/otokeep-logo-horizontal.png" alt="OtoKeep Logo" width="320">
  <br>
  <strong>Gak Ada Lagi Drama Lupa Servis. Solusi Cerdas Perawatan Motor & Mobil Berkala.</strong>
  <br>
  <em>Platform web responsif pemantauan kesehatan kendaraan, pengingat servis cerdas, buku servis digital, dan analitik armada operasional.</em>
</p>

---

## 🌟 Fitur Unggulan Proyek

1. **📱 Progressive Web App (PWA) Ready**
   - Dapat di-install langsung ke layar utama (*Add to Home Screen*) di smartphone (Android / iOS) maupun PC desktop tanpa melalui Google Play / App Store.
   - Dilengkapi *Service Worker* dan halaman *offline mode* yang ramah.
2. **📸 AI Speedometer / Odometer Scanner**
   - Pemindaian visual odometer riil kendaraan menggunakan kamera atau upload foto berbasis OCR AI cerdas untuk update KM otomatis.
3. **🔔 Pengingat Servis Cerdas (Smart Maintenance Tracker)**
   - Algoritma dinamis yang menghitung sisa jarak tempuh (KM) dan estimasi tanggal jatuh tempo penggantian komponen (Oli, Rem, CVT, Filter, Radiator, dll.).
   - Dilengkapi indikator status: *Prima*, *Mendekati Servis*, dan *Overdue / Perlu Servis*.
4. **📄 Buku Servis Digital Resmi (Cetak / Ekspor PDF A4)**
   - Dokumen rekam jejak servis (*service record*) bersertifikat resmi berstandar A4 yang siap dicetak langsung atau disimpan menjadi PDF untuk menaikkan nilai jual kendaraan bekas.
5. **💰 Personal Expense & Budget Tracker**
   - Rekapitulasi finansial perawatan kendaraan: Total biaya, pengeluaran tahun berjalan, grafik tren pengeluaran bulanan (Jan - Des), dan diagram donut alokasi biaya suku cadang.
6. **🤖 Bang OTO (Asisten AI Mekanik Kendaraan)**
   - Konsultasi teknis interaktif 24/7 seputar keluhan mesin, indikator speedometer, dan tips perawatan motor dan mobil.
7. **📊 Admin Fleet Intelligence Dashboard**
   - Dashboard analitik armada admin dengan pembaruan data *realtime auto-sync*:
     - KPI Total Pengguna & Unit Terdaftar
     - Grafik Garis Tren Pertumbuhan Armada (filter 7, 30, dan 90 hari)
     - Grafik Batang Model Kendaraan Terpopuler
     - Grafik Donut Karakteristik Jarak Tempuh Odometer & Tab Transmisi
     - Master CRUD Kategori Komponen Servis
     - CMS Publikasi Artikel Rekomendasi & Edukasi Kendaraan

---

## 🛠️ Prasyarat Sistem (Prerequisites)

Sebelum memulai instalasi, pastikan lingkungan komputer / server Anda telah terpasang:
- **PHP** >= 8.1 (dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `curl`)
- **Composer** (Package Manager PHP) >= 2.x
- **MySQL / MariaDB** (bisa menggunakan bawaan **Laragon** atau **XAMPP**)
- **Node.js** >= 18.x dan **NPM**
- **Git**

---

## 🚀 Panduan Instalasi Langkah Demi Langkah (Step-by-Step)

Ikuti langkah-langkah di bawah ini dari awal untuk menjalankan proyek Otokeep di komputer lokal Anda:

### 1. Clone Repositori dari GitHub
Buka terminal (Git Bash, Command Prompt, atau PowerShell), lalu jalankan:
```bash
git clone https://github.com/Dimss-W/Otokeep.git
cd Otokeep
```

---

### 2. Install Dependensi PHP (Composer)
Unduh seluruh library dan dependensi backend Laravel:
```bash
composer install
```

---

### 3. Buat File Konfigurasi Environment (`.env`)
Salin file template `.env.example` menjadi `.env`:
```bash
# Untuk Windows Command Prompt / PowerShell:
copy .env.example .env

# Atau jika menggunakan Git Bash / Linux / Mac:
cp .env.example .env
```

---

### 4. Buat Database Baru di MySQL
1. Buka aplikasi **Laragon** (klik *Start All*) atau **XAMPP** (nyalakan *Apache* & *MySQL*).
2. Buka **phpMyAdmin** di browser melalui `http://localhost/phpmyadmin`.
3. Buat database baru bernama:
   ```text
   db_otokeep
   ```
4. Buka file `.env` di text editor (VS Code, Notepad, dll.), lalu pastikan konfigurasi database sudah sesuai:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_otokeep
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   *(Kosongkan `DB_PASSWORD` jika menggunakan default Laragon/XAMPP tanpa password).*

---

### 5. Generate Application Key Laravel
Buat kunci enkripsi aplikasi:
```bash
php artisan key:generate
```

---

### 6. Migrasi & Isi Database (Pilih Salah Satu Cara)

#### 👉 Opsi A: Migrasi Skema Bersih + Seeder Bawaan (Direkomendasikan)
Jalankan migrasi tabel dan seeding data akun default:
```bash
php artisan migrate --seed
```

#### 👉 Opsi B: Import Langsung File SQL Lengkap (Data Dummy Armada Lengkap)
Proyek ini sudah dilengkapi file database siap pakai di folder `database/db_otokeep.sql` (berisi 342 akun pengguna dummy, unit armada, dan riwayat servis riil):
- **Lewat phpMyAdmin**: Buka database `db_otokeep` ➔ Tab **Import** ➔ Pilih file `database/db_otokeep.sql` ➔ Klik **Import / Go**.
- **Atau Lewat Terminal**:
  ```bash
  mysql -u root db_otokeep < database/db_otokeep.sql
  ```

---

### 7. Install Dependensi Frontend & Build Aset
Install dependensi JavaScript dan lakukan kompilasi aset CSS/JS:
```bash
npm install
npm run build
```

---

### 8. Hubungkan Storage Link (Upload Gambar & Dokumen)
Buat symlink folder storage agar gambar kendaraan dan bukti servis dapat diakses publik:
```bash
php artisan storage:link
```

---

### 9. Jalankan Server Aplikasi Lokal
Jalankan server pengembangan Laravel:
```bash
php artisan serve
```

Aplikasi sekarang sudah aktif dan dapat dibuka melalui browser di:
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)**  
*(Jika menggunakan Laragon dengan virtual host, bisa diakses melalui `http://otokeep.test`)*.

---

## 🔐 Akun Default untuk Pengujian

| Peran (Role) | Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@otokeep.com` | `password` | Dashboard Armada, CMS Rekomendasi, Master Kategori Servis |
| **User (Demo)** | `user@otokeep.com` / Buat Akun Baru | `password` | Dashboard Kendaraan, Scan AI, Riwayat Servis, Cetak Buku Servis |

*Anda juga dapat langsung mendaftarkan akun baru melalui menu **Daftar Akun** di halaman utama.*

---

## 💡 Perintah Bantuan Tambahan

Jika terjadi kendala cache tampilan atau konfigurasi tidak terbaca:
```bash
# Membersihkan seluruh cache Laravel
php artisan optimize:clear

# Menjalankan mode hot-reload aset frontend saat coding
npm run dev
```

---

## 📄 Lisensi & Kontributor

- **Repository**: [https://github.com/Dimss-W/Otokeep](https://github.com/Dimss-W/Otokeep)
- **Author**: [Dimss-W](https://github.com/Dimss-W)
- **Framework**: Laravel 10, Tailwind CSS, Chart.js, Phosphor Icons, PWA Web Engine.
