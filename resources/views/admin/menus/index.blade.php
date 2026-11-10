<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/staff.css') }}" rel="stylesheet">
    <style>
        body { background: #f7f7f5; color:#252525; font-family: Inter, 'Segoe UI', sans-serif; }
        .panel,.topbar { background:#fff; border:1px solid #ecebe7; border-radius:12px; }
        .btn-accent { background:#bd5b28; border-color:#bd5b28; color:#fff; }
        .btn-accent:hover { background:#a64b20; border-color:#a64b20; color:#fff; }
    </style>
</head>
<body>
<main class="container py-4">
    <header class="topbar p-3 p-md-4 d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="mb-2">@include('partials.brand', ['href' => route('admin.dashboard')])</div>
            <h1 class="h4 fw-bold mb-1">Kelola menu</h1>
            <p class="text-muted small mb-0">Atur daftar menu, kategori, harga, dan ketersediaan</p>
        </div>
        <a href="{{ route('admin.menus.create') }}" class="btn btn-accent btn-sm">Tambah menu</a>
        <div class="w-100">@include('admin.partials.navigation')</div>
    </header>

    @if(session('success'))
        <div class="alert alert-success rounded-3">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger rounded-3">{{ $errors->first() }}</div>
    @endif

    <section class="panel bg-white p-3 p-md-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Nama Menu</th><th>Kategori</th><th>Harga</th><th>Status</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                        <tr>
                            <td><div class="fw-semibold">{{ $menu->nama_menu }}</div><small class="text-muted">{{ $menu->deskripsi }}</small></td>
                            <td>{{ $menu->category->nama_kategori ?? '—' }}</td>
                            <td class="fw-semibold">Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                            <td><span class="badge {{ $menu->is_available ? 'bg-success' : 'bg-secondary' }}">{{ $menu->is_available ? 'Tersedia' : 'Tidak tersedia' }}</span></td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#edit-menu-{{ $menu->id }}">Ubah</button>
                                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" onsubmit="return confirm('Hapus menu ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada menu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @foreach($menus as $menu)
        <div class="modal fade" id="edit-menu-{{ $menu->id }}" tabindex="-1" aria-labelledby="edit-menu-title-{{ $menu->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('admin.menus.update', $menu) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header border-0 pb-0">
                            <div>
                                <h2 class="modal-title h5 fw-bold" id="edit-menu-title-{{ $menu->id }}">Ubah menu</h2>
                                <p class="small text-secondary mb-0">Perbarui informasi {{ $menu->nama_menu }}</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama-menu-{{ $menu->id }}" class="form-label small">Nama menu</label>
                                <input id="nama-menu-{{ $menu->id }}" name="nama_menu" class="form-control" value="{{ $menu->nama_menu }}" required maxlength="255">
                            </div>
                            <div class="mb-3">
                                <label for="category-menu-{{ $menu->id }}" class="form-label small">Kategori</label>
                                <select id="category-menu-{{ $menu->id }}" name="category_id" class="form-select" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected($menu->category_id == $category->id)>{{ $category->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi-menu-{{ $menu->id }}" class="form-label small">Deskripsi</label>
                                <textarea id="deskripsi-menu-{{ $menu->id }}" name="deskripsi" class="form-control" rows="3">{{ $menu->deskripsi }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="harga-menu-{{ $menu->id }}" class="form-label small">Harga (Rp)</label>
                                <input id="harga-menu-{{ $menu->id }}" name="harga" type="number" min="0" step="1" class="form-control" value="{{ $menu->harga }}" required>
                            </div>
                            <input type="hidden" name="is_available" value="0">
                            <div class="form-check form-switch">
                                <input id="available-menu-{{ $menu->id }}" name="is_available" type="checkbox" value="1" class="form-check-input" @checked($menu->is_available)>
                                <label for="available-menu-{{ $menu->id }}" class="form-check-label">Menu tersedia untuk dipesan</label>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-accent btn-sm">Simpan perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
