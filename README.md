# LKS PBO - Sistem Perpustakaan

## Identitas

- Nama: Fahriello
- NIM: H1101251009
- Kelas: B
- Mata kuliah: LKS PBO, Sistem Informasi, UNTAN

## Deskripsi

Aplikasi perpustakaan sederhana yang terdiri dari REST API (PHP) dan frontend (HTML, Tailwind CSS, JavaScript).

## Fitur

- REST API PHP untuk data Buku dan Kategori (GET, POST, PUT, DELETE)
- Header CORS sehingga frontend (port 3000) dapat memanggil backend (port 8000)
- Dashboard: total buku, jumlah kategori, dan 5 buku terbaru
- Halaman Buku: tabel data, tambah, edit, dan hapus lewat modal
- Halaman Kategori: tabel data, tambah, edit, dan hapus lewat modal
- Notifikasi toast untuk setiap operasi

## Cara Menjalankan

Prasyarat: PHP, Composer, dan MySQL atau MariaDB.

### 1. Database

Buat database `perpustakaan` dengan tabel `books` dan `categories`.

### 2. Backend (port 8000)

    cd rest-api-perpustakaan
    composer install

Buat file `.env` berisi konfigurasi database (file ini tidak ikut di-push), lalu jalankan:

    php -S localhost:8000 -t public

### 3. Frontend (port 3000)

Buka terminal baru:

    cd frontend-perpustakaan
    php -S localhost:3000

Buka `http://localhost:3000` di browser.

## Screenshot Aplikasi Final

- [Dashboard](screenshot-final/dashboard.png)
- [Halaman Buku](screenshot-final/halaman-buku.png)
- [Halaman Kategori](screenshot-final/halaman-kategori.png)

## Screenshot Tiap Pertemuan

### pertemuan-01
- [langkah-01.png](pertemuan-01/langkah-01.png)
- [langkah-02.png](pertemuan-01/langkah-02.png)
- [langkah-03.png](pertemuan-01/langkah-03.png)
- [langkah-04.png](pertemuan-01/langkah-04.png)
- [langkah-05.png](pertemuan-01/langkah-05.png)

### pertemuan-02
- [langkah-06.png](pertemuan-02/langkah-06.png)
- [langkah-07.png](pertemuan-02/langkah-07.png)
- [langkah-08.png](pertemuan-02/langkah-08.png)
- [langkah-09.png](pertemuan-02/langkah-09.png)

### pertemuan-03
- [langkah-10.png](pertemuan-03/langkah-10.png)
- [langkah-11.png](pertemuan-03/langkah-11.png)

### pertemuan-04
- [langkah-12.png](pertemuan-04/langkah-12.png)

### pertemuan-05
- [langkah-13.png](pertemuan-05/langkah-13.png)
- [langkah-14.png](pertemuan-05/langkah-14.png)
- [langkah-15.png](pertemuan-05/langkah-15.png)

### pertemuan-06
- [langkah-16.png](pertemuan-06/langkah-16.png)
- [langkah-17.png](pertemuan-06/langkah-17.png)
- [langkah-18.png](pertemuan-06/langkah-18.png)

### pertemuan-07
- [langkah-19.png](pertemuan-07/langkah-19.png)

- [langkah-20.png](pertemuan-07/langkah-20.png)
