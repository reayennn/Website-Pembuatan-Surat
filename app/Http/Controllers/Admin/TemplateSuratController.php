<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\KopSurat;
use App\Models\TandaTangan;
use App\Models\TemplateSurat;
use Illuminate\Http\Request;

class TemplateSuratController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $templates = TemplateSurat::with('jenisSurat')
            ->when($search, function ($query) use ($search) {
                $query->where('nama_surat', 'like', '%' . $search . '%')
                      ->orWhere('keterangan', 'like', '%' . $search . '%')
                      ->orWhereHas('jenisSurat', function ($q) use ($search) {
                          $q->where('nama_surat', 'like', '%' . $search . '%');
                      });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('admin.template_surat.index', compact('templates', 'search', 'status'));
    }

    public function create()
    {
        $jenisSurats = JenisSurat::all();
        $kopSurat    = KopSurat::getSettings();
        $tandaTangan = TandaTangan::getSettings();
        return view('admin.template_surat.create', compact('jenisSurats', 'kopSurat', 'tandaTangan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_surat'     => 'required|string|max:255',
            'judul_surat'    => 'nullable|string|max:255',
            'keterangan'     => 'nullable|string',
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'isi_pembuka'    => 'nullable|string',
            'isi_penutup'    => 'nullable|string',
            'persyaratan'    => 'nullable|string',
            'status'         => 'required|in:Aktif,Tidak Aktif',
        ]);

        TemplateSurat::create($request->only([
            'nama_surat', 'judul_surat', 'keterangan',
            'jenis_surat_id', 'isi_pembuka', 'isi_penutup', 'persyaratan', 'status',
        ]));

        return redirect()->route('admin.template_surat.index')->with('success', 'Template surat berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $template    = TemplateSurat::findOrFail($id);
        $jenisSurats = JenisSurat::all();
        $kopSurat    = KopSurat::getSettings();
        $tandaTangan = TandaTangan::getSettings();
        return view('admin.template_surat.edit', compact('template', 'jenisSurats', 'kopSurat', 'tandaTangan'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_surat'  => 'required|string|max:255',
            'judul_surat' => 'nullable|string|max:255',
            'keterangan'  => 'nullable|string',
            'isi_pembuka' => 'nullable|string',
            'isi_penutup' => 'nullable|string',
            'persyaratan' => 'nullable|string',
            'status'      => 'required|in:Aktif,Tidak Aktif',
        ]);

        $template = TemplateSurat::findOrFail($id);
        $template->update($request->only([
            'nama_surat', 'judul_surat', 'keterangan',
            'isi_pembuka', 'isi_penutup', 'persyaratan', 'status',
        ]));

        return redirect()->route('admin.template_surat.index')->with('success', 'Template surat berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        TemplateSurat::findOrFail($id)->delete();
        return redirect()->route('admin.template_surat.index')->with('success', 'Template surat berhasil dihapus.');
    }
}
