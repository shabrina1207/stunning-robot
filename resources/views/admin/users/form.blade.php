<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }} - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/staff.css') }}" rel="stylesheet">
    <style>body{background:#f7f7f5;color:#252525;font-family:Inter,'Segoe UI',sans-serif}.panel,.topbar{background:#fff;border:1px solid #ecebe7;border-radius:12px}.btn-accent{background:#bd5b28;border-color:#bd5b28;color:#fff}.btn-accent:hover{background:#a64b20;border-color:#a64b20;color:#fff}</style>
</head>
<body><main class="container py-4 py-lg-5" style="max-width:850px">
    <header class="topbar p-3 p-md-4 mb-4"><div class="mb-2">@include('partials.brand', ['href' => route('admin.dashboard')])</div><h1 class="h4 fw-bold mb-1">{{ $pageTitle }}</h1><p class="text-secondary small mb-3">Akun hanya dapat menggunakan role admin atau kasir</p>@include('admin.partials.navigation')</header>
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    <form class="panel p-3 p-md-4" action="{{ $user->exists ? route('admin.users.update',$user) : route('admin.users.store') }}" method="POST">
        @csrf @if($user->exists) @method('PUT') @endif
        <div class="mb-3"><label for="name" class="form-label fw-semibold">Nama</label><input id="name" name="name" class="form-control" value="{{ old('name',$user->name) }}" required maxlength="255"></div>
        <div class="mb-3"><label for="email" class="form-label fw-semibold">Email login</label><input id="email" name="email" type="email" class="form-control" value="{{ old('email',$user->email) }}" required maxlength="255"><div class="form-text">Email ini digunakan sebagai identitas saat masuk ke dashboard.</div></div>
        <div class="mb-3"><label for="role" class="form-label fw-semibold">Role</label><select id="role" name="role" class="form-select" required><option value="kasir" {{ old('role',$user->role ?: 'kasir')==='kasir'?'selected':'' }}>Kasir</option><option value="admin" {{ old('role',$user->role)==='admin'?'selected':'' }}>Admin</option></select>@if($user->id===auth()->id())<div class="form-text">Role akun yang sedang digunakan tidak dapat diturunkan.</div>@endif</div>
        <div class="mb-3"><label for="password" class="form-label fw-semibold">{{ $user->exists ? 'Password baru (opsional)' : 'Password' }}</label><input id="password" name="password" type="password" class="form-control" {{ $user->exists?'':'required' }} minlength="8" autocomplete="new-password"><div class="form-text">Minimal 8 karakter. Biarkan kosong jika tidak ingin mengganti password.</div></div>
        <div class="mb-4"><label for="password_confirmation" class="form-label fw-semibold">Konfirmasi password</label><input id="password_confirmation" name="password_confirmation" type="password" class="form-control" {{ $user->exists?'':'required' }} minlength="8" autocomplete="new-password"></div>
        <div class="d-flex gap-2"><button class="btn btn-accent">Simpan pengguna</button><a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a></div>
    </form>
</main></body>
</html>
