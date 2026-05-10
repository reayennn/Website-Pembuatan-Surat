<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use Illuminate\Http\Request;

class PendudukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $penduduks = Penduduk::when($search, function ($query) use ($search) {
                $query->where('nik', 'like', '%' . $search . '%')
                      ->orWhere('nama', 'like', '%' . $search . '%')
                      ->orWhere('pekerjaan', 'like', '%' . $search . '%')
                      ->orWhere('alamat', 'like', '%' . $search . '%');
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status_warga', $status);
            })
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('admin.penduduk.index', compact('penduduks', 'search', 'status'));
    }

    public function create()
    {
        return view('admin.penduduk.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nik'                => 'required|string|max:16|unique:penduduks',
            'nama'               => 'required|string|max:255',
            'tempat_lahir'       => 'required|string|max:255',
            'tanggal_lahir'      => 'required|date',
            'jenis_kelamin'      => 'required|in:Laki-laki,Perempuan',
            'status_perkawinan'  => 'required|in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati',
            'kewarganegaraan'    => 'required|string|max:50',
            'agama'              => 'required|string|max:50',
            'pekerjaan'          => 'required|string|max:255',
            'alamat'             => 'required|string',
        ]);

        Penduduk::create($validatedData);

        return redirect()->route('admin.penduduk.index')->with('success', 'Data Warga berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $penduduk = Penduduk::findOrFail($id);
        return view('admin.penduduk.show', compact('penduduk'));
    }

    public function edit(string $id)
    {
        $penduduk = Penduduk::findOrFail($id);
        return view('admin.penduduk.edit', compact('penduduk'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'nik'                => 'required|string|max:16|unique:penduduks,nik,' . $id,
            'nama'               => 'required|string|max:255',
            'tempat_lahir'       => 'required|string|max:255',
            'tanggal_lahir'      => 'required|date',
            'jenis_kelamin'      => 'required|in:Laki-laki,Perempuan',
            'status_perkawinan'  => 'required|in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati',
            'kewarganegaraan'    => 'required|string|max:50',
            'agama'              => 'required|string|max:50',
            'pekerjaan'          => 'required|string|max:255',
            'alamat'             => 'required|string',
        ]);

        $penduduk = Penduduk::findOrFail($id);
        $penduduk->update($validatedData);

        return redirect()->route('admin.penduduk.index')->with('success', 'Data Warga berhasil diperbarui.');
    }

    /**
     * Tandai warga sebagai Pindah — akses langsung dicabut.
     */
    public function tandaiPindah(Request $request, string $id)
    {
        $request->validate([
            'alamat_tujuan_pindah' => 'required|string|max:255',
            'tanggal_pindah'       => 'required|date',
            'keterangan_pindah'    => 'nullable|string|max:500',
        ], [
            'alamat_tujuan_pindah.required' => 'Alamat tujuan pindah wajib diisi.',
            'tanggal_pindah.required'       => 'Tanggal pindah wajib diisi.',
        ]);

        $penduduk = Penduduk::findOrFail($id);

        $penduduk->update([
            'status_warga'          => 'Pindah',
            'alamat_tujuan_pindah'  => $request->alamat_tujuan_pindah,
            'tanggal_pindah'        => $request->tanggal_pindah,
            'keterangan_pindah'     => $request->keterangan_pindah,
        ]);

        // Cabut semua sesi aktif user yang tertaut dengan penduduk ini
        if ($penduduk->user) {
            \Illuminate\Support\Facades\DB::table('sessions')
                ->where('user_id', $penduduk->user->id)
                ->delete();
        }

        return redirect()->route('admin.penduduk.index')
            ->with('success', "Warga {$penduduk->nama} telah ditandai sebagai Pindah. Akses login dicabut.");
    }

    /**
     * Batalkan status pindah — kembalikan ke Aktif.
     */
    public function batalkanPindah(string $id)
    {
        $penduduk = Penduduk::findOrFail($id);

        $penduduk->update([
            'status_warga'          => 'Aktif',
            'alamat_tujuan_pindah'  => null,
            'tanggal_pindah'        => null,
            'keterangan_pindah'     => null,
        ]);

        return redirect()->route('admin.penduduk.index')
            ->with('success', "Status warga {$penduduk->nama} telah dikembalikan ke Aktif.");
    }

    public function destroy(string $id)
    {
        Penduduk::findOrFail($id)->delete();
        return redirect()->route('admin.penduduk.index')->with('success', 'Data Warga berhasil dihapus.');
    }
}
