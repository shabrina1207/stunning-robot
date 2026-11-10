<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struk {{ $order->kode_transaksi ?? '#'.$order->id }}</title>
    <style>
        *{box-sizing:border-box}body{margin:0;background:#f7f7f5;color:#222;font:14px/1.45 Arial,sans-serif}.receipt{width:100%;max-width:380px;margin:28px auto;padding:24px;background:#fff;border:1px solid #e8e6e1;border-radius:16px}.center{text-align:center}.receipt-brand{display:inline-flex;align-items:center;gap:8px;color:#222;font-weight:700;font-size:16px}.receipt-brand img{width:42px;height:42px;border-radius:50%;object-fit:cover;box-shadow:0 3px 8px rgba(37,37,37,.14)}.muted{color:#666}.line{border-top:1px dashed #999;margin:14px 0}.row{display:flex;justify-content:space-between;gap:12px;margin:5px 0}.row span:last-child{text-align:right}.items{margin:8px 0}.total{font-weight:700;font-size:16px}.actions{display:flex;gap:8px;margin-top:22px}.actions a,.actions button{flex:1;padding:10px;border:1px solid #e2e0dc;border-radius:10px;background:#fff;color:#222;text-decoration:none;text-align:center;font-size:13px;cursor:pointer}.actions .primary{background:#bd5b28;border-color:#bd5b28;color:white}@media print{body{background:#fff}.receipt{width:80mm;max-width:80mm;margin:0;padding:4mm;border:0;border-radius:0}.actions{display:none}}
    </style>
</head>
<body>
    @php $total=$order->total_harga ?? $order->total ?? $order->total_amount ?? 0; @endphp
    <main class="receipt">
        <header class="center"><div class="receipt-brand"><img src="{{ asset('images/logo-konekty.jpg') }}" alt="Logo Kedai Kopi Konekty"><span>Kedai Kopi Konekty</span></div><div class="muted">Struk pembayaran</div></header>
        <div class="line"></div>
        <div class="row"><span class="muted">No. transaksi</span><span>{{ $order->kode_transaksi ?? $order->transaction_code ?? '#'.$order->id }}</span></div>
        <div class="row"><span class="muted">Tanggal</span><span>{{ $order->created_at->format('d/m/Y H:i') }}</span></div>
        <div class="row"><span class="muted">Pemesan</span><span>{{ $order->nama_pemesan ?? $order->customer_name }}</span></div>
        <div class="row"><span class="muted">Meja</span><span>{{ $order->nomor_meja ?? $order->table_id }}</span></div>
        <div class="row"><span class="muted">Pembayaran</span><span>{{ ucfirst($order->metode_pembayaran ?? $order->payment_method ?? '-') }}</span></div>
        <div class="line"></div>
        <section class="items">
            @foreach($order->orderDetails as $detail)
                @php $qty=$detail->quantity ?? $detail->qty ?? $detail->jumlah ?? 1; $harga=$detail->harga_satuan ?? $detail->price ?? $detail->harga ?? 0; $subtotal=$detail->subtotal ?? ($harga*$qty); @endphp
                <div class="row"><span>{{ $detail->menu->nama_menu ?? $detail->menu->name ?? 'Menu' }} × {{ $qty }}</span><span>Rp {{ number_format($subtotal,0,',','.') }}</span></div>
            @endforeach
        </section>
        <div class="line"></div>
        <div class="row total"><span>Total</span><span>Rp {{ number_format($total,0,',','.') }}</span></div>
        @if(($order->jumlah_bayar ?? 0) > 0)
            <div class="row"><span class="muted">Uang diterima</span><span>Rp {{ number_format($order->jumlah_bayar,0,',','.') }}</span></div>
            <div class="row"><span class="muted">Kembalian</span><span>Rp {{ number_format($order->kembalian ?? 0,0,',','.') }}</span></div>
        @endif
        <div class="line"></div><p class="center muted">Pembayaran terverifikasi. Terima kasih.</p>
        <div class="actions"><a href="{{ route('admin.pembayaran') }}">Kembali</a><button class="primary" onclick="window.print()">Cetak struk</button></div>
    </main>
</body>
</html>
