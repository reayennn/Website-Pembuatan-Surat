<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pengajuan Surat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Data Pengajuan / Pemohon</h3>
                    @php
                        $penduduk = $pengajuan->user->penduduk;
                        $dp = $pengajuan->data_pemohon ?? [];
                        $nama         = $dp['nama']              ?? ($penduduk?->nama             ?? 'N/A');
                        $nik          = $dp['nik']               ?? ($penduduk?->nik              ?? 'N/A');
                        $tempatLahir  = $dp['tempat_lahir']      ?? ($penduduk?->tempat_lahir     ?? '-');
                        $tglLahir     = $dp['tanggal_lahir']     ?? ($penduduk?->tanggal_lahir    ?? null);
                        $jk           = $dp['jenis_kelamin']     ?? ($penduduk?->jenis_kelamin    ?? '-');
                        $statusKawin  = $dp['status_perkawinan'] ?? ($penduduk?->status_perkawinan ?? 'Belum Kawin');
                        $kwnAdmin     = $dp['kewarganegaraan']   ?? ($penduduk?->kewarganegaraan  ?? 'WNI');
                        $agama        = $dp['agama']             ?? ($penduduk?->agama            ?? '-');
                        $pekerjaan    = $dp['pekerjaan']         ?? ($penduduk?->pekerjaan        ?? '-');
                        $alamat       = $dp['alamat']            ?? ($penduduk?->alamat           ?? '-');
                        $tglFormatted = $tglLahir ? \Carbon\Carbon::parse($tglLahir)->format('d-m-Y') : '-';
                    @endphp
                    <table style="width:100%;border-collapse:collapse">
                        @foreach([
                            'Nama Lengkap'          => "<strong>$nama</strong>",
                            'NIK'                   => $nik,
                            'Tempat, Tanggal Lahir' => "$tempatLahir, $tglFormatted",
                            'Jenis Kelamin'         => $jk,
                            'Kewarganegaraan'       => $kwnAdmin === 'WNI' ? 'Indonesia (WNI)' : $kwnAdmin,
                            'Agama'                 => $agama,
                            'Status Perkawinan'     => $statusKawin,
                            'Pekerjaan'             => $pekerjaan,
                            'Alamat Lengkap'        => $alamat,
                        ] as $label => $val)
                        <tr style="border-bottom:1px solid #f3f4f6">
                            <td style="padding:10px 0;width:180px;font-size:.85rem;font-weight:600;color:#6b7280">{{ $label }}</td>
                            <td style="padding:10px 0;font-size:.88rem;color:#111827">{!! $val !!}</td>
                        </tr>
                        @endforeach
                    </table>
                    @if($pengajuan->data_pemohon)
                        <p style="font-size:.72rem;color:#9ca3af;margin-top:8px;font-style:italic">* Data sesuai yang diisi oleh pemohon saat pengajuan</p>
                    @endif

                    @if($pengajuan->jenis_surat_id == 4)
                        <div class="mt-4 border-t pt-4">
                            <h4 class="font-medium text-md text-gray-800 mb-2">Data Kematian & Pelapor</h4>
                            <table style="width:100%;border-collapse:collapse">
                                @foreach([
                                    'Hari Meninggal' => $dp['kematian_hari'] ?? '-',
                                    'Tanggal Meninggal' => isset($dp['kematian_tanggal']) ? \Carbon\Carbon::parse($dp['kematian_tanggal'])->translatedFormat('d F Y') : '-',
                                    'Tempat Meninggal' => $dp['kematian_tempat'] ?? '-',
                                    'Penyebab Kematian' => $dp['kematian_penyebab'] ?? '-',
                                    'Nama Pelapor' => $dp['pelapor_nama'] ?? '-',
                                    'NIK Pelapor' => $dp['pelapor_nik'] ?? '-',
                                    'TTL Pelapor' => $dp['pelapor_ttl'] ?? '-',
                                    'Pekerjaan Pelapor' => $dp['pelapor_pekerjaan'] ?? '-',
                                    'Alamat Pelapor' => $dp['pelapor_alamat'] ?? '-',
                                    'Hubungan Pelapor' => $dp['pelapor_hubungan'] ?? '-'
                                ] as $lbl => $v)
                                <tr style="border-bottom:1px solid #f3f4f6">
                                    <td style="padding:6px 0;width:180px;font-size:.85rem;font-weight:600;color:#6b7280">{{ $lbl }}</td>
                                    <td style="padding:6px 0;font-size:.88rem;color:#111827">{{ $v }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Detail Pengajuan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Jenis Surat</p>
                            <p class="mt-1 text-sm text-gray-900 font-bold">{{ $pengajuan->jenisSurat->nama_surat }} ({{ $pengajuan->jenisSurat->kode_surat }})</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tanggal Pengajuan</p>
                            <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d F Y, H:i') }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-500">Keperluan</p>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded border">{{ $pengajuan->keperluan }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-500">File Persyaratan</p>
                            <div class="mt-2">
                                @if($pengajuan->file_persyaratan)
                                    @php
                                        $isZip = str_ends_with(strtolower($pengajuan->file_persyaratan), '.zip');
                                    @endphp

                                    @if($isZip && count($zipEntries) > 0)
                                        {{-- === TAMPILAN MULTI-FILE (dari ZIP) === --}}
                                        <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:10px;padding:14px">
                                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px">
                                                <svg width="16" height="16" fill="none" stroke="#15803d" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                <span style="font-size:.82rem;font-weight:700;color:#15803d">
                                                    {{ count($zipEntries) }} Berkas Persyaratan Diunggah
                                                </span>
                                            </div>

                                            <div style="display:flex;flex-direction:column;gap:8px">
                                                @foreach($zipEntries as $entry)
                                                    @php
                                                        $ext  = strtolower(pathinfo($entry['name'], PATHINFO_EXTENSION));
                                                        $isPdf = $ext === 'pdf';
                                                        $sizeKb = round($entry['size'] / 1024, 1);
                                                    @endphp
                                                    <div style="display:flex;align-items:center;gap:10px;background:#fff;border:1.5px solid #d1fae5;border-radius:8px;padding:10px 14px">
                                                        {{-- Ikon tipe --}}
                                                        @if($isPdf)
                                                            <div style="width:36px;height:36px;background:#fee2e2;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                                                <svg width="18" height="18" fill="none" stroke="#b91c1c" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                            </div>
                                                        @else
                                                            <div style="width:36px;height:36px;background:#dbeafe;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                                                <svg width="18" height="18" fill="none" stroke="#1d4ed8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                            </div>
                                                        @endif

                                                        {{-- Info file --}}
                                                        <div style="flex:1;min-width:0">
                                                            <p style="font-size:.82rem;font-weight:600;color:#111827;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="{{ $entry['name'] }}">
                                                                Berkas {{ $loop->iteration }}: {{ $entry['name'] }}
                                                            </p>
                                                            <p style="font-size:.72rem;color:#6b7280;margin-top:2px">
                                                                {{ $isPdf ? 'PDF' : 'Gambar' }} &bull; {{ $sizeKb }} KB
                                                            </p>
                                                        </div>

                                                        {{-- Tombol aksi --}}
                                                        <div style="display:flex;gap:6px;flex-shrink:0">
                                                            <a href="{{ route('admin.pengajuan_surat.syarat.download', [$pengajuan->id, $entry['index']]) }}"
                                                                target="_blank"
                                                                style="display:inline-flex;align-items:center;gap:5px;background:#15803d;color:#fff;border-radius:7px;padding:6px 12px;font-size:.75rem;font-weight:700;text-decoration:none">
                                                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                                Buka
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            {{-- Tombol download ZIP --}}
                                            <div style="margin-top:10px;padding-top:10px;border-top:1px solid #d1fae5">
                                                <a href="{{ Storage::url($pengajuan->file_persyaratan) }}"
                                                    download
                                                    style="display:inline-flex;align-items:center;gap:6px;font-size:.75rem;color:#6b7280;text-decoration:none">
                                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                    Unduh Semua (ZIP)
                                                </a>
                                            </div>
                                        </div>

                                    @else
                                        {{-- === TAMPILAN FILE TUNGGAL === --}}
                                        @php
                                            $ext = strtolower(pathinfo($pengajuan->file_persyaratan, PATHINFO_EXTENSION));
                                            $isPdf = $ext === 'pdf';
                                        @endphp
                                        <div style="display:inline-flex;align-items:center;gap:10px;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:8px;padding:10px 16px">
                                            @if($isPdf)
                                                <span style="background:#fee2e2;color:#b91c1c;border-radius:6px;padding:3px 9px;font-size:.72rem;font-weight:700">PDF</span>
                                            @else
                                                <span style="background:#dbeafe;color:#1d4ed8;border-radius:6px;padding:3px 9px;font-size:.72rem;font-weight:700">IMG</span>
                                            @endif
                                            <span style="font-size:.82rem;color:#374151;font-weight:600">1 Berkas Persyaratan</span>
                                            <a href="{{ Storage::url($pengajuan->file_persyaratan) }}" target="_blank"
                                                style="display:inline-flex;align-items:center;gap:5px;background:#15803d;color:#fff;border-radius:7px;padding:6px 12px;font-size:.75rem;font-weight:700;text-decoration:none">
                                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                Buka / Lihat
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-sm text-gray-500 italic">Tidak ada file persyaratan yang dilampirkan.</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Status Saat Ini</p>
                            <p class="mt-1">
                                @if($pengajuan->status == 'Menunggu')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Proses</span>
                                @elseif($pengajuan->status == 'Menunggu Kades')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Menunggu TTD Kades</span>
                                @elseif($pengajuan->status == 'Disetujui')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai / Disetujui</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($pengajuan->status == 'Menunggu')
                        <form action="{{ route('admin.pengajuan_surat.verify', $pengajuan->id) }}" method="POST" class="mt-4 border-t pt-4">
                            @csrf
                            @method('PUT')
                            <h4 class="font-medium text-md mb-4 text-gray-800">Verifikasi Pengajuan</h4>
                            
                            <div class="mb-4">
                                <x-input-label for="status" :value="__('Aksi Verifikasi')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required onchange="toggleKeterangan()">
                                    <option value="">-- Pilih Aksi --</option>
                                    <option value="Menunggu Kades">Data Benar, Teruskan ke Kepala Desa</option>
                                    <option value="Perbaikan">Data Tidak Lengkap, Kembalikan untuk Diperbaiki</option>
                                    <option value="Ditolak">Tolak Pengajuan (Fatal)</option>
                                </select>
                            </div>

                            <div class="mb-4 hidden" id="keterangan_container">
                                <x-input-label for="keterangan" :value="__('Keterangan / Alasan Penolakan')" />
                                <textarea id="keterangan" name="keterangan" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                                <p class="text-xs text-gray-500 mt-1" id="keterangan_helper_text">Wajib diisi sebagai catatan bagi warga.</p>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <a href="{{ route('admin.pengajuan_surat.index') }}" class="mr-3 text-gray-600 hover:text-gray-900 transition">Kembali</a>
                                <x-primary-button onclick="return confirm('Apakah Anda yakin dengan keputusan ini? Tindakan ini tidak dapat dibatalkan.')">
                                    {{ __('Proses Verifikasi') }}
                                </x-primary-button>
                            </div>
                        </form>

                        <script>
                            function toggleKeterangan() {
                                var status = document.getElementById('status').value;
                                var container = document.getElementById('keterangan_container');
                                var textarea = document.getElementById('keterangan');

                                if (status === 'Ditolak' || status === 'Perbaikan') {
                                    container.classList.remove('hidden');
                                    textarea.setAttribute('required', 'required');
                                } else {
                                    container.classList.add('hidden');
                                    textarea.removeAttribute('required');
                                    textarea.value = '';
                                }
                            }
                        </script>
                    @else
                        <div class="mt-6 border-t pt-4">
                            <h4 class="font-medium text-md mb-2 text-gray-800">Hasil Verifikasi</h4>
                            
                            @if($pengajuan->status == 'Menunggu Kades')
                                <div class="bg-blue-50 p-4 rounded-md border border-blue-200">
                                    <p class="text-blue-800 font-semibold mb-2">Menunggu Persetujuan Kepala Desa</p>
                                    <p class="text-sm text-gray-700">Surat telah divalidasi dan diteruskan ke Kepala Desa untuk penandatanganan.</p>
                                </div>
                            @elseif($pengajuan->status == 'Disetujui' && $pengajuan->surat)
                                <div class="bg-green-50 p-4 rounded-md border border-green-200">
                                    <p class="text-green-800 font-semibold mb-2">Surat telah berhasil diterbitkan dan ditandatangani.</p>
                                    <p class="text-sm text-gray-700">Nomor Surat: <strong>{{ $pengajuan->surat->nomor_surat }}</strong></p>
                                    <div class="mt-4">
                                        <a href="{{ asset('storage/' . $pengajuan->surat->file_pdf) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                            Unduh/Lihat PDF
                                        </a>
                                    </div>
                                </div>
                            @elseif($pengajuan->status == 'Ditolak')
                                <div class="bg-red-50 p-4 rounded-md border border-red-200">
                                    <p class="text-red-800 font-semibold mb-2">Permohonan ditolak.</p>
                                    <p class="text-sm text-gray-700"><strong>Alasan:</strong> {{ $pengajuan->keterangan ?? 'Tidak ada keterangan.' }}</p>
                                </div>
                            @endif
                            
                            <div class="mt-6 text-right">
                                <a href="{{ route('admin.pengajuan_surat.index') }}" class="text-indigo-600 hover:text-indigo-900 transition">Kembali ke Daftar</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
