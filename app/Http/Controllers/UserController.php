<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $stores = Store::orderBy('nama_toko')->get();

        $users = User::with('store')
            ->where('role', '!=', User::ROLE_MASTER_ADMIN)
            ->orderBy('store_id')
            ->orderBy('role')
            ->orderBy('username')
            ->get();

        return view('pusat.users', compact('stores', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'password' => ['required', 'string', 'min:5'],
            'role' => ['required', 'in:admin,kasir'],
            'store_id' => ['required', 'exists:stores,id'],
        ]);

        User::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'store_id' => $validated['store_id'],
        ]);

        return redirect()->route('pusat.users')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        abort_if($user->role === User::ROLE_MASTER_ADMIN, 403, 'Akun Master Admin tidak dapat diubah di sini.');

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username,'.$id],
            'password' => ['nullable', 'string', 'min:5'],
            'role' => ['required', 'in:admin,kasir'],
            'store_id' => ['required', 'exists:stores,id'],
        ]);

        $data = [
            'username' => $validated['username'],
            'role' => $validated['role'],
            'store_id' => $validated['store_id'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('pusat.users')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        abort_if($user->role === User::ROLE_MASTER_ADMIN, 403, 'Akun Master Admin tidak dapat dihapus.');

        if ($user->id === Auth::id()) {
            return redirect()->route('pusat.users')
                ->withErrors(['error' => 'Tidak dapat menghapus akun yang sedang digunakan.']);
        }

        $user->delete();

        return redirect()->route('pusat.users')->with('success', 'Pengguna berhasil dihapus.');
    }
}
