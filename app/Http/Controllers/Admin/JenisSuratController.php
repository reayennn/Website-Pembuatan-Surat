<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use Illuminate\Http\Request;

class JenisSuratController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $jenisSurats = JenisSurat::when($search, function ($query) use ($search) {
                $query->where('kode_surat', 'like', '%' . $search . '%')
                      ->orWhere('nama_surat', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('admin.jenis_surat.index', compact('jenisSurats', 'search'));
    }

    public function create()
    {
        return view('admin.jenis_surat.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_surat' => 'required|string|max:10|unique:jenis_surats',
            'nama_surat' => 'required|string|max:255',
        ]);

        JenisSurat::create($validatedData);

        return redirect()->route('admin.jenis_surat.index')->with('success', 'Jenis Surat berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $jenisSurat = JenisSurat::findOrFail($id);
        return view('admin.jenis_surat.show', compact('jenisSurat'));
    }

    public function edit(string $id)
    {
        $jenisSurat = JenisSurat::findOrFail($id);
        return view('admin.jenis_surat.edit', compact('jenisSurat'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'kode_surat' => 'required|string|max:10|unique:jenis_surats,kode_surat,' . $id,
            'nama_surat' => 'required|string|max:255',
        ]);

        $jenisSurat = JenisSurat::findOrFail($id);
        $jenisSurat->update($validatedData);

        return redirect()->route('admin.jenis_surat.index')->with('success', 'Jenis Surat berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        JenisSurat::findOrFail($id)->delete();
        return redirect()->route('admin.jenis_surat.index')->with('success', 'Jenis Surat berhasil dihapus.');
    }
}
