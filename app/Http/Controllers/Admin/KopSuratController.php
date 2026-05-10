<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KopSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KopSuratController extends Controller
{
    public function edit()
    {
        $kopSurat = KopSurat::getSettings();
        return view('admin.kop_surat.edit', compact('kopSurat'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'baris_1' => 'nullable|string|max:255',
            'baris_2' => 'nullable|string|max:255',
            'baris_3' => 'nullable|string|max:255',
            'baris_4' => 'nullable|string|max:255',
            'logo'    => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        $kopSurat = KopSurat::getSettings();
        $data = $request->only(['baris_1', 'baris_2', 'baris_3', 'baris_4']);

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($kopSurat->logo && Storage::disk('public')->exists($kopSurat->logo)) {
                Storage::disk('public')->delete($kopSurat->logo);
            }
            $data['logo'] = $request->file('logo')->store('kop_surat', 'public');
        }

        if ($request->input('hapus_logo') === '1' && $kopSurat->logo) {
            Storage::disk('public')->delete($kopSurat->logo);
            $data['logo'] = null;
        }

        $kopSurat->update($data);

        return redirect()->route('admin.kop_surat.edit')->with('success', 'Kop surat berhasil diperbarui.');
    }
}
