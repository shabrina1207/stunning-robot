<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $judul }} - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/staff.css') }}" rel="stylesheet">
    <style>
        body { background: #f7f7f5; color: #252525; font-family: Inter, 'Segoe UI', sans-serif; }
        .panel, .stat { background: #fff; border: 1px solid #ecebe7; border-radius: 12px; }
        .stat { padding: 20px; height: 100%; }
        .btn-accent { background: #bd5b28; color: #fff; border-color: #bd5b28; }
        .btn-accent:hover { background: #a64b20; color: #fff; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body>
<main class="container py-4 py-lg-5">
    <header class="panel p-3 p-md-4 d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 no-print">
        <div>
            <div class="mb-2">@include('partials.brand', ['href' => route('admin.dashboard')])</div>
            <h1 class="h4 fw-bold mb-1">{{ $judul }}</h1>
            <p class="text-secondary small mb-0">Pendapatan dari transaksi yang sudah terverifikasi</p>
        </div>
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm ms-lg-auto">Cetak</button>
        <div class="w-100">@include('admin.partials.navigation')</div>
    </header>

    <section class="row g-3 mb-4">
        <div class="col-md-8">
            <div class="stat">
                <div class="small text-secondary">Total penjualan · {{ $periodeLabel }}</div>
                <div class="h2 fw-bold mt-2 mb-0" style="color:#bd5b28">Rp {{ number_format($pendapatan, 0, ',', '.') }}</div>
                <div class="small text-secondary mt-2">{{ $daftarTransaksi->count() }} transaksi terverifikasi</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel p-3 h-100 no-print">
                <form action="{{ route('admin.rekap') }}" method="GET" class="d-grid gap-2">
                    <label class="small text-secondary" for="periode">Periode rekap</label>
                    <select id="periode" name="periode" class="form-select form-select-sm">
                        <option value="harian" {{ $jenis === 'harian' ? 'selected' : '' }}>Harian</option>
                        <option value="bulanan" {{ $jenis === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                    <div id="filter-harian" class="{{ $jenis === 'harian' ? '' : 'd-none' }}">
                        <label class="small text-secondary mb-1" for="tanggal">Pilih tanggal</label>
                        <input id="tanggal" type="date" name="tanggal" value="{{ $filterTanggal }}" class="form-control form-control-sm">
                    </div>
                    <div id="filter-bulanan" class="{{ $jenis === 'bulanan' ? '' : 'd-none' }}">
                        <label class="small text-secondary mb-1" for="bulan">Pilih bulan</label>
                        <input id="bulan" type="month" name="bulan" value="{{ $filterBulan }}" class="form-control form-control-sm">
                    </div>
                    <button class="btn btn-accent btn-sm">Tampilkan rekap</button>
                </form>
            </div>
        </div>
    </section>

    <section class="panel p-3 p-md-4">
        <div class="mb-3">
            <h2 class="h6 fw-bold mb-1">Daftar transaksi</h2>
            <p class="small text-secondary mb-0">{{ $periodeLabel }}</p>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small"><tr><th>Waktu</th><th>ID transaksi</th><th>Pemesan</th><th>Meja</th><th>Metode pembayaran</th><th class="text-end">Total</th></tr></thead>
                <tbody class="small">
                @forelse($daftarTransaksi as $trx)
                    <tr>
                        <td>{{ $trx->created_at->format('d M Y, H:i') }}</td>
                        <td class="fw-semibold">#{{ $trx->id }}</td>
                        <td>{{ $trx->nama_pemesan ?? $trx->customer_name }}</td>
                        <td>{{ $trx->nomor_meja ?? $trx->table_id }}</td>
                        <td>{{ ucfirst($trx->metode_pembayaran ?? $trx->payment_method) }}</td>
                        <td class="fw-semibold text-end">Rp {{ number_format($trx->total_harga ?? $trx->total ?? $trx->total_amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-secondary py-4">Belum ada transaksi terverifikasi untuk periode ini.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
</main>
<script>
    document.getElementById('periode').addEventListener('change', function () {
        document.getElementById('filter-harian').classList.toggle('d-none', this.value !== 'harian');
        document.getElementById('filter-bulanan').classList.toggle('d-none', this.value !== 'bulanan');
    });
</script>
</body>
</html>
