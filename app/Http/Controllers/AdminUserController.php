<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role', ['admin', 'kasir'])->orderBy('role')->orderBy('name')->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.form', ['user' => new User(), 'pageTitle' => 'Tambah pengguna']);
    }

    public function store(Request $request)
    {
        $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['admin', 'kasir'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        abort_unless(in_array($user->role, ['admin', 'kasir'], true), 404);

        return view('admin.users.form', ['user' => $user, 'pageTitle' => 'Ubah pengguna']);
    }

    public function update(Request $request, User $user)
    {
        abort_unless(in_array($user->role, ['admin', 'kasir'], true), 404);
        $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'kasir'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.users.index', ['edit_user' => $user->id])
                ->withErrors($validator)
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        $data = $validator->validated();

        if ($user->id === $request->user()->id && $data['role'] !== 'admin') {
            return redirect()->route('admin.users.index', ['edit_user' => $user->id])->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['role' => 'Anda tidak dapat mengubah role akun admin yang sedang digunakan.']);
        }

        if ($user->role === 'admin' && $data['role'] !== 'admin' && User::where('role', 'admin')->count() <= 1) {
            return redirect()->route('admin.users.index', ['edit_user' => $user->id])->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['role' => 'Role admin terakhir tidak dapat diubah. Tambahkan admin lain terlebih dahulu.']);
        }

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        abort_unless(in_array($user->role, ['admin', 'kasir'], true), 404);

        if ($user->id === $request->user()->id) {
            return back()->withErrors(['user' => 'Akun yang sedang digunakan tidak dapat dihapus.']);
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->withErrors(['user' => 'Admin terakhir tidak dapat dihapus. Tambahkan admin lain terlebih dahulu.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
