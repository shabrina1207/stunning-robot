<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola pengguna - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/staff.css') }}" rel="stylesheet">
    <style>
        body { background: #f7f7f5; color: #252525; font-family: Inter, 'Segoe UI', sans-serif; }
        .panel, .topbar { background: #fff; border: 1px solid #ecebe7; border-radius: 12px; }
        .btn-accent { background: #bd5b28; border-color: #bd5b28; color: #fff; }
        .btn-accent:hover { background: #a64b20; border-color: #a64b20; color: #fff; }
        .modal-content { border: 1px solid #ecebe7; border-radius: 16px; }
    </style>
</head>
<body>
<main class="container py-4 py-lg-5">
    <header class="topbar p-3 p-md-4 d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="mb-2">@include('partials.brand', ['href' => route('admin.dashboard')])</div>
            <h1 class="h4 fw-bold mb-1">Kelola pengguna</h1>
            <p class="text-secondary small mb-0">Atur akun yang dapat masuk sebagai admin atau kasir</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-accent btn-sm">Tambah pengguna</a>
        <div class="w-100">@include('admin.partials.navigation')</div>
    </header>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

    <section class="panel p-3 p-md-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small"><tr><th>Nama</th><th>Email</th><th>Role</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="fw-semibold">{{ $user->name }} @if($user->id === auth()->id())<span class="badge text-bg-light">Akun Anda</span>@endif</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge {{ $user->role === 'admin' ? 'text-bg-dark' : 'text-bg-secondary' }}">{{ ucfirst($user->role) }}</span></td>
                        <td>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#edit-user-{{ $user->id }}">Ubah</button>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus akun {{ addslashes($user->name) }}?')">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm">Hapus</button></form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-secondary py-4">Belum ada akun admin atau kasir.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @foreach($users as $user)
        @php $editingThisUser = (string) request('edit_user') === (string) $user->id; @endphp
        <div class="modal fade" id="edit-user-{{ $user->id }}" tabindex="-1" aria-labelledby="edit-user-title-{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header border-0 pb-0">
                            <div>
                                <h2 class="modal-title h5 fw-bold" id="edit-user-title-{{ $user->id }}">Ubah pengguna</h2>
                                <p class="small text-secondary mb-0">Perbarui informasi akun {{ $user->name }}</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name-{{ $user->id }}" class="form-label small">Nama</label>
                                <input id="name-{{ $user->id }}" name="name" class="form-control" value="{{ $editingThisUser ? old('name', $user->name) : $user->name }}" required maxlength="255">
                            </div>
                            <div class="mb-3">
                                <label for="email-{{ $user->id }}" class="form-label small">Email login</label>
                                <input id="email-{{ $user->id }}" name="email" type="email" class="form-control" value="{{ $editingThisUser ? old('email', $user->email) : $user->email }}" required maxlength="255">
                            </div>
                            <div class="mb-3">
                                <label for="role-{{ $user->id }}" class="form-label small">Role</label>
                                <select id="role-{{ $user->id }}" name="role" class="form-select" required>
                                    <option value="kasir" {{ ($editingThisUser ? old('role', $user->role) : $user->role) === 'kasir' ? 'selected' : '' }}>Kasir</option>
                                    <option value="admin" {{ ($editingThisUser ? old('role', $user->role) : $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                @if($user->id === auth()->id())<div class="form-text">Role akun yang sedang digunakan tidak dapat diturunkan.</div>@endif
                            </div>
                            <div class="mb-3">
                                <label for="password-{{ $user->id }}" class="form-label small">Password baru (opsional)</label>
                                <input id="password-{{ $user->id }}" name="password" type="password" class="form-control" minlength="8" autocomplete="new-password">
                                <div class="form-text">Biarkan kosong jika tidak ingin mengganti password.</div>
                            </div>
                            <div>
                                <label for="password-confirmation-{{ $user->id }}" class="form-label small">Konfirmasi password baru</label>
                                <input id="password-confirmation-{{ $user->id }}" name="password_confirmation" type="password" class="form-control" minlength="8" autocomplete="new-password">
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
<script>
    const editUserId = new URLSearchParams(window.location.search).get('edit_user');
    if (editUserId) {
        const editModal = document.getElementById(`edit-user-${editUserId}`);
        if (editModal) bootstrap.Modal.getOrCreateInstance(editModal).show();
    }
</script>
</body>
</html>
