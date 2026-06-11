# CI Apps Logistics & RBAC

Aplikasi web berbasis CodeIgniter 3 untuk manajemen operasional logistik, RBAC, dispatch armada, tugas driver, inspeksi kendaraan, Proof of Delivery, dan pelacakan rute pengiriman dengan Google Maps.

## Tech Stack

- PHP 7.4
- CodeIgniter 3
- MySQL / MariaDB
- Apache / Laragon
- Bootstrap 5
- jQuery
- Font Awesome
- Toastr.js
- SweetAlert2
- AOS Animation
- Google Maps JavaScript API
- Google Places Autocomplete
- Google Directions API
- Browser Geolocation API

## Fitur Utama

- Autentikasi login, logout, remember me, lockout login, dan session security.
- RBAC dengan tabel `users`, `roles`, `permissions`, dan `role_permissions`.
- Dashboard sesuai role pengguna.
- Manajemen user, role, permission, dan kendaraan.
- Pembuatan order logistik dengan Google Maps picker dan autocomplete lokasi.
- Penyimpanan koordinat asal dan tujuan order.
- Dispatch driver dan armada.
- Workflow order: pending, allocated, ready, loading, in transit, arrived, POD submitted, completed.
- Inspeksi kelayakan kendaraan sebelum jalan.
- Verifikasi loading dan segel kontainer.
- Surat jalan.
- Upload dan verifikasi POD.
- Halaman driver mobile untuk tugas aktif.
- Logging GPS driver ke `vehicle_location_logs`.
- Detail order dengan simulasi rute jalan Google Maps dan breadcrumb GPS.
- Public tracking page.

## Kebutuhan Sistem

- PHP 7.4
- MySQL atau MariaDB
- Apache dengan `mod_rewrite`
- Composer
- Browser modern dengan dukungan JavaScript dan Geolocation
- Google Maps API key dengan layanan berikut aktif:
  - Maps JavaScript API
  - Places API
  - Directions API
  - Geocoding API

## Instalasi

1. Clone atau salin project ke folder web server.

```bash
c:\laragon\www\ci-apps
```

2. Install dependency Composer jika diperlukan.

```bash
composer install
```

3. Buat file `.env` di root project.

```env
GOOGLE_MAPS_API_KEY=isi_api_key_google_maps
```

4. Pastikan konfigurasi database di `application/config/database.php` sesuai environment lokal.

Default project:

```php
'hostname' => 'localhost',
'username' => 'root',
'password' => '',
'database' => 'db_ci_apps',
'dbdriver' => 'mysqli',
```

5. Jalankan SQL migration secara berurutan dari folder `database/migrations`.

```text
001_auth_rbac.sql
002_logistics_ops.sql
003_dummy_orders.sql
004_seed_drivers.sql
005_add_loading_verification.sql
006_add_gps_tracking.sql
```

6. Akses aplikasi melalui browser.

```text
http://localhost/ci-apps
```

## Akun Demo

Seeder awal menyediakan akun berikut:

| Role | Username | Email | Password |
|---|---|---|---|
| Super Admin | superadmin | superadmin@example.com | SuperAdmin@123 |
| Admin | admin | admin@example.com | Admin@123 |
| User | user | user@example.com | User@123 |

Role logistik tambahan seperti driver/checker dibuat melalui migration operasional.

## Route Penting

| Modul | URL |
|---|---|
| Login | `/login` |
| Dashboard Admin | `/admin/dashboard` |
| User Management | `/admin/users` |
| Role Management | `/admin/roles` |
| Permission Management | `/admin/permissions` |
| Vehicle Management | `/admin/vehicles` |
| Order Logistik | `/admin/orders` |
| Inspeksi | `/admin/inspections` |
| Tugas Driver | `/driver/tasks` |
| Public Tracking | `/track` |

## Struktur Project

```text
application/
  config/          Konfigurasi CodeIgniter, route, database, Google Maps key
  controllers/     Controller admin, auth, driver, dan tracking publik
  helpers/         Helper autentikasi dan utility
  libraries/       Auth_lib untuk session, RBAC, dan permission checking
  models/          Model user, role, permission, order, vehicle, inspection, POD
  views/           Template layout, dashboard, order, driver, tracking
database/
  migrations/      SQL schema dan seed data
system/            Core CodeIgniter 3
```

## Konfigurasi Google Maps

Form Order menggunakan Google Maps untuk:

- autocomplete asal dan tujuan,
- reverse geocoding saat klik peta,
- penyimpanan koordinat asal dan tujuan,
- preview rute kendaraan.

Detail Order menggunakan Google Directions API untuk menggambar rute jalan yang sesuai dengan koordinat order. Jika tersedia log GPS driver, sistem akan menampilkan breadcrumb rute aktual dari tabel `vehicle_location_logs`.

Pastikan `.env` tidak ikut commit. File ini sudah di-ignore melalui `.gitignore`.

## Catatan Pengembangan

- Gunakan `can('permission.slug')` untuk menampilkan aksi di view.
- Gunakan `require_permission('permission.slug')` pada controller untuk proteksi akses backend.
- Jangan hanya mengandalkan hidden menu untuk keamanan.
- Semua fitur mutasi penting sebaiknya memakai POST dan CSRF token.
- Koordinat order harus berasal dari field `origin_latitude`, `origin_longitude`, `destination_latitude`, dan `destination_longitude`.

## Lisensi

Project ini menggunakan CodeIgniter 3 sebagai framework dasar. Sesuaikan lisensi aplikasi sesuai kebutuhan organisasi.
