<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <nav class="flex items-center gap-2 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-green-700">Dashboard</a>
                <span class="text-gray-300">/</span>
                <a href="{{ route('admin.template_surat.index') }}" class="text-gray-400 hover:text-green-700">Template Surat</a>
                <span class="text-gray-300">/</span>
                <span class="font-semibold text-gray-700">Edit: {{ Str::limit($template->nama_surat ?? '-', 35) }}</span>
            </nav>
            <a href="{{ route('admin.template_surat.index') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-300 text-gray-600 text-xs font-semibold rounded-lg hover:bg-gray-50 transition shadow-sm">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <style>
        .quill-box { height: 180px; font-family: 'Times New Roman', serif; font-size: 11pt; }
        .ql-toolbar.ql-snow { border-radius: 8px 8px 0 0; background: #f8fafc; border-color: #d1d5db; }
        .ql-container.ql-snow { border-radius: 0 0 8px 8px; border-color: #d1d5db; font-size: 11pt; }

        .a4-paper { background: #fff; font-family: 'Times New Roman', Times, serif; font-size: 11pt; line-height: 1.8; padding: 36px 40px; min-height: 600px; box-shadow: 0 4px 20px rgba(0,0,0,.15); border-radius: 2px; }
        .a4-kop { display: flex; align-items: center; gap: 12px; border-bottom: 4px double #111; padding-bottom: 10px; margin-bottom: 14px; }
        .a4-kop img { width: 68px; height: 68px; object-fit: contain; flex-shrink: 0; }
        .a4-kop-ph { width: 68px; height: 68px; background: #f3f4f6; border: 1px dashed #9ca3af; border-radius: 3px; display: flex; align-items: center; justify-content: center; font-size: 7pt; color: #9ca3af; text-align: center; flex-shrink: 0; }
        .a4-kop-text { flex: 1; text-align: center; line-height: 1.3; padding-right: 68px; }
        .a4-kop-text .k1, .a4-kop-text .k2 { font-size: 9pt; font-weight: 700; margin: 0; }
        .a4-kop-text .k3 { font-size: 15pt; font-weight: 900; text-transform: uppercase; margin: 2px 0; }
        .a4-kop-text .k4 { font-size: 7pt; color: #555; margin: 0; }
        .a4-judul { text-align: center; margin: 16px 0 8px; }
        .a4-judul .title { font-size: 11.5pt; font-weight: 700; text-decoration: underline; text-transform: uppercase; }
        .a4-judul .nomor { font-size: 9.5pt; color: #555; margin-top: 2px; }
        .a4-ttd { display: flex; justify-content: flex-end; margin-top: 20px; }
        .a4-ttd-inner { text-align: center; min-width: 170px; font-size: 11pt; }
        .a4-ttd-inner .gap { height: 60px; }
        .a4-ttd-inner .nama { font-weight: bold; text-decoration: underline; }
        .a4-ttd-inner .nipd { font-size: 9.5pt; }

        /* Fixed data table inside preview */
        .data-table-fixed { width: 100%; margin: 12px 0 12px 18px; font-size: 10pt; }
        .data-table-fixed td { padding: 4px 6px; vertical-align: top; }
        .data-table-fixed td:first-child { width: 185px; }
        .data-table-fixed td:nth-child(2) { width: 14px; }

        .section-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            margin-bottom: 4px;
            margin-top: 12px;
        }
        .editable-badge {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 99px;
            margin-left: 6px;
            vertical-align: middle;
        }
        .fixed-badge {
            display: inline-block;
            background: #fef3c7;
            color: #92400e;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 99px;
            margin-left: 6px;
            vertical-align: middle;
        }

        /* Fallback Tailwind Classes (jika Vite tidak berjalan) */
        .rounded-md { border-radius: 0.375rem !important; }
        .rounded-lg { border-radius: 0.5rem !important; }
        .rounded-xl { border-radius: 0.75rem !important; }
        .rounded-2xl { border-radius: 1rem !important; }
        .rounded-t-2xl { border-top-left-radius: 1rem !important; border-top-right-radius: 1rem !important; }
        .rounded-b-2xl { border-bottom-left-radius: 1rem !important; border-bottom-right-radius: 1rem !important; }
        .bg-amber-50 { background-color: #fffbeb !important; }
        .border-amber-200 { border-color: #fde68a !important; border-style: solid !important; border-width: 1px !important; }
        .bg-blue-50 { background-color: #eff6ff !important; }
        .border-blue-200 { border-color: #bfdbfe !important; border-style: solid !important; border-width: 1px !important; }
        .bg-red-50 { background-color: #fef2f2 !important; }
        .border-red-200 { border-color: #fecaca !important; border-style: solid !important; border-width: 1px !important; }
        .bg-green-50 { background-color: #f0fdf4 !important; }
        .border-green-200 { border-color: #bbf7d0 !important; border-style: solid !important; border-width: 1px !important; }
        .border-gray-100 { border-color: #f3f4f6 !important; border-style: solid !important; border-width: 1px !important; }
        .border-gray-300 { border-color: #d1d5db !important; border-style: solid !important; border-width: 1px !important; }
    </style>

    <div class="py-8">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width: 1400px;">

            @if(session('success'))
                <div class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-xl text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-5 flex gap-3 bg-red-50 border border-red-200 rounded-xl px-5 py-3.5">
                    <ul class="text-sm text-red-700 list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                {{-- ======= KOLOM KIRI: FORM ======= --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                    <div class="flex items-center gap-3 px-6 py-4 bg-green-700">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-white font-bold text-sm">Edit Template Surat</h2>
                            <p class="text-green-200 text-xs">{{ $template->jenisSurat->nama_surat ?? '-' }}</p>
                        </div>
                    </div>

                    <form id="editForm" action="{{ route('admin.template_surat.update', $template->id) }}" method="POST" class="p-6 space-y-4">
                        @csrf @method('PATCH')

                        {{-- Nama Surat --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nama Surat <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_surat" id="f_nama"
                                   value="{{ old('nama_surat', $template->nama_surat) }}"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                        </div>

                        {{-- Jenis + Status --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Jenis Surat</label>
                                <div class="w-full px-3.5 py-2.5 border border-gray-200 bg-gray-50 rounded-xl text-sm text-gray-600">
                                    {{ $template->jenisSurat->nama_surat ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Status <span class="text-red-500">*</span></label>
                                <select name="status" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500 bg-white" required>
                                    <option value="Aktif" {{ old('status', $template->status) === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Tidak Aktif" {{ old('status', $template->status) === 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>

                        {{-- Judul Surat --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                                Judul Surat <span class="text-gray-400 normal-case font-normal text-xs">(tampil di dokumen PDF)</span>
                            </label>
                            <input type="text" name="judul_surat" id="f_judul"
                                   value="{{ old('judul_surat', $template->judul_surat) }}"
                                   placeholder="Judul yang tampil di tengah surat"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>

                        {{-- Info kotak kuning --}}
                        <div class="p-3 bg-blue-50 border border-blue-200 rounded-xl">
                            <p class="text-xs font-bold text-blue-700 mb-1">Info: Cara Edit Template</p>
                            <ul class="text-xs text-blue-600 space-y-0.5 list-disc list-inside">
                                <li><span class="font-semibold text-green-700">Paragraf Pembuka & Penutup</span> — bisa diedit bebas</li>
                                <li><span class="font-semibold text-amber-700">Tabel Data Masyarakat</span> — otomatis dari NIK, tidak perlu diedit</li>
                            </ul>
                        </div>

                        {{-- ISI PEMBUKA --}}
                        <div>
                            <p class="section-label">(1) Paragraf Pembuka <span class="editable-badge">[Bisa Diedit]</span></p>
                            <div id="quill-pembuka" class="quill-box"></div>
                            <textarea name="isi_pembuka" id="isi_pembuka" class="sr-only">{!! old('isi_pembuka', $template->isi_pembuka) !!}</textarea>
                        </div>

                        {{-- TABEL DATA — INFO SAJA (tidak diedit) --}}
                        <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl">
                            <p class="text-xs font-bold text-amber-700 mb-1">Catatan: Tabel Data Masyarakat <span class="fixed-badge">[Otomatis]</span></p>
                            <table style="font-size: 0.75rem; color: #b45309; font-family: monospace; margin-top: 8px; margin-bottom: 4px; border-collapse: collapse;">
                                <tbody>
                                    <tr><td style="padding-right: 24px; padding-bottom: 4px; vertical-align: top;">N a m a</td><td style="padding-right: 8px; padding-bottom: 4px; vertical-align: top;">:</td><td style="font-style: italic; color: #d97706; padding-bottom: 4px; vertical-align: top;">[dari NIK]</td></tr>
                                    <tr><td style="padding-right: 24px; padding-bottom: 4px; vertical-align: top;">NIK</td><td style="padding-right: 8px; padding-bottom: 4px; vertical-align: top;">:</td><td style="font-style: italic; color: #d97706; padding-bottom: 4px; vertical-align: top;">[dari NIK]</td></tr>
                                    <tr><td style="padding-right: 24px; padding-bottom: 4px; vertical-align: top;">Tempat/Tgl Lahir</td><td style="padding-right: 8px; padding-bottom: 4px; vertical-align: top;">:</td><td style="font-style: italic; color: #d97706; padding-bottom: 4px; vertical-align: top;">[dari NIK]</td></tr>
                                    <tr><td style="padding-right: 24px; padding-bottom: 4px; vertical-align: top;">Jenis Kelamin</td><td style="padding-right: 8px; padding-bottom: 4px; vertical-align: top;">:</td><td style="font-style: italic; color: #d97706; padding-bottom: 4px; vertical-align: top;">[dari NIK]</td></tr>
                                    <tr><td style="padding-right: 24px; padding-bottom: 4px; vertical-align: top;">Kewarganegaraan</td><td style="padding-right: 8px; padding-bottom: 4px; vertical-align: top;">:</td><td style="color: #d97706; padding-bottom: 4px; vertical-align: top;">Indonesia</td></tr>
                                    <tr><td style="padding-right: 24px; padding-bottom: 4px; vertical-align: top;">A g a m a</td><td style="padding-right: 8px; padding-bottom: 4px; vertical-align: top;">:</td><td style="font-style: italic; color: #d97706; padding-bottom: 4px; vertical-align: top;">[dari NIK]</td></tr>
                                    <tr><td style="padding-right: 24px; padding-bottom: 4px; vertical-align: top;">Pekerjaan</td><td style="padding-right: 8px; padding-bottom: 4px; vertical-align: top;">:</td><td style="font-style: italic; color: #d97706; padding-bottom: 4px; vertical-align: top;">[dari NIK]</td></tr>
                                    <tr><td style="padding-right: 24px; padding-bottom: 4px; vertical-align: top;">A l a m a t</td><td style="padding-right: 8px; padding-bottom: 4px; vertical-align: top;">:</td><td style="font-style: italic; color: #d97706; padding-bottom: 4px; vertical-align: top;">[dari NIK]</td></tr>
                                </tbody>
                            </table>
                            <p class="text-xs text-amber-500 italic mt-1.5">Tabel ini tidak perlu diedit — diisi otomatis saat surat dicetak.</p>
                        </div>

                        {{-- ISI PENUTUP --}}
                        <div>
                            <p class="section-label">(2) Paragraf Penutup <span class="editable-badge">[Bisa Diedit]</span></p>
                            <div id="quill-penutup" class="quill-box"></div>
                            <textarea name="isi_penutup" id="isi_penutup" class="sr-only">{!! old('isi_penutup', $template->isi_penutup) !!}</textarea>
                        </div>

                        {{-- Keterangan --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                                Keterangan <span class="text-gray-400 normal-case font-normal text-xs">(tampil di daftar)</span>
                            </label>
                            <textarea name="keterangan" rows="2"
                                      class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500 resize-none">{{ old('keterangan', $template->keterangan) }}</textarea>
                        </div>

                        {{-- Persyaratan --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Persyaratan / Catatan</label>
                            <textarea name="persyaratan" rows="2"
                                      class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500 resize-none">{{ old('persyaratan', $template->persyaratan) }}</textarea>
                        </div>

                        {{-- Submit --}}
                        <div class="pt-2">
                            <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 py-3 bg-green-700 hover:bg-green-800 text-white font-bold text-sm rounded-xl transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ======= KOLOM KANAN: PREVIEW ======= --}}
                <div class="flex flex-col">
                    <div class="flex items-center justify-between bg-gray-800 rounded-t-2xl px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="flex gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            </div>
                            <span class="text-gray-300 text-xs font-medium ml-1">Preview Dokumen Surat</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                            <span class="text-green-400 text-xs font-semibold">Live</span>
                        </div>
                    </div>

                    <div class="flex-1 bg-gray-500 rounded-b-2xl p-5 overflow-auto" style="min-height: 560px; max-height: 82vh;">
                        <div class="a4-paper mx-auto" style="max-width: 560px;">

                            {{-- Kop --}}
                            <div class="a4-kop">
                                @if($kopSurat->logo)
                                    <img src="{{ Storage::url($kopSurat->logo) }}" alt="Logo">
                                @else
                                    <div class="a4-kop-ph">[Logo]</div>
                                @endif
                                <div class="a4-kop-text">
                                    <p class="k1">{{ $kopSurat->baris_1 ?: 'PEMERINTAH KABUPATEN DOMPU' }}</p>
                                    <p class="k2">{{ $kopSurat->baris_2 ?: 'KECAMATAN PEKAT' }}</p>
                                    <p class="k3">{{ $kopSurat->baris_3 ?: 'DESA KAROMBO' }}</p>
                                    <p class="k4">{{ $kopSurat->baris_4 ?: 'Jalan Raya Karombo Pekat Dompu - NTB' }}</p>
                                </div>
                            </div>

                            {{-- Judul --}}
                            <div class="a4-judul">
                                <p class="title" id="p_judul">{{ strtoupper($template->judul_surat ?: $template->nama_surat ?: 'JUDUL SURAT') }}</p>
                                <p class="nomor">Nomor : _____ / Pem.DSK / _____ / {{ date('Y') }}</p>
                            </div>

                            {{-- Pembuka (live preview) --}}
                            <div id="p_pembuka" style="font-size:10.5pt;line-height:1.75;margin-bottom:10px;text-align:justify">
                                {!! $template->isi_pembuka ?: '<span style="color:#b0b8c4;font-style:italic">Paragraf pembuka akan muncul di sini...</span>' !!}
                            </div>

                            {{-- Tabel Data Masyarakat (TETAP / TIDAK BISA DIEDIT) --}}
                            <table class="data-table-fixed" style="font-size:10.5pt">
                                <tr><td style="letter-spacing:3px">N a m a</td><td>:</td><td><strong>[Nama Pemohon]</strong></td></tr>
                                <tr><td>Nik</td><td>:</td><td>[NIK Pemohon]</td></tr>
                                <tr><td>Tempat / Tanggal Lahir</td><td>:</td><td>[Tempat] / [Tanggal Lahir]</td></tr>
                                <tr><td>Jenis Kelamin</td><td>:</td><td>[Jenis Kelamin]</td></tr>
                                <tr><td>Kewarganegaraan</td><td>:</td><td>Indonesia</td></tr>
                                <tr><td style="letter-spacing:3px">A g a m a</td><td>:</td><td>[Agama]</td></tr>
                                <tr><td>Pekerjaan</td><td>:</td><td>[Pekerjaan]</td></tr>
                                <tr><td style="letter-spacing:3px">A l a m a t</td><td>:</td><td>[Alamat]</td></tr>
                            </table>

                            {{-- Penutup (live preview) --}}
                            <div id="p_penutup" style="font-size:10.5pt;line-height:1.75;margin-top:10px;text-align:justify">
                                {!! $template->isi_penutup ?: '<span style="color:#b0b8c4;font-style:italic">Paragraf penutup akan muncul di sini...</span>' !!}
                            </div>

                            {{-- TTD --}}
                            <div class="a4-ttd">
                                <div class="a4-ttd-inner">
                                    <p>Karombo, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                                    <p>Kepala {{ $kopSurat->baris_3 ?: 'Desa Karombo' }},</p>
                                    <div class="gap"></div>
                                    <p class="nama">{{ $tandaTangan->nama ?: '.................................' }}</p>
                                    @if($tandaTangan->nipd)<p class="nipd">NIPD. {{ $tandaTangan->nipd }}</p>@endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script>
    var quillPembuka, quillPenutup;
    var toolbarOptions = [
        [{ header: [false, 1, 2] }],
        ['bold', 'italic', 'underline'],
        [{ align: [] }],
        ['clean']
    ];

    (function () {
        quillPembuka = new Quill('#quill-pembuka', { theme: 'snow', modules: { toolbar: toolbarOptions } });
        quillPenutup = new Quill('#quill-penutup', { theme: 'snow', modules: { toolbar: toolbarOptions } });

        var taPembuka = document.getElementById('isi_pembuka');
        var taPenutup = document.getElementById('isi_penutup');

        if (taPembuka.value.trim()) quillPembuka.root.innerHTML = taPembuka.value;
        if (taPenutup.value.trim()) quillPenutup.root.innerHTML = taPenutup.value;

        // Live preview sync
        quillPembuka.on('text-change', function () {
            var el = document.getElementById('p_pembuka');
            el.innerHTML = quillPembuka.getText().trim()
                ? quillPembuka.root.innerHTML
                : '<span style="color:#b0b8c4;font-style:italic">Paragraf pembuka akan muncul di sini...</span>';
        });

        quillPenutup.on('text-change', function () {
            var el = document.getElementById('p_penutup');
            el.innerHTML = quillPenutup.getText().trim()
                ? quillPenutup.root.innerHTML
                : '<span style="color:#b0b8c4;font-style:italic">Paragraf penutup akan muncul di sini...</span>';
        });

        // Update judul preview live
        document.getElementById('f_judul').addEventListener('input', function () {
            var v = (this.value.trim() || document.getElementById('f_nama').value.trim() || 'JUDUL SURAT').toUpperCase();
            document.getElementById('p_judul').textContent = v;
        });
        document.getElementById('f_nama').addEventListener('input', function () {
            var judul = document.getElementById('f_judul').value.trim();
            if (!judul) document.getElementById('p_judul').textContent = (this.value.trim() || 'JUDUL SURAT').toUpperCase();
        });

        // On submit — sync textarea values
        document.getElementById('editForm').addEventListener('submit', function (e) {
            taPembuka.value = quillPembuka.root.innerHTML;
            taPenutup.value = quillPenutup.root.innerHTML;
        });
    })();
    </script>
</x-app-layout>
