# Sistem Absensi Digital Mahasiswa Berbasis GPS Geofencing

## Tentang Project

Sistem Absensi Digital Mahasiswa merupakan aplikasi berbasis web yang dikembangkan untuk membantu proses presensi perkuliahan secara digital. Sistem ini memanfaatkan teknologi GPS Geofencing untuk memvalidasi lokasi mahasiswa saat melakukan presensi sehingga kehadiran dapat tercatat lebih akurat.

Project ini dikembangkan sebagai Tugas Akhir Program Studi D3 Manajemen Informatika.

---

## Fitur Mahasiswa

* Melakukan presensi hadir
* Presensi izin dan sakit
* Upload bukti izin atau sakit
* Validasi lokasi menggunakan GPS Geofencing
* Melihat status presensi setiap pertemuan
* Mendukung perkuliahan online dan offline

---

## Fitur Dosen

* Membuka presensi per pertemuan
* Menutup presensi per pertemuan
* Mengatur mode kuliah (Online / Offline)
* Monitoring kehadiran mahasiswa
* Melihat bukti izin dan sakit
* Rekap data presensi setiap pertemuan

---

## Teknologi yang Digunakan

* Laravel 12
* PHP
* MySQL
* Tailwind CSS
* JavaScript
* GPS Geofencing

---

## Struktur Database

Sistem menggunakan beberapa tabel utama:

* Mahasiswas
* Absensis
* Pengaturan Presensis

Relasi database:

* Mahasiswa memiliki banyak data absensi (One-to-Many)
* Pengaturan Presensi memiliki banyak data absensi (One-to-Many)

---

## Tampilan Sistem

### Dashboard Dosen

* Monitoring kehadiran mahasiswa
* Pengaturan buka dan tutup presensi
* Monitoring izin dan sakit

### Dashboard Mahasiswa

* Informasi pertemuan
* Presensi online dan offline
* Upload bukti izin dan sakit

---

## Pengembang

Liani Siti Lutfiah

Program Studi D3 Manajemen Informatika

Universitas Nasional PASIM

Tugas Akhir 2026
