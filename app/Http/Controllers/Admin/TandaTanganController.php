<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TandaTangan;
use Illuminate\Http\Request;

class TandaTanganController extends Controller
{
    public function edit()
    {
        $tandaTangan = TandaTangan::getSettings();
        return view('admin.tanda_tangan.edit', compact('tandaTangan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nipd'      => 'nullable|string|max:50',
            'nama'      => 'required|string|max:255',
            'gambar_qr' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $tandaTangan = TandaTangan::getSettings();

        $data = $request->only(['nipd', 'nama']);

        // Upload gambar QR Code jika ada file baru
        if ($request->hasFile('gambar_qr')) {
            // Hapus file lama jika ada
            if ($tandaTangan->gambar_qr && \Storage::disk('public')->exists($tandaTangan->gambar_qr)) {
                \Storage::disk('public')->delete($tandaTangan->gambar_qr);
            }
            $data['gambar_qr'] = $request->file('gambar_qr')->store('tanda_tangan', 'public');
        }

        $tandaTangan->update($data);

        return redirect()->route('admin.tanda_tangan.edit')->with('success', 'Informasi tanda tangan berhasil diperbarui.');
    }
}

