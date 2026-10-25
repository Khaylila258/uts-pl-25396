# TitipIn - Backend Laravel (Titipan Online)

REST API Laravel + Sanctum, plus halaman etalase barang (gaya Shopee/Tokopedia) di `/`.

## Pemasangan (proyek Laravel baru)
```bash
composer create-project laravel/laravel titipin
cd titipin
php artisan install:api            # memasang Sanctum + routes/api.php
# salin seluruh isi paket ini ke folder titipin (timpa file yang sama)
cp .env.example .env && php artisan key:generate   # jika belum
php artisan migrate --seed
php artisan serve
```
Buka http://localhost:8000 lalu login: `admin@titipin.test` / `password123`.

## Docker
```bash
docker compose up --build        # http://localhost:8000
```

## Endpoint
| Method | URL | Token | Keterangan |
|---|---|---|---|
| POST | /api/login | Tidak | 200 sukses, 401 salah, 422 validasi |
| POST | /api/logout | Ya | hapus token |
| GET | /api/items | Ya | filter: status, category, search, per_page |
| GET | /api/items/{id} | Ya | detail |
| POST | /api/items | Ya | 201 dibuat, 422 gagal validasi |
| PUT/PATCH | /api/items/{id} | Ya | ubah |
| DELETE | /api/items/{id} | Ya | hapus |

Tanpa token: 401. Semua respons JSON.

## Postman
Import `postman/TitipIn.postman_collection.json`, jalankan "Login" dulu (token tersimpan otomatis).

## Feature test
```bash
php artisan test
```
Berkas `tests/Feature/ItemApiTest.php` berisi 6 tes (login 200/401, tanpa token 401, tambah 201, validasi 422, ubah dan hapus).
