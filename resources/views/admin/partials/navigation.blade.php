<nav class="app-navigation" aria-label="Navigasi staf">
    <a href="{{ route('admin.dashboard') }}" class="staff-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif>Dashboard</a>
    <a href="{{ route('admin.pembayaran') }}" class="staff-nav-link {{ request()->routeIs('admin.pembayaran') ? 'active' : '' }}" @if(request()->routeIs('admin.pembayaran')) aria-current="page" @endif>Pembayaran</a>
    <a href="{{ route('admin.rekap', ['periode' => 'bulanan']) }}" class="staff-nav-link {{ request()->routeIs('admin.rekap*') ? 'active' : '' }}" @if(request()->routeIs('admin.rekap*')) aria-current="page" @endif>Rekap penjualan</a>
    @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.menus.index') }}" class="staff-nav-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}" @if(request()->routeIs('admin.menus.*')) aria-current="page" @endif>Kelola menu</a>
        <a href="{{ route('admin.users.index') }}" class="staff-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" @if(request()->routeIs('admin.users.*')) aria-current="page" @endif>Kelola pengguna</a>
    @endif
    <form action="{{ route('logout') }}" method="POST" class="staff-nav-logout">@csrf<button class="staff-nav-link" type="submit">Keluar</button></form>
</nav>
