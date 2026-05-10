<x-app-layout>
    <x-slot name="header">Tambah Jenis Surat</x-slot>

    <div style="max-width:520px">
        <div class="g-card" style="box-shadow:0 1px 4px rgba(0,0,0,.07)">
            <div class="g-card-header">
                <div><h3>Tambah Jenis Surat</h3><p>Isi kode dan nama jenis surat baru</p></div>
            </div>
            <form action="{{ route('admin.jenis_surat.store') }}" method="POST" style="padding:20px">
                @csrf
                @if($errors->any())
                    <div class="alert-error" style="margin-bottom:16px">
                        <ul style="list-style:disc;list-style-position:inside">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif
                <div style="display:flex;flex-direction:column;gap:16px">
                    <div>
                        <label class="f-label">Kode Surat <span style="color:#dc2626">*</span> <span style="color:#9ca3af;text-transform:none;font-size:.68rem">(maks. 10 karakter)</span></label>
                        <input type="text" name="kode_surat" maxlength="10" class="f-input" placeholder="Contoh: SKD-001" value="{{ old('kode_surat') }}" required>
                    </div>
                    <div>
                        <label class="f-label">Nama Jenis Surat <span style="color:#dc2626">*</span></label>
                        <input type="text" name="nama_surat" class="f-input" placeholder="Contoh: Surat Keterangan Domisili" value="{{ old('nama_surat') }}" required>
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:20px">
                    <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan
                    </button>
                    <a href="{{ route('admin.jenis_surat.index') }}" class="btn btn-outline" style="flex:1;justify-content:center">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
