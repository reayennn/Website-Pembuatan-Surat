<x-app-layout>
    <x-slot name="header">Edit Jenis Surat</x-slot>

    <div style="max-width:520px">
        <div class="g-card" style="box-shadow:0 1px 4px rgba(0,0,0,.07)">
            <div class="g-card-header">
                <div><h3>Edit Jenis Surat</h3><p>Kode: {{ $jenisSurat->kode_surat }}</p></div>
            </div>
            <form action="{{ route('admin.jenis_surat.update', $jenisSurat->id) }}" method="POST" style="padding:20px">
                @csrf @method('PUT')
                @if($errors->any())
                    <div class="alert-error" style="margin-bottom:16px">
                        <ul style="list-style:disc;list-style-position:inside">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif
                <div style="display:flex;flex-direction:column;gap:16px">
                    <div>
                        <label class="f-label">Kode Surat</label>
                        <input type="text" name="kode_surat" readonly class="f-input" value="{{ old('kode_surat', $jenisSurat->kode_surat) }}">
                        <p style="font-size:.72rem;color:#9ca3af;margin-top:4px">Kode surat tidak dapat diubah</p>
                    </div>
                    <div>
                        <label class="f-label">Nama Jenis Surat <span style="color:#dc2626">*</span></label>
                        <input type="text" name="nama_surat" class="f-input" value="{{ old('nama_surat', $jenisSurat->nama_surat) }}" required>
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:20px">
                    <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Perbarui
                    </button>
                    <a href="{{ route('admin.jenis_surat.index') }}" class="btn btn-outline" style="flex:1;justify-content:center">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
