# Deployment Laravel ke Vercel

Konfigurasi deployment memakai Docker + FrankenPHP. Jalur ini menjalankan seluruh aplikasi Laravel melalui `public/index.php`, sehingga file konfigurasi dan source code di luar `public` tidak menjadi document root.

## Prasyarat

- Repository sudah tersedia di GitHub, GitLab, atau Bitbucket.
- Akun Vercel.
- Database eksternal PostgreSQL atau MySQL. SQLite lokal tidak cocok untuk produksi di Vercel.
- Vercel CLI jika ingin deploy dari terminal: `npm i -g vercel`.

## Environment Variables Vercel

Tambahkan variabel berikut pada Environment `Production` dan `Preview` sesuai kebutuhan:

```text
APP_KEY=base64:...       # gunakan key yang stabil untuk satu environment
APP_URL=https://domain-anda.vercel.app
APP_ENV=production
APP_DEBUG=false
LOG_CHANNEL=stderr

DB_CONNECTION=pgsql      # atau mysql; otomatis pgsql jika memakai DATABASE_URL/POSTGRES_URL
DB_URL=postgresql://...  # dapat memakai DATABASE_URL atau POSTGRES_URL dari Neon
DB_HOST=...
DB_PORT=5432             # 3306 jika mysql
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
DB_SSLMODE=require       # umum untuk PostgreSQL managed

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync
```

Jangan menyalin file `.env` ke repository. `APP_KEY`, kredensial database, dan kredensial SMTP harus disimpan sebagai Environment Variables/Secrets di Vercel.

## Deploy pertama kali

```powershell
npm i -g vercel
vercel login
vercel link
vercel env add APP_KEY production
vercel env add APP_URL production
vercel deploy
```

Uji URL preview yang diberikan Vercel. Jika sudah benar, jalankan:

```powershell
vercel deploy --prod
```

Alternatifnya, import repository dari dashboard Vercel. Pastikan Root Directory menunjuk ke folder proyek ini dan file `Dockerfile`, `Caddyfile`, serta `vercel.json` ikut ter-commit.

## Migrasi database

Jalankan migrasi satu kali terhadap database produksi dari lingkungan yang memiliki PHP 8.3+ dan akses ke database:

```powershell
php artisan migrate --force
php artisan db:seed --force
```

Jangan menjalankan migrasi pada startup container karena beberapa instance dapat hidup bersamaan.

## Catatan operasional

- File yang ditulis ke filesystem container tidak permanen. Aplikasi saat ini hanya memakai gambar statis di `public/images`, tetapi upload baru harus dipindahkan ke object storage.
- Queue database tidak memiliki worker permanen di Vercel; konfigurasi deployment memakai `QUEUE_CONNECTION=sync`.
- Setelah deployment, cek `/up`, `/order`, dan `/login`.
