<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .receipt-card { max-width: 450px; margin: 30px auto; background: #fff; border-radius: 16px; padding: 25px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); }
        .dashed-line { border-bottom: 2px dashed #dee2e6; margin: 15px 0; }
        @media print {
            body { background: #fff; }
            .no-print { display: none !important; }
            .receipt-card { box-shadow: none; margin: 0; width: 100%; max-width: 100%; padding: 0; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="receipt-card">
        @php
            $paymentStatus = $order->status_verifikasi ?? 'pending';
            $isVerified = $paymentStatus === 'verified';
            $isRejected = $paymentStatus === 'rejected';
            $metode = strtolower((string) ($order->metode_pembayaran ?? $order->payment_method ?? ''));
        @endphp

        @if($isVerified)
        <!-- Status & Arahan Kasir -->
        <div class="text-center mb-3">
            <div class="text-success fs-1 no-print">✓</div>
            <h4 class="fw-bold mb-1">Pesanan Berhasil Disimpan!</h4>
            
            @php
                $metode = strtolower($order->metode_pembayaran ?? $order->payment_method ?? '');
            @endphp

            @if(str_contains($metode, 'tunai') || str_contains($metode, 'cash') || str_contains($metode, 'kasir'))
                <div class="alert alert-warning mt-3 text-start no-print" role="alert">
                    <strong>📌 Instruksi Pembayaran:</strong><br>
                    Silakan sebutkan <strong>Kode Transaksi</strong> atau <strong>Nomor Meja</strong> ke Kasir untuk melakukan pembayaran tunai.
                </div>
            @else
                <p class="text-muted small">Terima kasih telah melakukan pemesanan.</p>
            @endif
        </div>

        <div class="dashed-line"></div>

        <!-- Detail Struk -->
        <div class="text-center mb-3">
            <h5 class="fw-bold m-0">COFFEE SHOP</h5>
            <small class="text-muted">Bukti Pemesanan</small>
        </div>

        <div class="small">
            <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">Kode Transaksi</span>
                <span class="fw-bold">{{ $order->kode_transaksi ?? $order->transaction_code ?? '#'.$order->id }}</span>
            </div>
            <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">Nama Pemesan</span>
                <span>{{ $order->nama_pemesan ?? $order->customer_name }}</span>
            </div>
            <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">Nomor Meja</span>
                <span>{{ $order->nomor_meja ?? $order->table_id }}</span>
            </div>
            <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">Metode Bayar</span>
                <span class="badge bg-secondary text-uppercase">{{ $order->metode_pembayaran ?? $order->payment_method }}</span>
            </div>
        </div>

        <div class="dashed-line"></div>

        <!-- Daftar Menu -->
        <div class="small mb-3">
            <div class="fw-bold mb-2">Item Pesanan:</div>
            @foreach($order->orderDetails as $detail)
            @php
                $qty = $detail->quantity ?? $detail->qty ?? $detail->jumlah ?? 1;
                $harga = $detail->harga_satuan ?? $detail->price ?? $detail->harga ?? 0;
            @endphp
            <div class="d-flex justify-content-between mb-1">
                <span>{{ $qty }}x {{ $detail->menu->nama_menu ?? $detail->menu->name ?? 'Menu' }}</span>
                <span>Rp {{ number_format($harga * $qty, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>

        <div class="dashed-line"></div>

        <!-- Total Tagihan -->
        <div class="d-flex justify-content-between fw-bold fs-6 mb-4">
            <span>Total Tagihan</span>
            <span class="text-success">Rp {{ number_format($order->total_harga ?? $order->total_amount ?? $order->total, 0, ',', '.') }}</span>
        </div>

        <!-- Tombol Aksi -->
        <div class="d-grid gap-2 no-print">
            <button onclick="window.print()" class="btn btn-outline-dark fw-bold rounded-pill">
                🖨️ Cetak Struk
            </button>
            <a href="/order" class="btn btn-primary fw-bold rounded-pill">
                Kembali ke Menu
            </a>
        </div>
    </div>
</div>

        @else
            <div class="text-center">
                <div class="text-warning fs-1">⏳</div>
                <h4 class="fw-bold">Menunggu Konfirmasi Pembayaran</h4>
                @if($isRejected)
                    <div class="alert alert-danger">Pembayaran ditolak. Silakan hubungi kasir.</div>
                @else
                    <div class="alert alert-warning">Pembayaran sedang menunggu konfirmasi admin/kasir. Struk akan muncul otomatis setelah dikonfirmasi.</div>
                    <div class="spinner-border text-warning my-3" role="status" aria-label="Memeriksa status pembayaran"></div>
                    <p class="text-muted small">Jangan tutup halaman ini.</p>
                @endif
                <p>Kode transaksi: <strong>{{ $order->kode_pesanan ?? $order->id }}</strong></p>
                <a href="/order" class="btn btn-outline-secondary">Kembali ke Menu</a>
            </div>
            @if(!$isRejected)
                <script>
                    const statusUrl = @json(route('order.status', $order->id));
                    const checkPaymentStatus = async () => {
                        try {
                            const response = await fetch(statusUrl, { headers: { 'Accept': 'application/json' }, cache: 'no-store' });
                            const data = await response.json();
                            if (data.status_verifikasi === 'verified' || data.status_verifikasi === 'rejected') window.location.reload();
                        } catch (error) { console.warn('Status pembayaran belum dapat diperiksa.', error); }
                    };
                    window.setInterval(checkPaymentStatus, 5000);
                </script>
            @endif
        @endif
</body>
</html>
