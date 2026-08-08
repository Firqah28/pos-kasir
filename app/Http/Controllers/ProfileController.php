<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $settings = $this->getSettings();

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

        $storeId = $this->currentStoreId();

        $fields = ['store_name', 'store_alamat', 'store_telepon', 'store_thank_you'];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                DB::table('settings')->updateOrInsert(
                    ['key' => $field, 'store_id' => $storeId],
                    ['value' => $request->input($field), 'updated_at' => now()]
                );
            }
        }

        if ($request->hasFile('store_logo')) {
            $logoPath = $request->file('store_logo')->store('logos', 'public');

            // Delete old logo of this store if exists
            $oldLogo = DB::table('settings')
                ->where('key', 'store_logo')
                ->where('store_id', $storeId)
                ->value('value');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }

            DB::table('settings')->updateOrInsert(
                ['key' => 'store_logo', 'store_id' => $storeId],
                ['value' => $logoPath, 'updated_at' => now()]
            );
        }

        return redirect()->route('profil.index')->with('success', 'Profil toko berhasil diperbarui!');
    }

    public function removeLogo()
    {
        $storeId = $this->currentStoreId();

        $oldLogo = DB::table('settings')
            ->where('key', 'store_logo')
            ->where('store_id', $storeId)
            ->value('value');
        if ($oldLogo) {
            Storage::disk('public')->delete($oldLogo);
            DB::table('settings')
                ->where('key', 'store_logo')
                ->where('store_id', $storeId)
                ->delete();
        }

        return redirect()->route('profil.index')->with('success', 'Logo berhasil dihapus!');
    }

    private function getSettings()
    {
        $global = DB::table('settings')->whereNull('store_id')->pluck('value', 'key');

        $storeId = $this->currentStoreId();
        if (! $storeId) {
            return $global;
        }

        $storeSettings = DB::table('settings')->where('store_id', $storeId)->pluck('value', 'key');

        return $global->merge($storeSettings);
    }
}
