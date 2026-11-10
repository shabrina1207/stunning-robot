<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Pembayaran - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/staff.css') }}" rel="stylesheet">
    <style>body{background:#f7f7f5;color:#252525;font-family:Inter,'Segoe UI',sans-serif}.panel,.topbar{background:#fff;border:1px solid #ecebe7;border-radius:12px}.btn-accent{background:#bd5b28;color:#fff}.btn-accent:hover{background:#a64b20;color:#fff}</style>
</head>
<body><main class="container py-4 py-lg-5">
    <header class="topbar p-3 p-md-4 d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div><div class="mb-2">@include('partials.brand', ['href' => route('admin.dashboard')])</div><h1 class="h4 fw-bold mb-1">Verifikasi pembayaran</h1><p class="text-secondary small mb-0">{{ $pesananMenunggu->count() }} pembayaran menunggu konfirmasi</p></div>
        <div class="w-100">@include('admin.partials.navigation')</div>
    </header>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    <section class="row g-3">
        @forelse($pesananMenunggu as $order)
            @php $metode=strtolower($order->metode_pembayaran ?? $order->payment_method ?? ''); $total=$order->total_harga ?? $order->total ?? $order->total_amount; @endphp
            <div class="col-lg-6"><article class="panel p-3 p-md-4 h-100">
                <div class="d-flex justify-content-between gap-3 mb-3"><div><h2 class="h6 fw-bold mb-1">Pesanan #{{ $order->id }}</h2><div class="small text-secondary">{{ $order->nama_pemesan ?? $order->customer_name }} · Meja {{ $order->nomor_meja ?? $order->table_id }}</div><div class="small text-secondary">{{ $order->created_at->format('d M Y, H:i') }}</div></div><span class="badge text-bg-warning align-self-start">Menunggu</span></div>
                <div class="border-top border-bottom py-2 mb-3 small">@foreach($order->orderDetails as $det)<div class="d-flex justify-content-between"><span>{{ $det->quantity ?? $det->qty ?? 1 }}x {{ $det->menu->nama_menu ?? 'Menu' }}</span><span>Rp {{ number_format($det->subtotal ?? 0,0,',','.') }}</span></div>@endforeach</div>
                <div class="d-flex justify-content-between fw-semibold mb-3"><span>Total tagihan</span><span>Rp {{ number_format($total,0,',','.') }}</span></div>
                <form action="{{ route('admin.order.status',$order->id) }}" method="POST" class="d-flex flex-wrap align-items-end gap-2 mb-3 p-3 rounded-3" style="background:#f7f7f5">@csrf<div class="flex-grow-1"><label class="form-label small mb-1" for="status-{{ $order->id }}">Status pesanan</label><select id="status-{{ $order->id }}" name="status" class="form-select form-select-sm"><option value="pending" {{ $order->status==='pending'?'selected':'' }}>Menunggu</option><option value="diproses" {{ $order->status==='diproses'?'selected':'' }}>Diproses</option><option value="selesai" {{ $order->status==='selesai'?'selected':'' }}>Selesai</option></select></div><button class="btn btn-outline-secondary btn-sm">Ubah status</button></form>
                @if(in_array($metode,['tunai','cash'],true))
                    <form action="{{ route('admin.order.bayarTunai',$order->id) }}" method="POST" class="row g-2 align-items-end">@csrf<div class="col"><label class="form-label small" for="jumlah-{{ $order->id }}">Jumlah uang diterima</label><input id="jumlah-{{ $order->id }}" type="number" name="jumlah_bayar" min="{{ $total }}" step="1" class="form-control form-control-sm" placeholder="Masukkan nominal" required></div><div class="col-auto"><button class="btn btn-accent btn-sm">Konfirmasi tunai</button></div></form>
                @else
                    <form action="{{ route('admin.order.verifikasi',$order->id) }}" method="POST" class="d-flex flex-wrap gap-2">@csrf<button name="status_verifikasi" value="verified" class="btn btn-success btn-sm">Konfirmasi pembayaran</button><button name="status_verifikasi" value="rejected" class="btn btn-outline-danger btn-sm">Tolak</button></form>
                @endif
            </article></div>
        @empty
            <div class="col-12"><div class="panel text-center text-secondary p-5">Tidak ada pembayaran yang menunggu verifikasi.</div></div>
        @endforelse
    </section>
</main></body>
</html>
