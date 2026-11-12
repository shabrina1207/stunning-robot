# Deployment Laravel ke Vercel

Konfigurasi deployment memakai Docker + FrankenPHP. Jalur ini menjalankan seluruh aplikasi Laravel melalui `public/index.php`, sehingga file konfigurasi dan source code di luar `public` tidak menjadi document root.

Production memakai MySQL eksternal agar data transaksi pelanggan, kasir, dan admin tersimpan pada database yang sama. SQLite hanya digunakan saat proses build/demo lokal; SQLite tidak cocok untuk penyimpanan transaksi di Vercel.

## Prasyarat

- Repository sudah tersedia di GitHub, GitLab, atau Bitbucket.
- Akun Vercel.
- Database eksternal MySQL. SQLite lokal tidak cocok untuk produksi di Vercel.
- Vercel CLI jika ingin deploy dari terminal: `npm i -g vercel`.

## Environment Variables Vercel

Tambahkan variabel berikut pada Environment `Production` dan `Preview` sesuai kebutuhan:

```text
VERCEL_DEMO_MODE=false
APP_KEY=base64:...       # gunakan key yang stabil untuk satu environment
APP_URL=https://app-five-neon-79.vercel.app
APP_ENV=production
APP_DEBUG=false
LOG_CHANNEL=stderr

DB_CONNECTION=mysql
DB_URL=mysql://USERNAME:PASSWORD@HOST:3306/DATABASE
DB_HOST=HOST
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...

SESSION_DRIVER=cookie
CACHE_STORE=array
QUEUE_CONNECTION=sync
```

Jika sebelumnya pernah menambahkan `SESSION_DRIVER=database` atau `CACHE_STORE=database` di Vercel, hapus atau ubah keduanya agar tidak membutuhkan tabel `sessions` dan `cache`.

Jangan menyalin file `.env` ke repository. `APP_KEY`, kredensial database, dan kredensial SMTP harus disimpan sebagai Environment Variables/Secrets di Vercel.

## Menggunakan Railway MySQL

Buat service MySQL dari dashboard Railway, lalu salin connection string `MYSQL_URL` yang diberikan Railway ke Environment Variables Vercel. Gunakan environment `Production` dan `Preview` bila keduanya diperlukan:

```text
DB_CONNECTION=mysql
DB_URL=mysql://USERNAME:PASSWORD@HOST:PORT/DATABASE
MYSQL_URL=mysql://USERNAME:PASSWORD@HOST:PORT/DATABASE
VERCEL_DEMO_MODE=false
```

Jangan gunakan `DB_HOST=127.0.0.1`, karena itu menunjuk ke container Vercel, bukan ke Railway. Setelah variable disimpan, lakukan redeploy lalu jalankan migrasi MySQL satu kali dari komputer yang memiliki akses ke URL tersebut:

```powershell
php artisan migrate --force
php artisan db:seed --force
```

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
