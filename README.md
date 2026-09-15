# Evolusi PL - [Isi NIM Kamu di Sini]
Repository untuk tugas mata kuliah **Konstruksi & Evolusi Perangkat Lunak, 2026**.

## Aplikasi
Sebuah aplikasi web berbasis Laravel sederhana untuk demonstrasi alur integrasi Git Flow dan pipeline CI/CD. Aplikasi ini mengimplementasikan fitur CRUD (Create, Read, Update, Delete) satu tabel untuk pengelolaan data **Item**.

### Berkas-Berkas Utama
| Path | Keterangan |
|------|------------|
| `app/Models/Item.php` | Model database untuk entitas Item. |
| `app/Http/Controllers/ItemController.php` | Logika utama CRUD aplikasi. |
| `resources/views/items.blade.php` | Antarmuka pengguna (tabel, modal tambah, dan modal edit). |
| `routes/web.php` | Konfigurasi rute atau URL web. |
| `tests/Feature/ItemTest.php` | Skrip pengujian otomatis (Feature Test) PHPUnit. |

## Panduan Penggunaan Lokal

Aplikasi ini menggunakan framework **Laravel**. Pastikan Anda sudah menginstal PHP (versi >= 8.2) dan Composer di mesin lokal Anda.

1. **Install Dependensi**
   ```bash
   composer install
   ```
2. **Konfigurasi Environment**
   Buat salinan file lingkungan hidup (environment) dan hasilkan application key yang unik.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   > **Catatan**: Secara bawaan file `.env` Laravel menggunakan _driver_ `sqlite` atau `mysql`. Sesuaikan kredensial koneksi agar cocok dengan sistem di laptop Anda.

3. **Migrasi Database**
   Buat struktur tabel pada database.
   ```bash
   php artisan migrate
   ```

4. **Menjalankan Aplikasi**
   Nyalakan development server.
   ```bash
   php artisan serve
   ```
   Buka `http://127.0.0.1:8000` di peramban web Anda.

## Menjalankan Pengujian (Testing)
Pengujian fungsional dan unit yang dibuat untuk proyek ini terhubung langsung dengan alat bawaan Laravel. Tanpa perlu peramban web.
```bash
php artisan test
```

## Alur Branch (Git Flow)
Pengembangan tidak dilakukan secara sembarangan di branch utama (`main`). Alur pergerakan kodenya adalah:

`feature/<nama-fitur>` —PR—> `dev` —PR—> `main`

- `feature/<nama-fitur>`: Tempat bekerja sehari-hari. Merubah tampilan atau menambal error. Silakan push langsung ke branch ini.
- `dev`: Lingkungan untuk menyatukan semua branch fitur. Harus melalui proses Pull Request (PR) dari branch `feature`.
- `main`: Branch final dan stabil yang siap diproduksi (diberi nilai). Harus melalui PR dari branch `dev`.

## Alur Pipeline CI/CD (GitHub Actions)
Terdapat pipeline di dalam `.github/workflows/ci.yml` yang berjalan dengan 4 tahapan (*jobs*).
1. **build**: Menginstal modul dependensi awal aplikasi menggunakan Composer.
2. **test**: Menjalankan pengujian otomatis `php artisan test` dengan dukungan service container _MySQL_.
3. **staging**: Mensimulasikan proses _deployment_ awal menggunakan perintah-perintah SSH yang diarahkan ke berkas lokal `deploy.sh`.
4. **production**: Memiliki proses layaknya staging, **tetapi dijaga ketat**. Tahapan ini mem-filter dan hanya akan berjalan jika ada aktivitas (push/PR) pada branch `main` serta harus disetujui (approve) secara manual di UI *GitHub Environments* (required reviewers).

## Gaya Pesan Commit
Untuk menjaga agar histori rapi, ikuti format [Conventional Commits](https://www.conventionalcommits.org/):
- `feat:`     Menambahkan fitur baru (contoh: *feat: halaman CRUD berhasil dibentuk*)
- `fix:`      Memperbaiki bug aplikasi
- `test:`     Menambahkan atau mengubah kode pengujian
- `ci:`       Perubahan yang berkaitan dengan alur script CI/CD
- `docs:`     Penulisan dan merubah dokumen semacam README
- `chore:`    Mengubah pengaturan non-kode, memperbaharui package

## ⚠️ Peringatan Keamanan
Perhatikan selalu isi `.gitignore` Anda. Jangan sekalipun menyertakan kredensial rahasia (seperti Private Keys SSH `.pem`, `.key` ataupun file `.env` yang riil) ke dalam riwayat commit. Semua injeksi _secrets_ harus melalui fitur repositori seperti Actions Secrets, dan bukan di _hard-code_.
