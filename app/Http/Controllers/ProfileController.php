<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $settings = DB::table('settings')->pluck('value', 'key');
        return view('profil', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_alamat' => 'nullable|string|max:500',
            'store_telepon' => 'nullable|string|max:50',
            'store_thank_you' => 'nullable|string|max:500',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $fields = ['store_name', 'store_alamat', 'store_telepon', 'store_thank_you'];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                DB::table('settings')->updateOrInsert(
                    ['key' => $field],
                    ['value' => $request->input($field), 'updated_at' => now()]
                );
            }
        }

        if ($request->hasFile('store_logo')) {
            $logoPath = $request->file('store_logo')->store('logos', 'public');
            
            // Delete old logo if exists
            $oldLogo = DB::table('settings')->where('key', 'store_logo')->value('value');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }

            DB::table('settings')->updateOrInsert(
                ['key' => 'store_logo'],
                ['value' => $logoPath, 'updated_at' => now()]
            );
        }

        return redirect()->route('profil.index')->with('success', 'Profil toko berhasil diperbarui!');
    }

    public function removeLogo()
    {
        $oldLogo = DB::table('settings')->where('key', 'store_logo')->value('value');
        if ($oldLogo) {
            Storage::disk('public')->delete($oldLogo);
            DB::table('settings')->where('key', 'store_logo')->delete();
        }

        return redirect()->route('profil.index')->with('success', 'Logo berhasil dihapus!');
    }
}
