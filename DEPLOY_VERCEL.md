# Deployment Laravel ke Vercel

Konfigurasi deployment memakai Docker + FrankenPHP. Jalur ini menjalankan seluruh aplikasi Laravel melalui `public/index.php`, sehingga file konfigurasi dan source code di luar `public` tidak menjadi document root.

Mode cepat saat ini memakai SQLite yang dibuat dan diisi otomatis saat Docker image dibangun. Mode ini cocok untuk demo; data transaksi tidak dijamin permanen ketika instance Vercel diganti atau disk container dibersihkan. Untuk production, matikan `VERCEL_DEMO_MODE` dan gunakan PostgreSQL/Neon.

## Prasyarat

- Repository sudah tersedia di GitHub, GitLab, atau Bitbucket.
- Akun Vercel.
- Database eksternal PostgreSQL atau MySQL. SQLite lokal tidak cocok untuk produksi di Vercel.
- Vercel CLI jika ingin deploy dari terminal: `npm i -g vercel`.

## Environment Variables Vercel

Tambahkan variabel berikut pada Environment `Production` dan `Preview` sesuai kebutuhan:

```text
VERCEL_DEMO_MODE=true
APP_KEY=base64:...       # gunakan key yang stabil untuk satu environment
APP_URL=https://domain-anda.vercel.app
APP_ENV=production
APP_DEBUG=false
LOG_CHANNEL=stderr

DB_CONNECTION=sqlite     # mode demo cepat; gunakan pgsql untuk production
DB_URL=postgresql://...  # hanya diperlukan untuk mode pgsql/production
DB_HOST=...
DB_PORT=5432             # 3306 jika mysql
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
DB_SSLMODE=require       # umum untuk PostgreSQL managed

SESSION_DRIVER=cookie
CACHE_STORE=array
QUEUE_CONNECTION=sync
```

Jika sebelumnya pernah menambahkan `SESSION_DRIVER=database` atau `CACHE_STORE=database` di Vercel, hapus atau ubah keduanya agar tidak membutuhkan tabel `sessions` dan `cache`.

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

Jalankan migrasi satu kali terhadap database produksi dari lingkungan yang memiliki PHP 8.3+ dan akses ke database. Session dan cache tidak perlu dimigrasikan karena deployment memakai cookie/array:

```powershell
php artisan migrate --force
php artisan db:seed --force
```

Jangan menjalankan migrasi pada startup container karena beberapa instance dapat hidup bersamaan.

## Catatan operasional

- File yang ditulis ke filesystem container tidak permanen. Aplikasi saat ini hanya memakai gambar statis di `public/images`, tetapi upload baru harus dipindahkan ke object storage.
- Queue database tidak memiliki worker permanen di Vercel; konfigurasi deployment memakai `QUEUE_CONNECTION=sync`.
- Setelah deployment, cek `/up`, `/order`, dan `/login`.
