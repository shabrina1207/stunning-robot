<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin dan Kasir - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Poppins', sans-serif; }
        .card-login { width: 100%; max-width: 420px; border: none; border-radius: 16px; box-shadow: 0 4px 18px rgba(0,0,0,0.07); }
        .brand-mark { color: #ff6b00; }
        .btn-orange { background-color: #ff6b00; color: white; font-weight: 600; border-radius: 20px; }
        .btn-orange:hover { background-color: #e56000; color: white; }
        .password-field { position: relative; }
        .password-field .form-control { padding-right: 44px; }
        .toggle-password { position: absolute; top: 50%; right: 10px; transform: translateY(-50%); border: 0; background: transparent; color: #6c757d; padding: 4px; line-height: 1; cursor: pointer; }
        .toggle-password:hover, .toggle-password:focus { color: #7f1d1d; }
    </style>
</head>
<body>

<div class="card card-login p-4 bg-white">
    <div class="text-center mb-4">
        <h4 class="fw-bold mb-1 brand-mark">COFFEE SHOP</h4>
        <p class="text-muted small">Masuk sebagai kasir atau admin menggunakan email</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger py-2 small text-center">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login.process') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label text-muted small">Email</label>
            <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="nama@contoh.com" autocomplete="username" required autofocus>
        </div>
        <div class="mb-4">
            <label for="password" class="form-label text-muted small">Password</label>
            <div class="password-field">
                <input id="password" type="password" name="password" class="form-control" autocomplete="current-password" required>
                <button type="button" class="toggle-password" id="toggle-password" aria-label="Tampilkan password" aria-pressed="false">
                    <span aria-hidden="true">👁</span>
                </button>
            </div>
        </div>
        <button type="submit" class="btn btn-orange w-100 py-2">Masuk Dashboard</button>
    </form>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('toggle-password');

    togglePassword.addEventListener('click', function () {
        const isHidden = passwordInput.type === 'password';
        passwordInput.type = isHidden ? 'text' : 'password';
        this.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
        this.setAttribute('aria-pressed', String(isHidden));
    });
</script>

</body>
</html>
