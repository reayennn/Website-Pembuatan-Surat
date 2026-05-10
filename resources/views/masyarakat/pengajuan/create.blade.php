<x-app-layout>
    <x-slot name="header">Pengajuan Surat</x-slot>

    {{-- Page header --}}
    <div style="background:#15803d;border-radius:16px;padding:22px 28px;margin-bottom:20px;position:relative;overflow:hidden">
        <div style="position:absolute;inset:0;opacity:.05;background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Crect x='5' y='5' width='50' height='50' fill='none' stroke='white' stroke-width='1'/%3E%3C/svg%3E\");background-size:60px"></div>
        <div style="position:relative;display:flex;align-items:center;justify-content:space-between">
            <div>
                <h2 style="color:#fff;font-size:1.3rem;font-weight:800;display:flex;align-items:center;gap:8px">
                    <svg width="20" height="20" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Pengajuan Surat
                </h2>
                <p style="color:rgba(255,255,255,.6);font-size:.78rem;margin-top:2px">Pilih surat → Edit data → Lihat preview → Kirim</p>
            </div>
            <div style="font-size:.72rem;color:rgba(255,255,255,.5)">
                <a href="{{ route('dashboard') }}" style="color:rgba(255,255,255,.65);text-decoration:none">Dashboard</a>
                <span style="margin:0 5px">/</span>
                <span style="color:#fff">Pengajuan</span>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('error'))
        <div style="background:#fef2f2;border:1.5px solid #fca5a5;border-radius:10px;padding:14px 18px;margin-bottom:16px;display:flex;align-items:flex-start;gap:12px">
            <svg width="18" height="18" fill="none" stroke="#dc2626" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p style="font-weight:700;color:#b91c1c;font-size:.82rem">Pengajuan Ditolak</p>
                <p style="color:#dc2626;font-size:.78rem;margin-top:2px">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if(!Auth::user()->penduduk_id)
        <div class="g-card" style="padding:40px;text-align:center">
            <svg width="48" height="48" fill="none" stroke="#d1d5db" stroke-width="1.2" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            <p style="font-weight:700;color:#6b7280;margin-bottom:4px">Akses terbatas</p>
            <p style="font-size:.82rem;color:#9ca3af">Hubungi Admin Desa untuk menautkan akun Anda dengan NIK</p>
        </div>
    @else

    {{-- MAIN 3-PANEL GRID --}}
    <div style="display:grid;grid-template-columns:280px 1fr 1fr;gap:16px;align-items:start">

        {{-- ══ PANEL 1: FORM PENGAJUAN ══ --}}
        <div style="display:flex;flex-direction:column;gap:16px">

            {{-- Pilih Jenis Surat --}}
            <div class="g-card">
                <div style="padding:12px 16px;background:#0f4c2a;border-radius:14px 14px 0 0">
                    <p style="color:#fff;font-size:.78rem;font-weight:700">(1) Pilih Jenis Surat</p>
                </div>
                <div style="padding:14px">
                    <select id="jenis-surat-select" class="f-input" style="font-size:.82rem">
                        <option value="">— Pilih Surat —</option>
                        @foreach($jenisSurats as $j)
                            <option value="{{ $j->id }}" {{ old('jenis_surat_id') == $j->id ? 'selected' : '' }}>
                                {{ $j->nama_surat }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Edit Data Surat --}}
            <div class="g-card" id="panel-data" style="opacity:.4;pointer-events:none;transition:all .3s">
                <div style="padding:12px 16px;background:#0f4c2a;border-radius:14px 14px 0 0">
                    <p id="label-data-utama" style="color:#fff;font-size:.78rem;font-weight:700">(2) Edit Data (Opsional)</p>
                </div>
                <div style="padding:14px;display:flex;flex-direction:column;gap:10px">
                    <p style="font-size:.68rem;color:#9ca3af;margin-bottom:2px;line-height:1.5">Data diambil dari NIK Anda. Edit jika ada yang perlu diperbaiki.</p>

                    @php $p = Auth::user()->penduduk; @endphp

                    <div>
                        <label class="f-label" style="font-size:.65rem">Nama</label>
                        <input id="f-nama" class="f-input" style="font-size:.8rem;padding:7px 10px" type="text" value="{{ $p->nama }}" placeholder="Nama lengkap">
                    </div>
                    <div>
                        <label class="f-label" style="font-size:.65rem">NIK</label>
                        <input id="f-nik" class="f-input" style="font-size:.8rem;padding:7px 10px;background:#f9fafb;color:#6b7280" type="text" value="{{ $p->nik }}" readonly>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                        <div>
                            <label class="f-label" style="font-size:.65rem">Tempat Lahir</label>
                            <input id="f-tempat" class="f-input" style="font-size:.8rem;padding:7px 10px" type="text" value="{{ $p->tempat_lahir }}" placeholder="Kota">
                        </div>
                        <div>
                            <label class="f-label" style="font-size:.65rem">Tgl. Lahir</label>
                            <input id="f-tgl" class="f-input" style="font-size:.8rem;padding:7px 10px" type="date" value="{{ $p->tanggal_lahir }}">
                        </div>
                    </div>
                    <div>
                        <label class="f-label" style="font-size:.65rem">Jenis Kelamin</label>
                        <select id="f-gender" class="f-input" style="font-size:.8rem;padding:7px 10px">
                            <option value="Laki-laki" {{ $p->jenis_kelamin === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ $p->jenis_kelamin === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="f-label" style="font-size:.65rem">Status Perkawinan</label>
                        <div id="f-status-kawin-display" style="width:100%;padding:7px 10px;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.8rem;color:#374151;cursor:not-allowed">
                            {{ $p->status_perkawinan ?? 'Belum Kawin' }}
                        </div>
                    </div>
                    <div>
                        <label class="f-label" style="font-size:.65rem">Agama</label>
                        <input id="f-agama" class="f-input" style="font-size:.8rem;padding:7px 10px" type="text" value="{{ $p->agama }}" placeholder="Agama">
                    </div>
                    <div>
                        <label class="f-label" style="font-size:.65rem">Pekerjaan</label>
                        <input id="f-pekerjaan" class="f-input" style="font-size:.8rem;padding:7px 10px" type="text" value="{{ $p->pekerjaan }}" placeholder="Pekerjaan">
                    </div>
                    <div>
                        <label class="f-label" style="font-size:.65rem">Alamat</label>
                        <textarea id="f-alamat" class="f-input" style="font-size:.8rem;padding:7px 10px;resize:vertical" rows="2" placeholder="Alamat lengkap">{{ $p->alamat }}</textarea>
                    </div>
                    <div>
                        <label class="f-label" style="font-size:.65rem">Keperluan / Tujuan <span style="color:#dc2626">*</span></label>
                        <textarea id="f-keperluan" class="f-input" style="font-size:.8rem;padding:7px 10px;resize:vertical" rows="3" placeholder="Untuk keperluan..." minlength="5">{{ old('keperluan') }}</textarea>
                        <div style="display:flex;justify-content:space-between;margin-top:3px">
                            <span id="kep-hint" style="font-size:.66rem;color:#dc2626;display:none">Minimal 5 karakter</span>
                            <span style="flex:1"></span>
                            <span id="kep-counter" style="font-size:.66rem;color:#9ca3af">0 karakter</span>
                        </div>
                    </div>

                    <!-- DATA KEMATIAN KHUSUS ID 4 -->
                    <div id="panel-kematian" style="display:none;margin-top:20px;border-top:1px dashed #d1d5db;padding-top:14px">
                        <p style="font-size:.78rem;font-weight:700;color:#374151;margin-bottom:10px">Data Kematian</p>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                            <div>
                                <label class="f-label" style="font-size:.65rem">Hari Meninggal</label>
                                <input id="f-kem-hari" class="f-input" style="font-size:.8rem;padding:7px 10px" type="text" placeholder="Contoh: Senin">
                            </div>
                            <div>
                                <label class="f-label" style="font-size:.65rem">Tanggal</label>
                                <input id="f-kem-tgl" class="f-input" style="font-size:.8rem;padding:7px 10px" type="date">
                            </div>
                        </div>
                        <div style="margin-top:8px">
                            <label class="f-label" style="font-size:.65rem">Tempat Meninggal</label>
                            <input id="f-kem-tempat" class="f-input" style="font-size:.8rem;padding:7px 10px" type="text" placeholder="Contoh: RSUD Dompu">
                        </div>
                        <div style="margin-top:8px">
                            <label class="f-label" style="font-size:.65rem">Penyebab Kematian</label>
                            <input id="f-kem-sebab" class="f-input" style="font-size:.8rem;padding:7px 10px" type="text" placeholder="Contoh: Sakit">
                        </div>

                        <p style="font-size:.78rem;font-weight:700;color:#374151;margin-top:16px;margin-bottom:10px">Data Pelapor</p>
                        <div style="margin-top:8px">
                            <label class="f-label" style="font-size:.65rem">Nama Lengkap</label>
                            <input id="f-pel-nama" class="f-input" style="font-size:.8rem;padding:7px 10px" type="text" placeholder="Nama pelapor">
                        </div>
                        <div style="margin-top:8px">
                            <label class="f-label" style="font-size:.65rem">Nomor KTP</label>
                            <input id="f-pel-ktp" class="f-input" style="font-size:.8rem;padding:7px 10px" type="text" placeholder="NIK pelapor">
                        </div>
                        <div style="margin-top:8px">
                            <label class="f-label" style="font-size:.65rem">Tempat Tgl Lahir / Umur</label>
                            <input id="f-pel-ttl" class="f-input" style="font-size:.8rem;padding:7px 10px" type="text" placeholder="Contoh: Dompu, 1 Januari 1990">
                        </div>
                        <div style="margin-top:8px">
                            <label class="f-label" style="font-size:.65rem">Pekerjaan</label>
                            <input id="f-pel-kerja" class="f-input" style="font-size:.8rem;padding:7px 10px" type="text" placeholder="Pekerjaan pelapor">
                        </div>
                        <div style="margin-top:8px">
                            <label class="f-label" style="font-size:.65rem">Alamat</label>
                            <textarea id="f-pel-alamat" class="f-input" style="font-size:.8rem;padding:7px 10px;resize:vertical" rows="2" placeholder="Alamat pelapor"></textarea>
                        </div>
                        <div style="margin-top:8px">
                            <label class="f-label" style="font-size:.65rem">Hubungan Pelapor dengan Yang Meninggal</label>
                            <input id="f-pel-hub" class="f-input" style="font-size:.8rem;padding:7px 10px" type="text" placeholder="Contoh: Anak Kandung, Suami/Istri">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div id="panel-submit" style="opacity:.4;pointer-events:none;transition:all .3s">

                {{-- Validation Banner (injected by JS) --}}
                <div id="validation-banner" style="display:none;margin-bottom:12px;border-radius:12px;overflow:hidden;font-family:'Inter',sans-serif;font-size:.78rem"></div>

                <form action="{{ route('pengajuan.store') }}" method="POST" id="form-pengajuan" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="jenis_surat_id"  id="hidden-jenis">
                    <input type="hidden" name="keperluan"       id="hidden-keperluan">
                    <input type="hidden" name="nama"            id="hidden-nama">
                    <input type="hidden" name="tempat_lahir"    id="hidden-tempat">
                    <input type="hidden" name="tanggal_lahir"   id="hidden-tgl">
                    <input type="hidden" name="jenis_kelamin"   id="hidden-gender">
                    <input type="hidden" name="status_perkawinan" id="hidden-status-kawin">
                    <input type="hidden" name="agama"           id="hidden-agama">
                    <input type="hidden" name="pekerjaan"       id="hidden-pekerjaan">
                    <input type="hidden" name="alamat"          id="hidden-alamat">

                    <!-- Extra fields for Surat Kematian -->
                    <input type="hidden" name="kematian_hari" id="h-kem-hari">
                    <input type="hidden" name="kematian_tanggal" id="h-kem-tgl">
                    <input type="hidden" name="kematian_tempat" id="h-kem-tempat">
                    <input type="hidden" name="kematian_penyebab" id="h-kem-sebab">
                    <input type="hidden" name="pelapor_nama" id="h-pel-nama">
                    <input type="hidden" name="pelapor_nik" id="h-pel-ktp">
                    <input type="hidden" name="pelapor_ttl" id="h-pel-ttl">
                    <input type="hidden" name="pelapor_pekerjaan" id="h-pel-kerja">
                    <input type="hidden" name="pelapor_alamat" id="h-pel-alamat">
                    <input type="hidden" name="pelapor_hubungan" id="h-pel-hub">

                    @if($errors->any())
                        <div class="alert-error" style="margin-bottom:10px;font-size:.78rem">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="g-card" style="margin-bottom:16px;padding:14px;background:#f9fafb">
                        <p style="font-size:.78rem;font-weight:700;color:#374151;margin-bottom:6px">Persyaratan Surat</p>
                        <div id="syarat-container" style="font-size:.75rem;color:#4b5563;margin-bottom:12px;line-height:1.5">
                            Pilih jenis surat untuk melihat persyaratan.
                        </div>

                        {{-- Area Upload Multi-File --}}
                        <div style="border:2px dashed #d1d5db;border-radius:10px;padding:14px;background:#fff;transition:border-color .2s" id="upload-area">
                            <p style="font-size:.72rem;font-weight:700;color:#374151;margin-bottom:4px">Upload Berkas Persyaratan</p>
                            <p style="font-size:.67rem;color:#6b7280;margin-bottom:10px;line-height:1.5">
                                Jika berkas lebih dari 1, pilih satu per satu.<br>
                                Format: PDF, JPG, PNG &bull; Maks. 5MB per berkas &bull; Maks. 5 berkas.
                            </p>

                            {{-- Tombol tambah file --}}
                            <label for="f-file-syarat-trigger" id="btn-tambah-berkas"
                                style="display:inline-flex;align-items:center;gap:6px;background:#15803d;color:#fff;border-radius:8px;padding:7px 14px;font-size:.75rem;font-weight:700;cursor:pointer;transition:background .2s">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Berkas
                            </label>
                            <input type="file" id="f-file-syarat-trigger" accept=".pdf,.jpg,.jpeg,.png" style="display:none" multiple>

                            {{-- Hidden input yang akan dikirim (diisi via JS DataTransfer) --}}
                            <input type="file" id="f-file-syarat" name="file_persyaratan[]" accept=".pdf,.jpg,.jpeg,.png" style="display:none" multiple>

                            {{-- Daftar file yang dipilih --}}
                            <div id="file-list" style="margin-top:10px;display:flex;flex-direction:column;gap:6px"></div>

                            {{-- Counter --}}
                            <p id="file-counter" style="font-size:.65rem;color:#9ca3af;margin-top:8px;display:none">
                                <span id="file-count">0</span> berkas dipilih &bull; Total: <span id="file-size-total">0 KB</span>
                            </p>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:11px">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Kirim Permohonan
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline" style="width:100%;justify-content:center;margin-top:8px">Batal</a>
                </form>
            </div>

        </div>

        {{-- ══ PANEL 2+3: PREVIEW (spans 2 columns) ══ --}}
        <div style="grid-column: 2 / 4">
            {{-- Preview header --}}
            <div class="g-card" style="margin-bottom:12px">
                <div style="padding:10px 18px;display:flex;align-items:center;gap:10px;border-bottom:1px solid #f3f4f6">
                    <div id="live-dot" style="width:8px;height:8px;border-radius:50%;background:#d1d5db"></div>
                    <span style="font-size:.78rem;font-weight:700;color:#374151">Preview Surat</span>
                    <span id="live-label" style="font-size:.7rem;color:#9ca3af;margin-left:4px">— Pilih jenis surat dahulu</span>
                    <div style="margin-left:auto;display:flex;gap:8px">
                        <span style="font-size:.68rem;color:#9ca3af;background:#f3f4f6;padding:3px 8px;border-radius:99px">Preview tidak tersimpan</span>
                    </div>
                </div>
                <div style="padding:10px 18px;background:#fafafa">
                    <p style="font-size:.72rem;color:#6b7280">Data yang diedit di form kiri hanya untuk preview. Pengajuan tetap diverifikasi admin berdasarkan data NIK Anda.</p>
                </div>
            </div>

            {{-- A4 Paper --}}
            <div style="position:relative">
                <div id="preview-paper" style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:48px 56px;box-shadow:0 4px 24px rgba(0,0,0,.09);font-family:'Times New Roman',serif;min-height:600px;position:relative">

                    {{-- Placeholder --}}
                    <div id="preview-placeholder" style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:40px">
                        <svg width="60" height="60" fill="none" stroke="#e5e7eb" stroke-width="1" viewBox="0 0 24 24" style="margin-bottom:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p style="font-weight:700;color:#9ca3af;font-size:.95rem;font-family:'Inter',sans-serif">Preview akan muncul di sini</p>
                        <p style="font-size:.78rem;color:#c0cad7;margin-top:6px;font-family:'Inter',sans-serif">Pilih jenis surat untuk memulai</p>
                    </div>

                    {{-- Letter content --}}
                    <div id="preview-content" style="display:none">

                        {{-- KOP --}}
                        <div style="text-align:center;border-bottom:3px solid #000;padding-bottom:12px;margin-bottom:16px">
                            <p style="font-size:11.5pt;font-weight:bold;letter-spacing:.04em">{{ $kop->baris_1 }}</p>
                            <p style="font-size:11pt;font-weight:bold">{{ $kop->baris_2 }}</p>
                            <p style="font-size:14pt;font-weight:bold;text-transform:uppercase;margin-top:2px">{{ $kop->baris_3 }}</p>
                            <p style="font-size:8pt;color:#333;margin-top:5px">{{ $kop->baris_4 }}</p>
                        </div>

                        {{-- Judul --}}
                        <div style="text-align:center;margin:16px 0 20px">
                            <p id="preview-judul" style="font-size:12pt;font-weight:bold;text-decoration:underline;text-transform:uppercase"></p>
                            <div style="font-size:9pt;color:#555;margin-top:5px">
                                Nomor :&nbsp;
                                <span style="border-bottom:1px solid #666;display:inline-block;min-width:140px">&nbsp;</span>
                            </div>
                        </div>

                        {{-- Pembuka --}}
                        <div id="preview-pembuka" style="font-size:10pt;line-height:1.85;color:#111;margin-bottom:10px;text-align:justify"></div>

                        {{-- Tabel Data Masyarakat (HARDCODED — tidak dipengaruhi template editor) --}}
                        <table id="preview-data-table" style="display:none;width:100%;font-size:10pt;line-height:1.85;margin:10px 0 10px 16px;border-collapse:collapse">
                            <colgroup><col style="width:200px"><col style="width:16px"><col></colgroup>
                            <tbody>
                                <tr>
                                    <td style="padding:3px 0;vertical-align:top">Nama</td>
                                    <td style="padding:3px 6px;vertical-align:top">:</td>
                                    <td id="td-nama" style="padding:3px 0;vertical-align:top;font-weight:bold">-</td>
                                </tr>
                                <tr>
                                    <td style="padding:3px 0;vertical-align:top">NIK</td>
                                    <td style="padding:3px 6px;vertical-align:top">:</td>
                                    <td id="td-nik" style="padding:3px 0;vertical-align:top">-</td>
                                </tr>
                                <tr>
                                    <td style="padding:3px 0;vertical-align:top">Tempat / Tanggal Lahir</td>
                                    <td style="padding:3px 6px;vertical-align:top">:</td>
                                    <td id="td-ttl" style="padding:3px 0;vertical-align:top">-</td>
                                </tr>
                                <tr>
                                    <td style="padding:3px 0;vertical-align:top">Jenis Kelamin</td>
                                    <td style="padding:3px 6px;vertical-align:top">:</td>
                                    <td id="td-jk" style="padding:3px 0;vertical-align:top">-</td>
                                </tr>
                                <tr>
                                    <td style="padding:3px 0;vertical-align:top">Kewarganegaraan</td>
                                    <td style="padding:3px 6px;vertical-align:top">:</td>
                                    <td id="td-kwn" style="padding:3px 0;vertical-align:top">Indonesia</td>
                                </tr>
                                <tr>
                                    <td style="padding:3px 0;vertical-align:top">Agama</td>
                                    <td style="padding:3px 6px;vertical-align:top">:</td>
                                    <td id="td-agama" style="padding:3px 0;vertical-align:top">-</td>
                                </tr>
                                <tr>
                                    <td style="padding:3px 0;vertical-align:top">Status Perkawinan</td>
                                    <td style="padding:3px 6px;vertical-align:top">:</td>
                                    <td id="td-status-kawin" style="padding:3px 0;vertical-align:top">-</td>
                                </tr>
                                <tr>
                                    <td style="padding:3px 0;vertical-align:top">Pekerjaan</td>
                                    <td style="padding:3px 6px;vertical-align:top">:</td>
                                    <td id="td-pekerjaan" style="padding:3px 0;vertical-align:top">-</td>
                                </tr>
                                <tr>
                                    <td style="padding:3px 0;vertical-align:top">Alamat</td>
                                    <td style="padding:3px 6px;vertical-align:top">:</td>
                                    <td id="td-alamat" style="padding:3px 0;vertical-align:top">-</td>
                                </tr>
                            </tbody>
                        </table>

                        {{-- Penutup --}}
                        <div id="preview-penutup" style="font-size:10pt;line-height:1.85;color:#111;margin-top:10px;text-align:justify"></div>

                        {{-- Tanda Tangan --}}
                        <div style="display:flex;justify-content:flex-end">
                            <div style="text-align:center;min-width:200px">
                                <p style="font-size:9.5pt">Karombo, <span id="preview-tanggal"></span></p>
                                <p style="font-size:9.5pt">{{ $ttd->nama }},</p>
                                <div style="height:70px"></div>
                                <p style="font-size:9.5pt;font-weight:bold;border-bottom:1px solid #000;display:inline-block;padding-bottom:1px">{{ $ttd->nama }}</p>
                                @if($ttd->nipd)
                                    <p style="font-size:8.5pt;margin-top:3px">NIP. {{ $ttd->nipd }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Loading --}}
                    <div id="preview-loading" style="display:none;position:absolute;inset:0;align-items:center;justify-content:center;background:rgba(255,255,255,.85);border-radius:12px">
                        <svg style="animation:spin 1s linear infinite;color:#166534" width="36" height="36" fill="none" viewBox="0 0 24 24">
                            <circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path style="opacity:.75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                </div>

                {{-- Not found notice --}}
                <div id="preview-notfound" style="display:none;background:#fef9c3;border:1.5px solid #fde68a;border-radius:10px;padding:11px 16px;margin-top:12px;font-size:.78rem;color:#92400e;font-family:'Inter',sans-serif;display:none;align-items:center;gap:8px">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20" style="flex-shrink:0"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    Template belum tersedia untuk jenis surat ini. Pengajuan tetap bisa dikirim.
                </div>
            </div>
        </div>

    </div>{{-- end 3-col grid --}}
    @endif

    <style>
        @keyframes spin { to { transform: rotate(360deg); } }
        @media (max-width: 1100px) {
            div[style*="grid-template-columns:280px 1fr 1fr"] {
                grid-template-columns: 1fr !important;
            }
            div[style*="grid-column: 2 / 4"] {
                grid-column: 1 !important;
            }
        }
    </style>

    <script>
        const TEMPLATE_URL = '{{ url("/pengajuan/template") }}';
        const CHECK_URL    = '{{ url("/pengajuan/check") }}';
        const TODAY = new Date().toLocaleDateString('id-ID', { day:'numeric', month:'long', year:'numeric' });

        // ── Elements ──
        const selEl     = document.getElementById('jenis-surat-select');
        const panelData = document.getElementById('panel-data');
        const panelSub  = document.getElementById('panel-submit');
        const liveDot   = document.getElementById('live-dot');
        const liveLabel = document.getElementById('live-label');
        const placeholder  = document.getElementById('preview-placeholder');
        const content      = document.getElementById('preview-content');
        const loadingEl    = document.getElementById('preview-loading');
        const notfoundEl   = document.getElementById('preview-notfound');
        const judulEl      = document.getElementById('preview-judul');
        const tanggalEl    = document.getElementById('preview-tanggal');
        const hiddenJenis  = document.getElementById('hidden-jenis');
        const hiddenKep    = document.getElementById('hidden-keperluan');
        const validBanner  = document.getElementById('validation-banner');
        const submitBtn    = document.querySelector('#form-pengajuan button[type="submit"]');

        // Tracks current eligibility state
        let eligibilityValid = true;

        // ── Editable fields ──
        const fNama       = document.getElementById('f-nama');
        const fNik        = document.getElementById('f-nik');
        const fTempat     = document.getElementById('f-tempat');
        const fTgl        = document.getElementById('f-tgl');
        const fGender     = document.getElementById('f-gender');
        // Status Perkawinan — READONLY, diambil dari display div (tidak bisa diubah user)
        const statusKawinVal = document.getElementById('f-status-kawin-display')?.textContent.trim() || '{{ $p->status_perkawinan ?? "Belum Kawin" }}';
        const fAgama      = document.getElementById('f-agama');
        const fPekerjaan  = document.getElementById('f-pekerjaan');
        const fAlamat     = document.getElementById('f-alamat');
        const fKeperluan  = document.getElementById('f-keperluan');

        // Set hidden status kawin dari PHP (server-side, immutable)
        document.getElementById('hidden-status-kawin').value = statusKawinVal;

        let rawPembuka = '';
        let rawPenutup = '';

        function formatDate(dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr);
            if (isNaN(d)) return dateStr;
            return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        }

        function replacePlaceholders(html) {
            if (!html) return '';
            return html
                .replaceAll('{$nama}',             fNama.value || '-')
                .replaceAll('{$nik}',              fNik.value || '-')
                .replaceAll('{$tempat_lahir}',     fTempat.value || '-')
                .replaceAll('{$tanggal_lahir}',    formatDate(fTgl.value))
                .replaceAll('{$jenis_kelamin}',    fGender.value || '-')
                .replaceAll('{$status_perkawinan}',statusKawinVal)
                .replaceAll('{$agama}',            fAgama.value || '-')
                .replaceAll('{$pekerjaan}',        fPekerjaan.value || '-')
                .replaceAll('{$alamat}',           fAlamat.value || '-')
                .replaceAll('{$keperluan}',        fKeperluan.value.trim() || '<em style="color:#9ca3af">[Isi keperluan]</em>');
        }

        function renderPreview() {
            // Update tabel data masyarakat
            document.getElementById('td-nama').textContent         = fNama.value || '-';
            document.getElementById('td-nik').textContent          = fNik.value || '-';
            document.getElementById('td-ttl').textContent          = (fTempat.value || '-') + ' / ' + (formatDate(fTgl.value) || '-');
            document.getElementById('td-jk').textContent           = fGender.value || '-';
            document.getElementById('td-kwn').textContent          = 'Indonesia';
            document.getElementById('td-agama').textContent        = fAgama.value || '-';
            document.getElementById('td-status-kawin').textContent = statusKawinVal; // tetap dari data, tidak berubah
            document.getElementById('td-pekerjaan').textContent    = fPekerjaan.value || '-';
            document.getElementById('td-alamat').textContent       = fAlamat.value || '-';

            // Update pembuka & penutup
            document.getElementById('preview-pembuka').innerHTML = replacePlaceholders(rawPembuka);
            document.getElementById('preview-penutup').innerHTML = replacePlaceholders(rawPenutup);

            // sync hidden
            hiddenKep.value = fKeperluan.value;
        }

        function setLoading(on) {
            loadingEl.style.display = on ? 'flex' : 'none';
        }

        function showPlaceholder() {
            placeholder.style.display = 'flex';
            content.style.display = 'none';
            notfoundEl.style.display = 'none';
            liveDot.style.background = '#d1d5db';
            liveLabel.textContent = '— Pilih jenis surat dahulu';
        }

        function enablePanels(on) {
            panelData.style.opacity = on ? '1' : '.4';
            panelData.style.pointerEvents = on ? 'all' : 'none';
            panelSub.style.opacity = on ? '1' : '.4';
            panelSub.style.pointerEvents = on ? 'all' : 'none';
        }

        async function fetchTemplate(jenisSuratId) {
            setLoading(true);
            try {
                const res  = await fetch(`${TEMPLATE_URL}/${jenisSuratId}`);
                const data = await res.json();
                setLoading(false);

                if (!data.found) {
                    showPlaceholder();
                    notfoundEl.style.display = 'flex';
                    rawPembuka = ''; rawPenutup = '';
                    enablePanels(true);
                    liveLabel.textContent = '— Template belum tersedia';
                    document.getElementById('syarat-container').innerHTML = '—';
                    return;
                }

                placeholder.style.display = 'none';
                content.style.display = 'block';
                notfoundEl.style.display = 'none';

                judulEl.textContent = data.judul_surat || '';
                tanggalEl.textContent = TODAY;

                rawPembuka = data.isi_pembuka || '';
                rawPenutup = data.isi_penutup || '';

                // Tampilkan tabel data jika template punya pembuka/penutup
                document.getElementById('preview-data-table').style.display =
                    (rawPembuka || rawPenutup) ? 'table' : 'none';

                let syaratHtml = '';
                if (data.persyaratan) {
                    const items = data.persyaratan.split('\\n').filter(i => i.trim());
                    if (items.length > 0) {
                        syaratHtml = '<ul style="margin:0;padding-left:16px;list-style-type:disc">';
                        items.forEach(i => syaratHtml += `<li>${i}</li>`);
                        syaratHtml += '</ul>';
                    } else {
                        syaratHtml = 'Tidak ada persyaratan khusus.';
                    }
                } else {
                    syaratHtml = 'Tidak ada persyaratan khusus.';
                }
                document.getElementById('syarat-container').innerHTML = syaratHtml;

                liveDot.style.background = '#22c55e';
                liveLabel.textContent = '— Update otomatis saat Anda mengetik';

                enablePanels(true);
                renderPreview();

            } catch(e) {
                setLoading(false);
                showPlaceholder();
            }
        }

        // ── Eligibility Check (AJAX ke server) ────────────────────────────
        function renderBanner(result) {
            if (!validBanner) return;
            validBanner.innerHTML = '';
            validBanner.style.display = 'none';

            const hasErrors   = result.errors   && result.errors.length > 0;
            const hasWarnings = result.warnings  && result.warnings.length > 0;
            const hasInfo     = result.info       && result.info.length > 0;

            if (!hasErrors && !hasWarnings && !hasInfo) return;

            let html = '';
            if (hasErrors) {
                html += `<div style="background:#fef2f2;border:1.5px solid #fca5a5;padding:14px 16px;">
                    <p style="font-weight:700;color:#b91c1c;margin-bottom:4px">Tidak Memenuhi Syarat</p>
                    <ul style="margin:0;padding-left:16px;color:#dc2626">${result.errors.map(e => `<li style="margin-bottom:4px">${e}</li>`).join('')}</ul>
                    <p style="margin-top:8px;font-size:.72rem;color:#ef4444">Pengajuan tidak dapat dikirim. Hubungi admin desa untuk memperbaiki data.</p>
                    </div>`;
            }
            if (hasWarnings) {
                html += `<div style="background:#fffbeb;border-top:${hasErrors?'0':'1.5px solid #fbbf24'};border-left:1.5px solid #fbbf24;border-right:1.5px solid #fbbf24;border-bottom:1.5px solid #fbbf24;padding:12px 16px;">
                    <p style="font-weight:700;color:#92400e;margin-bottom:4px">Perlu Perhatian</p>
                    <ul style="margin:0;padding-left:16px;color:#b45309">${result.warnings.map(w => `<li style="margin-bottom:4px">${w}</li>`).join('')}</ul></div>`;
            }
            if (hasInfo) {
                html += `<div style="background:#eff6ff;border-top:${(hasErrors||hasWarnings)?'0':'1.5px solid #93c5fd'};border-left:1.5px solid #93c5fd;border-right:1.5px solid #93c5fd;border-bottom:1.5px solid #93c5fd;padding:12px 16px;">
                    <p style="font-weight:700;color:#1e40af;margin-bottom:4px">Informasi</p>
                    <ul style="margin:0;padding-left:16px;color:#1d4ed8">${result.info.map(i => `<li style="margin-bottom:4px">${i}</li>`).join('')}</ul></div>`;
            }

            if (!hasErrors && result.valid) {
                html = `<div style="background:#f0fdf4;border:1.5px solid #86efac;padding:12px 16px;">
                    <p style="color:#15803d;font-weight:600">Data Anda memenuhi syarat untuk surat ini.</p></div>` + html;
            }

            validBanner.innerHTML = html;
            validBanner.style.display = 'block';
            validBanner.style.animation = 'fadeInBanner .3s ease';
        }

        function updateSubmitButton(valid) {
            eligibilityValid = valid;
            if (!submitBtn) return;
            if (!valid) {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '.4';
                submitBtn.style.cursor  = 'not-allowed';
                submitBtn.title = 'Perbaiki data terlebih dahulu.';
            } else {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor  = 'pointer';
                submitBtn.title = '';
            }
        }

        async function fetchEligibility(jenisSuratId) {
            try {
                const res    = await fetch(`${CHECK_URL}/${jenisSuratId}`);
                const result = await res.json();
                renderBanner(result);
                updateSubmitButton(result.valid);
            } catch(e) {
                // Jika gagal network, biarkan user lanjut (jangan blokir)
                updateSubmitButton(true);
            }
        }

        // ── Event listeners ──

        selEl.addEventListener('change', function () {
            hiddenJenis.value = this.value;

            // Reset banner & button state dulu
            if (validBanner) { validBanner.innerHTML = ''; validBanner.style.display = 'none'; }
            updateSubmitButton(true);

            // Logic Kematian
            if (this.value == '4') {
                document.getElementById('label-data-utama').textContent = '(2) Data Almarhum / Orang Yang Meninggal';
                document.getElementById('panel-kematian').style.display = 'block';
            } else {
                document.getElementById('label-data-utama').textContent = '(2) Edit Data (Opsional)';
                document.getElementById('panel-kematian').style.display = 'none';
            }

            if (!this.value) { showPlaceholder(); enablePanels(false); return; }

            // Fetch template DAN eligibility secara paralel
            fetchTemplate(this.value);
            fetchEligibility(this.value);
        });

        // All editable fields → re-render live
        [fNama, fTempat, fTgl, fGender, fAgama, fPekerjaan].forEach(el =>
            el.addEventListener('input', renderPreview)
        );
        fAgama.addEventListener('change',  renderPreview);
        fGender.addEventListener('change', renderPreview);
        // Status Perkawinan: readonly, tidak ada event listener
        fAlamat.addEventListener('input', renderPreview);
        const kepCounter = document.getElementById('kep-counter');
        const kepHint    = document.getElementById('kep-hint');

        function updateKepCounter() {
            const len = fKeperluan.value.trim().length;
            kepCounter.textContent = len + ' karakter';
            if (len > 0 && len < 5) {
                kepCounter.style.color = '#dc2626';
                kepHint.style.display = 'inline';
            } else {
                kepCounter.style.color = len >= 5 ? '#16a34a' : '#9ca3af';
                kepHint.style.display = 'none';
            }
            hiddenKep.value = fKeperluan.value;
            renderPreview();
        }

        fKeperluan.addEventListener('input', updateKepCounter);

        // ══ MULTI-FILE UPLOAD LOGIC ══
        (function() {
            const trigger    = document.getElementById('f-file-syarat-trigger');
            const realInput  = document.getElementById('f-file-syarat');
            const fileList   = document.getElementById('file-list');
            const counter    = document.getElementById('file-counter');
            const countSpan  = document.getElementById('file-count');
            const sizeSpan   = document.getElementById('file-size-total');
            const uploadArea = document.getElementById('upload-area');

            const MAX_FILES  = 5;
            const MAX_BYTES  = 5 * 1024 * 1024; // 5 MB per file
            let selectedFiles = []; // array of File objects

            function formatSize(bytes) {
                if (bytes < 1024) return bytes + ' B';
                if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
                return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
            }

            function iconForType(file) {
                const ext = file.name.split('.').pop().toLowerCase();
                if (ext === 'pdf') {
                    return `<span style="background:#fee2e2;color:#b91c1c;border-radius:6px;padding:2px 7px;font-size:.65rem;font-weight:700">PDF</span>`;
                }
                return `<span style="background:#dbeafe;color:#1d4ed8;border-radius:6px;padding:2px 7px;font-size:.65rem;font-weight:700">IMG</span>`;
            }

            function renderList() {
                fileList.innerHTML = '';
                if (selectedFiles.length === 0) {
                    counter.style.display = 'none';
                    uploadArea.style.borderColor = '#d1d5db';
                    // Kosongkan input
                    const dt = new DataTransfer();
                    realInput.files = dt.files;
                    return;
                }

                uploadArea.style.borderColor = '#15803d';
                counter.style.display = 'block';
                countSpan.textContent = selectedFiles.length;
                const totalSize = selectedFiles.reduce((s, f) => s + f.size, 0);
                sizeSpan.textContent = formatSize(totalSize);

                // Rebuild DataTransfer untuk realInput
                const dt = new DataTransfer();

                selectedFiles.forEach((file, idx) => {
                    dt.items.add(file);
                    const row = document.createElement('div');
                    row.style.cssText = 'display:flex;align-items:center;gap:8px;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:8px;padding:6px 10px;font-size:.72rem;';
                    row.innerHTML = `
                        ${iconForType(file)}
                        <span style="flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#374151" title="${file.name}">${file.name}</span>
                        <span style="color:#6b7280;white-space:nowrap">${formatSize(file.size)}</span>
                        <button type="button" data-idx="${idx}" title="Hapus berkas ini"
                            style="background:#fee2e2;color:#b91c1c;border:none;border-radius:6px;padding:3px 8px;cursor:pointer;font-size:.7rem;font-weight:700;flex-shrink:0">
                            Hapus
                        </button>`;
                    fileList.appendChild(row);
                });

                realInput.files = dt.files;

                // Pasang event hapus
                fileList.querySelectorAll('button[data-idx]').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const i = parseInt(this.dataset.idx);
                        selectedFiles.splice(i, 1);
                        renderList();
                    });
                });
            }

            trigger.addEventListener('change', function() {
                const newFiles = Array.from(this.files);
                let rejected = [];

                newFiles.forEach(file => {
                    if (file.size > MAX_BYTES) {
                        rejected.push(`"${file.name}" terlalu besar (maks. 5 MB).`);
                        return;
                    }
                    // Cegah duplikat (cek nama + ukuran)
                    const isDup = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                    if (isDup) {
                        rejected.push(`"${file.name}" sudah ada dalam daftar.`);
                        return;
                    }
                    if (selectedFiles.length >= MAX_FILES) {
                        rejected.push(`Maksimal ${MAX_FILES} berkas. "${file.name}" tidak ditambahkan.`);
                        return;
                    }
                    selectedFiles.push(file);
                });

                if (rejected.length > 0) {
                    alert('Perhatian:\n' + rejected.join('\n'));
                }

                this.value = ''; // reset trigger agar bisa pilih file sama lagi
                renderList();
            });
        })();

        // Form submit — validate dan sync semua hidden inputs
        document.getElementById('form-pengajuan').addEventListener('submit', function(e) {
            if (!selEl.value) { e.preventDefault(); alert('Pilih jenis surat terlebih dahulu.'); return; }
            if (fKeperluan.value.trim().length < 5) { e.preventDefault(); alert('Keperluan minimal 5 karakter.'); return; }

            // Cek eligibility — blokir jika tidak lolos validasi
            if (!eligibilityValid) {
                e.preventDefault();
                // Scroll ke banner
                if (validBanner) validBanner.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            const fileInput = document.getElementById('f-file-syarat');
            if (!fileInput.files || fileInput.files.length === 0) {
                e.preventDefault();
                alert('Peringatan: Anda wajib melampirkan minimal 1 Dokumen Persyaratan.');
                document.getElementById('upload-area').style.borderColor = '#dc2626';
                document.getElementById('upload-area').scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            // Sync semua hidden inputs dari field yang terlihat
            hiddenJenis.value    = selEl.value;
            hiddenKep.value      = fKeperluan.value;
            document.getElementById('hidden-nama').value         = fNama.value;
            document.getElementById('hidden-tempat').value       = fTempat.value;
            document.getElementById('hidden-tgl').value          = fTgl.value;
            document.getElementById('hidden-gender').value       = fGender.value;
            // Status kawin sudah di-set saat init, tidak perlu diupdate
            document.getElementById('hidden-agama').value        = fAgama.value;
            document.getElementById('hidden-pekerjaan').value    = fPekerjaan.value;
            document.getElementById('hidden-alamat').value       = fAlamat.value;

            if (selEl.value == '4') {
                document.getElementById('h-kem-hari').value = document.getElementById('f-kem-hari').value;
                document.getElementById('h-kem-tgl').value = document.getElementById('f-kem-tgl').value;
                document.getElementById('h-kem-tempat').value = document.getElementById('f-kem-tempat').value;
                document.getElementById('h-kem-sebab').value = document.getElementById('f-kem-sebab').value;
                document.getElementById('h-pel-nama').value = document.getElementById('f-pel-nama').value;
                document.getElementById('h-pel-ktp').value = document.getElementById('f-pel-ktp').value;
                document.getElementById('h-pel-ttl').value = document.getElementById('f-pel-ttl').value;
                document.getElementById('h-pel-kerja').value = document.getElementById('f-pel-kerja').value;
                document.getElementById('h-pel-alamat').value = document.getElementById('f-pel-alamat').value;
                document.getElementById('h-pel-hub').value = document.getElementById('f-pel-hub').value;
            }
        });

        // Init
        if (selEl.value) {
            hiddenJenis.value = selEl.value;
            fetchTemplate(selEl.value);
            fetchEligibility(selEl.value);
        }
    </script>

    <style>
        @keyframes fadeInBanner {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>

</x-app-layout>
