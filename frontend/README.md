# TitipIn - Frontend Vue.js

Vue 3 + Vite + Vue Router + Axios. Memakai API Laravel TitipIn (folder `backend/`).

## Menjalankan
```bash
cd frontend
cp .env.example .env        # atur VITE_API_URL ke alamat API Laravel
npm install                 # membuat package-lock.json (commit file ini untuk npm ci di CI)
npm run dev                 # http://localhost:5173
npm run build               # hasil build di dist/
```
Pastikan backend berjalan (`php artisan serve`) lalu login `admin@titipin.test` / `password123`.

## Konfigurasi
| Variabel | Contoh | Fungsi |
|---|---|---|
| VITE_API_URL | http://localhost:8000/api | Alamat dasar API (dibaca di `src/api/http.js`) |
