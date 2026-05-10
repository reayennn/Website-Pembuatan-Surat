<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Penduduk;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('penduduk')->where('role', 'masyarakat')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function linkPenduduk(Request $request, string $id)
    {
        $request->validate([
            'nik'  => 'required|string|min:16|max:16',
            'nama' => 'required|string|max:255',
        ], [
            'nik.required'  => 'NIK wajib diisi.',
            'nik.min'       => 'NIK harus tepat 16 digit.',
            'nik.max'       => 'NIK harus tepat 16 digit.',
            'nama.required' => 'Nama wajib diisi.',
        ]);

        $user = User::findOrFail($id);

        // Cek apakah NIK ini sudah ditautkan ke user lain
        $existingUser = User::where('penduduk_id', function($q) use ($request) {
            $q->select('id')->from('penduduks')->where('nik', $request->nik)->limit(1);
        })->where('id', '!=', $id)->first();

        if ($existingUser) {
            return redirect()->route('admin.users.index')
                ->with('error', 'NIK ' . $request->nik . ' sudah ditautkan ke akun lain (' . $existingUser->name . ').');
        }

        // Cari atau buat data warga berdasarkan NIK
        // Mengisi semua kolom NOT NULL dengan nilai default agar MySQL tidak menolak insert
        $penduduk = Penduduk::firstOrCreate(
            ['nik' => $request->nik],
            [
                'nama'          => $request->nama,
                'tempat_lahir'  => '-',
                'tanggal_lahir' => '1970-01-01',
                'jenis_kelamin' => 'Laki-laki',
                'agama'         => '-',
                'pekerjaan'     => '-',
                'alamat'        => '-',
            ]
        );

        // Jika penduduk sudah ada, update nama jika diberikan
        if (!$penduduk->wasRecentlyCreated && $request->nama) {
            $penduduk->nama = $request->nama;
            $penduduk->save();
        }

        $user->penduduk_id = $penduduk->id;
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun "' . $user->name . '" berhasil ditautkan dengan NIK ' . $penduduk->nik . ' (' . $penduduk->nama . ').');
    }

    public function destroy(string $id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil dihapus.');
    }
}
