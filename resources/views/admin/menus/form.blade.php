<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }} - Coffee Shop</title>
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
<main class="container py-4" style="max-width:760px">
    <header class="topbar p-3 p-md-4 d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="mb-2">@include('partials.brand', ['href' => route('admin.dashboard')])</div>
            <h1 class="h4 fw-bold mb-1">{{ $pageTitle }}</h1>
            <p class="text-muted small mb-0">Isi detail menu dan harga</p>
        </div>
        <div class="w-100">@include('admin.partials.navigation')</div>
    </header>

    @if($errors->any())
        <div class="alert alert-danger rounded-3">{{ $errors->first() }}</div>
    @endif

    <form class="panel bg-white p-4" action="{{ $menu->exists ? route('admin.menus.update', $menu) : route('admin.menus.store') }}" method="POST">
        @csrf
        @if($menu->exists) @method('PUT') @endif

        <div class="mb-3">
            <label for="nama_menu" class="form-label fw-semibold">Nama Menu</label>
            <input id="nama_menu" name="nama_menu" class="form-control" value="{{ old('nama_menu', $menu->nama_menu) }}" required maxlength="255">
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label fw-semibold">Kategori</label>
            <select id="category_id" name="category_id" class="form-select" required>
                <option value="">Pilih kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $menu->category_id) == $category->id)>{{ $category->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label fw-semibold">Harga (Rp)</label>
            <input id="harga" name="harga" type="number" min="0" step="1" class="form-control" value="{{ old('harga', $menu->harga) }}" required>
        </div>
        <input type="hidden" name="is_available" value="0">
        <div class="form-check form-switch mb-4">
            <input id="is_available" name="is_available" type="checkbox" value="1" class="form-check-input" @checked(old('is_available', $menu->is_available) == 1)>
            <label for="is_available" class="form-check-label">Menu tersedia untuk dipesan</label>
        </div>
        <button type="submit" class="btn btn-accent">Simpan menu</button>
    </form>
</main>
</body>
</html>
