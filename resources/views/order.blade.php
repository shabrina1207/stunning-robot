<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pemesanan Menu - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; padding-bottom: 100px; }
        .nav-pills .nav-link { color: #6c757d; background-color: #f1f3f5; border-radius: 20px; padding: 8px 20px; font-weight: 500; }
        .nav-pills .nav-link.active { background-color: #ff6b00; color: #ffffff; font-weight: 600; }
        .card-menu { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); background: #ffffff; transition: transform .2s ease, box-shadow .2s ease; will-change: transform, box-shadow; }
        .card-menu:hover, .card-menu:focus-within { transform: translateY(-5px); box-shadow: 0 16px 30px rgba(37,37,37,.16), 0 5px 10px rgba(255,107,0,.12); }
        .card-menu:active { transform: translateY(-2px); box-shadow: 0 9px 18px rgba(37,37,37,.13); }
        .btn-qty { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; border-radius: 6px; cursor: pointer; }
        .fixed-bottom-bar { position: fixed; bottom: 0; left: 0; right: 0; background: #ffffff; box-shadow: 0 -2px 10px rgba(0,0,0,0.08); padding: 16px 24px; z-index: 100; }
        .btn-orange { background-color: #ff6b00; color: white; border-radius: 20px; padding: 10px 24px; font-weight: 600; border: none; }
        .brand-lockup { display: inline-flex; align-items: center; gap: 10px; color: #252525; }
        .brand-logo { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; }
        .brand-name { font-size: 1.1rem; font-weight: 700; letter-spacing: -.02em; }
    </style>
</head>
<body>

<div class="container py-4">
    @include('partials.brand')
    <p class="text-muted small">Pilih dan bayar menu yang Anda inginkan secara mandiri</p>

    <!-- Filter Kategori -->
    <ul class="nav nav-pills mb-4" id="category-tabs">
        <li class="nav-item me-2">
            <button class="nav-link active filter-btn" data-filter="all">Semua</button>
        </li>
        @foreach($categories as $cat)
        <li class="nav-item me-2">
            <button class="nav-link filter-btn" data-filter="{{ $cat->nama_kategori }}">{{ $cat->nama_kategori }}</button>
        </li>
        @endforeach
    </ul>

    <!-- List Menu -->
    <div class="row g-3" id="menu-container">
        @foreach($menus as $menu)
        <div class="col-md-6 menu-item" data-category="{{ $menu->category->nama_kategori ?? 'all' }}">
            <div class="card card-menu p-3">
                <h6 class="fw-bold mb-1">{{ $menu->nama_menu }}</h6>
                <p class="text-muted small mb-2">{{ $menu->deskripsi }}</p>
                <p class="fw-bold mb-2">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-outline-secondary btn-qty btn-minus" data-id="{{ $menu->id }}" data-name="{{ $menu->nama_menu }}" data-price="{{ $menu->harga }}">-</button>
                    <span class="mx-3 qty-val fw-bold" id="qty-{{ $menu->id }}">0</span>
                    <button type="button" class="btn btn-warning btn-qty text-white btn-plus" data-id="{{ $menu->id }}" data-name="{{ $menu->nama_menu }}" data-price="{{ $menu->harga }}">+</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Bottom Bar -->
<div class="fixed-bottom-bar d-flex justify-content-between align-items-center">
    <div>
        <span class="text-muted small d-block">TOTAL BAYAR</span>
        <span class="fw-bold text-warning fs-5" id="total-bayar">Rp 0</span>
    </div>
    <button type="button" class="btn btn-orange" id="btn-lanjut">Lanjut Pesan</button>
</div>

<!-- Modal Pembayaran -->
<div class="modal fade" id="modalPembayaran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Konfirmasi & Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formOrder">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Nomor Meja</label>
                        <input type="text" class="form-control" id="nomor_meja" value="MEJA-01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Nama Pemesan</label>
                        <input type="text" class="form-control" id="nama_pemesan" placeholder="Masukkan nama kamu" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Metode Pembayaran</label>
                        <select class="form-select" id="metode_pembayaran" required>
                            <option value="tunai">Tunai (Kasir)</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Tagihan:</span>
                        <span class="fw-bold text-warning" id="modal-total">Rp 0</span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-orange" id="btn-bayar">Bayar Sekarang</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pop-Up QRIS -->
<div class="modal fade" id="modalQRIS" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-3">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold w-100">Scan QRIS Pembayaran</h5>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-2">Silakan scan kode QR di bawah ini menggunakan e-wallet / mobile banking kamu:</p>
                <!-- Gambar Dummy QRIS Placeholder -->
                <div class="my-3 text-center">
                    <img src="{{ asset('images/qr.jpg') }}" alt="QRIS" class="img-fluid border rounded p-2 shadow-sm" style="width: 220px; height: 220px; object-fit: contain;">
                </div>
                <p class="fw-bold text-warning fs-5 mb-0" id="qris-total-display">Rp 0</p>
                <small class="text-muted d-block mt-1">Pembayaran akan dikonfirmasi oleh kasir/admin.</small>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary w-100 rounded-pill fw-bold py-2" data-bs-dismiss="modal">Lihat Status Pesanan</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let totalHarga = 0;
    let selectedItems = {};
    let redirectSuccessUrl = '';

    const totalDisplay = document.getElementById('total-bayar');
    const modalTotalDisplay = document.getElementById('modal-total');
    const qrisTotalDisplay = document.getElementById('qris-total-display');
    const modalPembayaran = new bootstrap.Modal(document.getElementById('modalPembayaran'));
    const modalQRIS = new bootstrap.Modal(document.getElementById('modalQRIS'));

    // 1. Filter Kategori
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const filterValue = this.getAttribute('data-filter');
            document.querySelectorAll('.menu-item').forEach(item => {
                if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // 2. Tambah / Kurangi Qty
    document.querySelectorAll('.btn-plus').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const price = parseInt(this.getAttribute('data-price'));
            if (!selectedItems[id]) {
                selectedItems[id] = { id: id, price: price, qty: 0 };
            }
            selectedItems[id].qty++;
            totalHarga += price;
            document.getElementById('qty-' + id).textContent = selectedItems[id].qty;
            totalDisplay.textContent = 'Rp ' + totalHarga.toLocaleString('id-ID');
        });
    });

    document.querySelectorAll('.btn-minus').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const price = parseInt(this.getAttribute('data-price'));

            if (selectedItems[id] && selectedItems[id].qty > 0) {
                selectedItems[id].qty--;
                totalHarga -= price;

                if (selectedItems[id].qty === 0) {
                    delete selectedItems[id];
                }

                document.getElementById('qty-' + id).textContent = selectedItems[id] ? selectedItems[id].qty : 0;
                totalDisplay.textContent = 'Rp ' + totalHarga.toLocaleString('id-ID');
            }
        });
    });

    // 3. Klik Lanjut Pesan -> Buka Modal
    document.getElementById('btn-lanjut').addEventListener('click', function() {
        if (totalHarga === 0) {
            alert('Pilih minimal 1 menu sebelum melanjutkan!');
            return;
        }
        modalTotalDisplay.textContent = 'Rp ' + totalHarga.toLocaleString('id-ID');
        modalPembayaran.show();
    });

    // 4. Kirim Data Ke Controller
    document.getElementById('btn-bayar').addEventListener('click', function() {
        const namaPemesan = document.getElementById('nama_pemesan').value;
        const nomorMeja = document.getElementById('nomor_meja').value;
        const metodePembayaran = document.getElementById('metode_pembayaran').value;

        if (!namaPemesan) {
            alert('Harap isi nama pemesan terlebih dahulu!');
            return;
        }

        const itemsArray = Object.values(selectedItems);

        fetch('/order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                nomor_meja: nomorMeja,
                nama_pemesan: namaPemesan,
                metode_pembayaran: metodePembayaran,
                items: itemsArray
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                redirectSuccessUrl = data.redirect_url;
                
                // QRIS dan tunai sama-sama menunggu konfirmasi kasir/admin.
                if (data.is_qris) {
                    modalPembayaran.hide();
                    qrisTotalDisplay.textContent = 'Rp ' + totalHarga.toLocaleString('id-ID');
                    modalQRIS.show();
                }

                window.setTimeout(function () {
                    window.location.href = redirectSuccessUrl;
                }, data.is_qris ? 1200 : 0);
            } else {
                alert('Gagal: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses pesanan.');
        });
    });

});
</script>

</body>
</html>
