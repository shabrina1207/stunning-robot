<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Kasir - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/staff.css') }}" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .navbar-brand { font-weight: 700; color: #ff6b00 !important; }
        .cashier-navbar { border-bottom: 1px solid #eceff1; }
        .cashier-navbar .staff-nav-link:hover { color: #ff6b00; border-bottom-color: currentColor; }
        .card-order { border: 1px solid #eceff1; border-radius: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.035); }
        .badge-status { font-size: 0.85rem; padding: 6px 12px; border-radius: 20px; }
        .list-unstyled .border-bottom { border-color: #eceff1 !important; }
        .brand-lockup { display: inline-flex; align-items: center; gap: 10px; color: #252525; }
        .brand-logo { width: 46px; height: 46px; border-radius: 50%; object-fit: cover; }
        .brand-name { font-size: 1.05rem; font-weight: 700; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white px-4 mb-4 cashier-navbar">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-3">
            @include('partials.brand', ['href' => route('admin.dashboard')])
            <span class="navbar-brand mb-0">DASHBOARD KASIR</span>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="staff-nav-link">Keluar</button>
        </form>
    </div>
</nav>

<div class="container pb-5">
    <h4 class="fw-bold mb-3">Daftar Pesanan Masuk</h4>

    @if($orders->isEmpty())
        <div class="alert alert-warning text-center py-4 rounded-3">
            Belum ada pesanan yang masuk saat ini.
        </div>
    @else
        <div class="row g-3">
            @foreach($orders as $order)
            <div class="col-md-6">
                <div class="card card-order p-4 bg-white">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="badge bg-secondary mb-1">{{ $order->kode_transaksi ?? 'TRX-'.$order->id }}</span>
                            <h5 class="fw-bold mb-0">{{ $order->nama_pemesan ?? $order->customer_name }}</h5>
                            <small class="text-muted">Meja: {{ $order->nomor_meja ?? 'Meja -' }} | Metode: <span class="text-uppercase fw-bold">{{ $order->metode_pembayaran ?? $order->payment_method }}</span></small>
                        </div>
                        <div>
                            @if($order->status == 'pending')
                                <span class="badge bg-warning text-dark badge-status">Pending</span>
                            @elseif($order->status == 'lunas' || $order->status == 'paid')
                                <span class="badge bg-success badge-status">Lunas</span>
                            @else
                                <span class="badge bg-info badge-status">{{ ucfirst($order->status) }}</span>
                            @endif
                        </div>
                    </div>

                    <hr class="my-2">

                    <div class="mb-3">
                        <small class="fw-bold text-muted d-block mb-1">Rincian Menu:</small>
                        <ul class="list-unstyled small mb-0">
                            @foreach($order->orderDetails as $detail)
                                <li class="d-flex justify-content-between py-1 border-bottom">
                                    <span>{{ $detail->menu->nama_menu ?? 'Menu' }} (x{{ $detail->jumlah ?? $detail->qty ?? $detail->quantity }})</span>
                                    <span class="fw-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div>
                            <span class="text-muted small d-block">Total Tagihan</span>
                            <span class="fw-bold text-warning fs-5">Rp {{ number_format($order->total_harga ?? $order->total, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <!-- Tombol aksi kasir akan kita aktifkan di Tahap 3 -->
                            <button class="btn btn-sm btn-outline-success rounded-pill px-3" disabled>Kelola Pesanan</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
