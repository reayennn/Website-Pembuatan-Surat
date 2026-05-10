<x-app-layout>
    <x-slot name="header">Tanda Tangan</x-slot>

    @if(session('success'))
        <div class="alert-success">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:960px">

        {{-- Form --}}
        <div class="g-card" style="box-shadow:0 1px 4px rgba(0,0,0,.07)">
            <div class="g-card-header"><div><h3>Data Pejabat Penandatangan</h3><p>Nama, NIPD, dan QR Code kepala desa</p></div></div>
            <form action="{{ route('admin.tanda_tangan.update') }}" method="POST" enctype="multipart/form-data" style="padding:20px">
                @csrf @method('PATCH')
                @if($errors->any())
                    <div class="alert-error">
                        <ul style="list-style:disc;list-style-position:inside">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif
                <div style="display:flex;flex-direction:column;gap:14px">
                    <div>
                        <label class="f-label">NIPD <span style="color:#9ca3af;text-transform:none;font-size:.68rem">(opsional)</span></label>
                        <input type="text" name="nipd" class="f-input" value="{{ old('nipd', $tandaTangan->nipd) }}" placeholder="Nomor Induk Pegawai Desa">
                    </div>
                    <div>
                        <label class="f-label">Nama Kepala Desa <span style="color:#dc2626">*</span></label>
                        <input type="text" name="nama" class="f-input" required value="{{ old('nama', $tandaTangan->nama) }}" placeholder="Nama lengkap kepala desa">
                    </div>

                    {{-- Upload Gambar QR Code --}}
                    <div>
                        <label class="f-label">Gambar QR Code TTD <span style="color:#9ca3af;text-transform:none;font-size:.68rem">(PNG/JPG, maks 2MB)</span></label>

                        @if($tandaTangan->gambar_qr)
                            <div style="margin-bottom:10px;padding:10px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;display:flex;align-items:center;gap:12px">
                                <img src="{{ Storage::url($tandaTangan->gambar_qr) }}" alt="QR Code" style="width:64px;height:64px;object-fit:contain;border:1px solid #d1d5db;border-radius:4px">
                                <div>
                                    <p style="font-size:.8rem;font-weight:600;color:#166534">QR Code sudah diupload ✓</p>
                                    <p style="font-size:.72rem;color:#6b7280">Upload file baru untuk menggantinya</p>
                                </div>
                            </div>
                        @endif

                        <input type="file" name="gambar_qr" accept="image/png,image/jpeg,image/jpg"
                            style="display:block;width:100%;padding:8px;border:1.5px dashed #d1d5db;border-radius:8px;font-size:.85rem;background:#f9fafb;cursor:pointer"
                            onchange="previewQR(this)">
                        <p style="font-size:.72rem;color:#9ca3af;margin-top:4px">Upload gambar QR Code TTD Kepala Desa yang akan muncul di setiap surat</p>

                        {{-- Preview gambar yang dipilih --}}
                        <div id="qr-preview-wrap" style="display:none;margin-top:10px;text-align:center">
                            <img id="qr-preview-img" src="" alt="Preview" style="width:80px;height:80px;object-fit:contain;border:1px solid #d1d5db;border-radius:4px">
                            <p style="font-size:.72rem;color:#6b7280;margin-top:4px">Preview QR Code</p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:18px">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- Preview --}}
        <div class="g-card" style="box-shadow:0 1px 4px rgba(0,0,0,.07)">
            <div style="padding:12px 18px;background:#1e293b;display:flex;align-items:center;gap:8px">
                <span style="width:11px;height:11px;background:#ef4444;border-radius:50%;display:inline-block"></span>
                <span style="width:11px;height:11px;background:#eab308;border-radius:50%;display:inline-block"></span>
                <span style="width:11px;height:11px;background:#22c55e;border-radius:50%;display:inline-block"></span>
                <span style="color:#94a3b8;font-size:.72rem;margin-left:8px;font-family:monospace">preview-tanda-tangan.pdf</span>
            </div>
            <div style="padding:32px;display:flex;align-items:center;justify-content:center;min-height:220px">
                <div style="text-align:center;font-family:'Times New Roman',Times,serif;font-size:.9rem">
                    <p>Karombo, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <p style="margin-top:4px">Kepala Desa Karombo,</p>

                    @if($tandaTangan->gambar_qr)
                        <img src="{{ Storage::url($tandaTangan->gambar_qr) }}" alt="QR TTD"
                             style="width:90px;height:90px;object-fit:contain;display:block;margin:10px auto">
                        <p style="font-size:.7rem;color:#6b7280">Tanda Tangan Digital</p>
                    @else
                        <div style="width:90px;height:90px;border:2px dashed #d1d5db;border-radius:4px;display:flex;align-items:center;justify-content:center;margin:10px auto">
                            <span style="font-size:.68rem;color:#9ca3af;text-align:center">Upload<br>QR Code</span>
                        </div>
                    @endif

                    <p style="font-weight:700;text-decoration:underline;margin-top:4px">{{ $tandaTangan->nama ?: '...............................' }}</p>
                    @if($tandaTangan->nipd)
                        <p style="font-size:.8rem;color:#4b5563;margin-top:2px">NIPD. {{ $tandaTangan->nipd }}</p>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <script>
        function previewQR(input) {
            const wrap = document.getElementById('qr-preview-wrap');
            const img  = document.getElementById('qr-preview-img');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => { img.src = e.target.result; wrap.style.display = 'block'; };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
