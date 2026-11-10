<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard {{ ucfirst(auth()->user()->role) }} - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/staff.css') }}" rel="stylesheet">
    <style>
        body{background:#f7f7f5;color:#252525;font-family:Inter,'Segoe UI',sans-serif}.topbar,.panel,.stat{background:#fff;border:1px solid #ecebe7;border-radius:12px}.stat{padding:18px;height:100%}.stat-label{font-size:.82rem;color:#777}.stat-value{font-size:1.55rem;font-weight:700;margin-top:6px}.accent{color:#bd5b28}.btn-accent{background:#bd5b28;color:#fff}.btn-accent:hover{background:#a64b20;color:#fff}.brand-lockup{display:inline-flex;align-items:center;gap:10px;color:#252525}.brand-logo{width:52px;height:52px;border-radius:50%;object-fit:cover}.brand-name{font-size:1.1rem;font-weight:700;letter-spacing:-.02em}.table>:not(caption)>*>*{padding:.8rem .65rem}@media print{.no-print{display:none!important}}
    </style>
</head>
<body>
<main class="container py-4 py-lg-5">
    <header class="topbar dashboard-topbar p-3 p-md-4 d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 no-print">
        <div><div class="mb-2">@include('partials.brand', ['href' => route('admin.dashboard')])</div><h1 class="h4 fw-bold mb-1">Dashboard {{ ucfirst(auth()->user()->role) }}</h1><p class="text-secondary small mb-0">Ringkasan operasional dan penjualan bulan ini</p></div>
        <div class="w-100">@include('admin.partials.navigation')</div>
    </header>
    @if(session('success'))<div class="alert alert-success no-print">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger no-print">{{ $errors->first() }}</div>@endif

    <section class="row g-3 mb-4">
        <div class="col-6 col-lg-3"><div class="stat"><div class="stat-label">Penjualan hari ini</div><div class="stat-value accent">Rp {{ number_format($penjualanHariIni,0,',','.') }}</div></div></div>
        <div class="col-6 col-lg-3"><div class="stat"><div class="stat-label">Penjualan bulan ini</div><div class="stat-value">Rp {{ number_format($penjualanBulanan,0,',','.') }}</div></div></div>
        <div class="col-6 col-lg-3"><div class="stat"><div class="stat-label">Pesanan hari ini</div><div class="stat-value">{{ $totalPesananHariIni }}</div></div></div>
        <div class="col-6 col-lg-3"><div class="stat"><div class="stat-label">Pesanan selesai</div><div class="stat-value">{{ $pesananSelesai }}</div></div></div>
    </section>

    <section class="row g-4 mb-4">
        <div class="col-lg-4"><div class="panel p-3 p-md-4 h-100"><div class="d-flex justify-content-between align-items-start mb-3"><div><h2 class="h6 fw-bold mb-1">Menu terlaris</h2><p class="small text-secondary mb-0">Berdasarkan item terjual bulan ini</p></div><span class="small text-secondary">{{ now()->translatedFormat('F Y') }}</span></div>
            @forelse($menuTerlaris as $index => $menu)<div class="d-flex justify-content-between align-items-center py-2 border-bottom"><div><span class="text-secondary small me-2">{{ $index + 1 }}.</span><span class="fw-semibold">{{ $menu->nama_menu }}</span></div><div class="text-end"><div class="fw-semibold">{{ number_format($menu->jumlah_terjual,0,',','.') }} terjual</div><div class="text-secondary small">Rp {{ number_format($menu->omzet,0,',','.') }}</div></div></div>@empty<div class="small text-secondary py-3">Belum ada penjualan terverifikasi bulan ini.</div>@endforelse
        </div></div>
        <div class="col-lg-8"><div class="panel p-3 p-md-4 h-100"><div class="d-flex justify-content-between align-items-center mb-3"><div><h2 class="h6 fw-bold mb-1">Pesanan terbaru</h2><p class="small text-secondary mb-0">Perbarui status pesanan dari daftar ini</p></div></div>
            <form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex gap-2 mb-3 no-print"><input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control form-control-sm" style="max-width:190px"><button class="btn btn-accent btn-sm">Filter</button>@if(request('tanggal'))<a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">Reset</a>@endif</form>
            <div class="table-responsive"><table class="table table-hover align-middle small mb-0"><thead class="table-light"><tr><th>Pesanan</th><th>Item</th><th>Total</th><th>Pembayaran</th><th>Status</th><th class="no-print">Ubah status</th></tr></thead><tbody>
            @forelse($orders as $order)@php $st=strtolower($order->status); @endphp<tr><td><div class="fw-semibold">#{{ $order->id }} · {{ $order->nama_pemesan ?? $order->customer_name }}</div><div class="text-secondary">{{ $order->created_at->format('d M Y, H:i') }} · Meja {{ $order->nomor_meja ?? $order->table_id }}</div></td><td>@foreach($order->orderDetails as $det)<div>{{ $det->quantity ?? $det->qty ?? 1 }}x {{ $det->menu->nama_menu ?? 'Menu' }}</div>@endforeach</td><td class="fw-semibold">Rp {{ number_format($order->total_harga ?? $order->total ?? $order->total_amount,0,',','.') }}</td><td><div>{{ ucfirst($order->metode_pembayaran ?? $order->payment_method) }}</div><span class="badge {{ $order->status_verifikasi==='verified'?'text-bg-success':($order->status_verifikasi==='rejected'?'text-bg-danger':'text-bg-warning') }}">{{ $order->status_verifikasi==='verified'?'Terverifikasi':($order->status_verifikasi==='rejected'?'Ditolak':'Menunggu') }}</span></td><td><span class="badge {{ $st==='selesai'?'text-bg-success':($st==='diproses'?'text-bg-info':'text-bg-secondary') }}">{{ ucfirst($st) }}</span></td><td class="no-print"><form action="{{ route('admin.order.status',$order->id) }}" method="POST">@csrf<select name="status" class="form-select form-select-sm" onchange="this.form.submit()"><option value="pending" {{ $st==='pending'?'selected':'' }}>Menunggu</option><option value="diproses" {{ $st==='diproses'?'selected':'' }}>Diproses</option><option value="selesai" {{ $st==='selesai'?'selected':'' }}>Selesai</option></select></form></td></tr>
            @empty<tr><td colspan="6" class="text-center text-secondary py-4">Belum ada pesanan.</td></tr>@endforelse
            </tbody></table></div>
        </div></div>
    </section>
</main>
</body>
</html>
