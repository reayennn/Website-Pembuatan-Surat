<x-app-layout>
    <x-slot name="header">Kop Surat</x-slot>

    @if(session('success'))
        <div class="alert-success">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Preview --}}
    <div class="g-card" style="margin-bottom:20px;box-shadow:0 1px 4px rgba(0,0,0,.07)">
        <div class="g-card-header"><div><h3>Preview Kop Surat</h3><p>Tampilan kepala surat pada dokumen PDF</p></div></div>
        <div style="padding:24px">
            <div style="border-bottom:4px double #1e293b;padding-bottom:16px;display:flex;align-items:center;gap:16px;background:#fff;font-family:'Times New Roman',Times,serif">
                @if($kopSurat->logo)
                    <img src="{{ Storage::url($kopSurat->logo) }}" alt="Logo" style="height:80px;width:80px;object-fit:contain">
                @else
                    <div style="height:80px;width:80px;border:2px dashed #d1d5db;display:flex;align-items:center;justify-content:center;color:#9ca3af;font-size:.7rem;text-align:center;border-radius:6px">Logo<br>Belum Ada</div>
                @endif
                <div style="text-align:center;flex:1;line-height:1.3">
                    <p style="font-weight:700;font-size:1rem">{{ $kopSurat->baris_1 ?? '—' }}</p>
                    <p style="font-weight:700;font-size:.9rem">{{ $kopSurat->baris_2 ?? '—' }}</p>
                    <p style="font-weight:900;font-size:1.1rem;text-transform:uppercase">{{ $kopSurat->baris_3 ?? '—' }}</p>
                    <p style="font-size:.8rem;color:#4b5563;margin-top:3px">{{ $kopSurat->baris_4 ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Grid --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

        {{-- Logo --}}
        <div class="g-card" style="box-shadow:0 1px 4px rgba(0,0,0,.07)">
            <div class="g-card-header"><div><h3>Logo Instansi</h3><p>JPG/PNG, maks 1MB</p></div></div>
            <form action="{{ route('admin.kop_surat.update') }}" method="POST" enctype="multipart/form-data" style="padding:20px">
                @csrf @method('PATCH')
                <div style="display:flex;flex-direction:column;align-items:center;gap:12px">
                    @if($kopSurat->logo)
                        <img src="{{ Storage::url($kopSurat->logo) }}" alt="Logo" style="height:100px;width:100px;object-fit:contain;border:1px solid #e5e7eb;border-radius:10px;padding:4px">
                        <label style="display:flex;align-items:center;gap:6px;font-size:.8rem;color:#dc2626;cursor:pointer">
                            <input type="checkbox" name="hapus_logo" value="1"> Hapus logo saat ini
                        </label>
                    @else
                        <div style="height:100px;width:100px;border:2px dashed #d1d5db;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#9ca3af;font-size:.7rem;border-radius:10px;gap:4px">
                            <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Belum ada
                        </div>
                    @endif
                    <div style="width:100%">
                        <label class="f-label">Pilih File</label>
                        <input type="file" name="logo" accept="image/png,image/jpeg" class="f-input" style="padding:6px">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:16px">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Simpan Logo
                </button>
            </form>
        </div>

        {{-- Info Kop --}}
        <div class="g-card" style="box-shadow:0 1px 4px rgba(0,0,0,.07)">
            <div class="g-card-header"><div><h3>Informasi Kop Surat</h3><p>4 baris teks kepala surat</p></div></div>
            <form action="{{ route('admin.kop_surat.update') }}" method="POST" style="padding:20px">
                @csrf @method('PATCH')
                <div style="display:flex;flex-direction:column;gap:14px">
                    @foreach(['baris_1' => 'Baris 1 — Pemerintah / Instansi', 'baris_2' => 'Baris 2 — Kecamatan', 'baris_3' => 'Baris 3 — Nama Desa (HURUF BESAR)', 'baris_4' => 'Baris 4 — Alamat'] as $field => $label)
                        <div>
                            <label class="f-label">{{ $label }}</label>
                            <input type="text" name="{{ $field }}" class="f-input" value="{{ old($field, $kopSurat->$field) }}">
                        </div>
                    @endforeach
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:18px">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    Perbarui Data
                </button>
            </form>
        </div>
    </div>

</x-app-layout>
