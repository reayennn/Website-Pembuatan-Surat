<x-app-layout>
    {{-- Header Content Area --}}
    <div style="background: linear-gradient(135deg, #166534 0%, #15803d 100%); padding: 40px 30px 60px 30px; border-radius: 0 0 20px 20px; color: white; margin-bottom: -40px;">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <h2 class="text-2xl font-semibold tracking-wide" style="font-family: 'Poppins', sans-serif;">Pengajuan Surat</h2>
            </div>
            <a href="{{ route('kades.persetujuan.index') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Semua Data
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pb-12 mt-6">
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded mb-6 flex justify-between items-center shadow-md">
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-sm">Error!</span>
                    <span class="text-sm shadow-sm">{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.style.display='none'" class="text-red-700 hover:text-red-900">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-6">
            {{-- Left Column: Data Surat & Data Pengajuan --}}
            <div class="lg:w-2/3 flex flex-col gap-6">
                
                {{-- Data Surat Card --}}
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-green-800 font-semibold text-lg">Data Surat</h3>
                    </div>
                    <div class="p-6">
                        <table class="w-full text-sm text-left">
                            <tbody class="divide-y divide-gray-100">
                                <tr>
                                    <td class="py-3 font-semibold text-gray-700 w-1/3">Nama Surat</td>
                                    <td class="py-3 text-gray-900 uppercase font-bold text-green-700">{{ $pengajuan->jenisSurat->nama_surat }}</td>
                                </tr>
                                <tr>
                                    <td class="py-3 font-semibold text-gray-700">Keterangan Surat</td>
                                    <td class="py-3 text-gray-600">Surat yang berisi pengantar atau keterangan yang dibutuhkan masyarakat untuk mengajukan pelayanan</td>
                                </tr>
                                <tr>
                                    <td class="py-3 font-semibold text-gray-700 align-top">Keperluan</td>
                                    <td class="py-3 text-gray-800">{{ $pengajuan->keperluan }}</td>
                                </tr>
                                <tr>
                                    <td class="py-3 font-semibold text-gray-700">Tanggal Pengajuan</td>
                                    <td class="py-3 text-gray-800">{{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('d F Y, H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="py-3 font-semibold text-gray-700">Status</td>
                                    <td class="py-3">
                                        @if($pengajuan->status == 'Menunggu Kades')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-yellow-100 text-yellow-800 border border-yellow-200">Menunggu TTD Anda</span>
                                        @elseif($pengajuan->status == 'Disetujui')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-green-100 text-green-800 border border-green-200">Disetujui</span>
                                        @else
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-red-100 text-red-800 border border-red-200">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Berkas Persyaratan Card --}}
                @if($pengajuan->file_persyaratan)
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50" style="background:linear-gradient(to right,#f0fdf4,#f9fafb)">
                        <h3 class="text-green-800 font-semibold text-lg">Berkas Persyaratan</h3>
                    </div>
                    <div class="p-6">
                        @php $isZip = str_ends_with(strtolower($pengajuan->file_persyaratan), '.zip'); @endphp

                        @if($isZip && count($zipEntries) > 0)
                            <p style="font-size:.78rem;color:#6b7280;margin-bottom:12px">
                                <strong style="color:#15803d">{{ count($zipEntries) }}</strong> berkas persyaratan diunggah oleh pemohon.
                            </p>
                            <div style="display:flex;flex-direction:column;gap:8px">
                                @foreach($zipEntries as $entry)
                                    @php
                                        $ext   = strtolower(pathinfo($entry['name'], PATHINFO_EXTENSION));
                                        $isPdf = $ext === 'pdf';
                                        $sizeKb = round($entry['size'] / 1024, 1);
                                    @endphp
                                    <div style="display:flex;align-items:center;gap:10px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:10px 14px">
                                        @if($isPdf)
                                            <div style="width:36px;height:36px;background:#fee2e2;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                                <svg width="18" height="18" fill="none" stroke="#b91c1c" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                            </div>
                                        @else
                                            <div style="width:36px;height:36px;background:#dbeafe;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                                <svg width="18" height="18" fill="none" stroke="#1d4ed8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                        <div style="flex:1;min-width:0">
                                            <p style="font-size:.82rem;font-weight:600;color:#1e293b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="{{ $entry['name'] }}">
                                                Berkas {{ $loop->iteration }}: {{ $entry['name'] }}
                                            </p>
                                            <p style="font-size:.72rem;color:#64748b;margin-top:2px">
                                                {{ $isPdf ? 'PDF' : 'Gambar' }} &bull; {{ $sizeKb }} KB
                                            </p>
                                        </div>
                                        <a href="{{ route('kades.persetujuan.syarat.download', [$pengajuan->id, $entry['index']]) }}"
                                            target="_blank"
                                            style="display:inline-flex;align-items:center;gap:5px;background:#15803d;color:#fff;border-radius:7px;padding:6px 12px;font-size:.75rem;font-weight:700;text-decoration:none;flex-shrink:0">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Buka
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            <div style="margin-top:10px;padding-top:10px;border-top:1px solid #e2e8f0">
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($pengajuan->file_persyaratan) }}" download
                                    style="font-size:.75rem;color:#64748b;text-decoration:none;display:inline-flex;align-items:center;gap:5px">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Unduh Semua (ZIP)
                                </a>
                            </div>
                        @else
                            @php $ext = strtolower(pathinfo($pengajuan->file_persyaratan, PATHINFO_EXTENSION)); @endphp
                            <div style="display:inline-flex;align-items:center;gap:10px;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:8px;padding:10px 16px">
                                @if($ext === 'pdf')
                                    <span style="background:#fee2e2;color:#b91c1c;border-radius:6px;padding:3px 9px;font-size:.72rem;font-weight:700">PDF</span>
                                @else
                                    <span style="background:#dbeafe;color:#1d4ed8;border-radius:6px;padding:3px 9px;font-size:.72rem;font-weight:700">IMG</span>
                                @endif
                                <span style="font-size:.82rem;color:#374151;font-weight:600">1 Berkas Persyaratan</span>
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($pengajuan->file_persyaratan) }}" target="_blank"
                                    style="display:inline-flex;align-items:center;gap:5px;background:#15803d;color:#fff;border-radius:7px;padding:6px 12px;font-size:.75rem;font-weight:700;text-decoration:none">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Buka / Lihat
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Data Pengajuan Card (Pemohon Profile) --}}
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-green-800 font-semibold text-lg">Data Pengajuan / Pemohon</h3>
                    </div>
                    <div class="p-6">
                        @php
                            $penduduk = $pengajuan->user->penduduk;
                            $dp = $pengajuan->data_pemohon ?? [];
                            $rows = [
                                'Nama Lengkap'          => '<strong>' . ($dp['nama'] ?? ($penduduk?->nama ?? $pengajuan->user->name)) . '</strong>',
                                'NIK'                   => $dp['nik']               ?? ($penduduk?->nik              ?? '-'),
                                'Tempat, Tanggal Lahir' => ($dp['tempat_lahir'] ?? ($penduduk?->tempat_lahir ?? '-')) . ', ' . (isset($dp['tanggal_lahir']) ? \Carbon\Carbon::parse($dp['tanggal_lahir'])->format('d-m-Y') : ($penduduk?->tanggal_lahir ? \Carbon\Carbon::parse($penduduk->tanggal_lahir)->format('d-m-Y') : '-')),
                                'Jenis Kelamin'         => $dp['jenis_kelamin']     ?? ($penduduk?->jenis_kelamin    ?? '-'),
                                'Kewarganegaraan'       => ($dp['kewarganegaraan']  ?? ($penduduk?->kewarganegaraan  ?? 'WNI')) === 'WNI' ? 'Indonesia (WNI)' : ($dp['kewarganegaraan'] ?? '-'),
                                'Agama'                 => $dp['agama']             ?? ($penduduk?->agama            ?? '-'),
                                'Status Perkawinan'     => $dp['status_perkawinan'] ?? ($penduduk?->status_perkawinan ?? 'Belum Kawin'),
                                'Pekerjaan'             => $dp['pekerjaan']         ?? ($penduduk?->pekerjaan        ?? '-'),
                                'Alamat Lengkap'        => $dp['alamat']            ?? ($penduduk?->alamat           ?? '-'),
                            ];
                        @endphp
                        <table class="w-full text-sm text-left">
                            <tbody class="divide-y divide-gray-100">
                                @foreach($rows as $label => $val)
                                <tr>
                                    <td class="py-3 font-semibold text-gray-700 w-1/3">{{ $label }}</td>
                                    <td class="py-3 text-gray-900">{!! $val !!}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($pengajuan->data_pemohon)
                            <p class="text-xs text-gray-400 mt-3 italic">* Data sesuai yang diisi pemohon saat pengajuan</p>
                        @endif

                        @if($pengajuan->jenis_surat_id == 4)
                            <div class="mt-4 border-t border-gray-100 pt-4">
                                <h4 class="font-semibold text-gray-700 mb-2">Data Kematian & Pelapor</h4>
                                <table class="w-full text-sm text-left">
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach([
                                            'Hari Meninggal' => $dp['kematian_hari'] ?? '-',
                                            'Tanggal Meninggal' => isset($dp['kematian_tanggal']) ? \Carbon\Carbon::parse($dp['kematian_tanggal'])->translatedFormat('d F Y') : '-',
                                            'Tempat Meninggal' => $dp['kematian_tempat'] ?? '-',
                                            'Penyebab' => $dp['kematian_penyebab'] ?? '-',
                                            'Nama Pelapor' => $dp['pelapor_nama'] ?? '-',
                                            'NIK Pelapor' => $dp['pelapor_nik'] ?? '-',
                                            'TTL Pelapor' => $dp['pelapor_ttl'] ?? '-',
                                            'Pekerjaan Pelapor' => $dp['pelapor_pekerjaan'] ?? '-',
                                            'Alamat Pelapor' => $dp['pelapor_alamat'] ?? '-',
                                            'Hubungan Pelapor' => $dp['pelapor_hubungan'] ?? '-'
                                        ] as $lbl => $v)
                                        <tr>
                                            <td class="py-2 text-gray-600 font-medium w-1/3">{{ $lbl }}</td>
                                            <td class="py-2 text-gray-900">{{ $v }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Right Column: Approval Action --}}
            <div class="lg:w-1/3">
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden sticky top-6">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-green-800 font-semibold text-lg">Perstujuan Surat</h3>
                    </div>
                    
                    <div class="p-6">
                        @if($pengajuan->status == 'Menunggu Kades')
                            <p class="text-sm text-gray-600 mb-6">
                                Silakan tinjau data pemohon di samping. Jika sudah sesuai, Anda dapat menyetujui dan menandatangani surat ini secara digital.
                            </p>

                            <div class="flex flex-col gap-3">
                                <!-- Tombol Setuju -->
                                <form action="{{ route('kades.persetujuan.setuju', $pengajuan->id) }}" method="POST" class="w-full">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin menyetujui dan menandatangani surat ini?')" class="w-full text-white font-semibold py-3 px-4 rounded-lg flex items-center justify-center gap-2 transition" style="background:#16a34a;box-shadow:0 4px 6px -1px rgba(22,163,74,.4),0 2px 4px -1px rgba(22,163,74,.2)" onmouseover="this.style.background='#15803d'" onmouseout="this.style.background='#16a34a'">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Setujui dan Tinjau
                                    </button>
                                </form>

                                <!-- Tombol Tolak -->
                                <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="w-full text-white font-semibold py-3 px-4 rounded-lg flex items-center justify-center gap-2 transition mt-1" style="background:#f59e0b;box-shadow:0 1px 3px 0 rgba(0,0,0,0.1),0 1px 2px 0 rgba(0,0,0,0.06)" onmouseover="this.style.background='#d97706'" onmouseout="this.style.background='#f59e0b'">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Tolak Permohonan
                                </button>
                            </div>

                        @elseif($pengajuan->status == 'Disetujui' && $pengajuan->surat)
                            <div style="text-align:center;padding:16px 0">
                                <div style="width:64px;height:64px;background:#dcfce7;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                                    <svg width="32" height="32" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <h4 style="font-weight:800;color:#111827;font-size:1.1rem;margin-bottom:4px">Telah Disetujui</h4>
                                <p style="font-size:.8rem;color:#6b7280;margin-bottom:16px">Nomor: {{ $pengajuan->surat->nomor_surat }}</p>

                                {{-- Tombol PDF yang jelas --}}
                                <a href="{{ asset('storage/' . $pengajuan->surat->file_pdf) }}" target="_blank"
                                   style="display:flex;align-items:center;justify-content:center;gap:10px;width:100%;background:#15803d;color:#fff;font-weight:700;font-size:.9rem;padding:13px 16px;border-radius:10px;text-decoration:none;box-shadow:0 4px 12px rgba(21,128,61,.35);transition:background .2s"
                                   onmouseover="this.style.background='#166534'" onmouseout="this.style.background='#15803d'">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v6h6"/>
                                    </svg>
                                    Lihat / Unduh PDF
                                </a>
                            </div>
                        @elseif($pengajuan->status == 'Ditolak')
                            <div class="text-center py-4">
                                <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </div>
                                <h4 class="font-bold text-gray-900 text-lg mb-1">Permohonan Ditolak</h4>
                                <div class="bg-gray-50 border border-gray-200 rounded p-3 mt-4 text-left">
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Alasan Penolakan:</p>
                                    <p class="text-sm text-gray-800">{{ $pengajuan->keterangan ?? 'Tidak ada keterangan.' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Penolakan -->
    @if($pengajuan->status == 'Menunggu Kades')
    <div id="rejectModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full z-50 flex items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="relative w-full max-w-md p-6 border shadow-2xl rounded-xl bg-white m-4 min-h-[auto]">
            <form action="{{ route('kades.persetujuan.tolak', $pengajuan->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mt-1">
                    <div class="flex items-center gap-3 mb-4 border-b pb-4">
                        <div class="bg-red-100 p-2 rounded-full text-red-600">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <h3 class="text-xl leading-6 font-bold text-gray-900">Tolak Permohonan</h3>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-4">Harap sertakan alasan mengapa surat ini ditolak agar pemohon mengetahui kekurangannya.</p>
                    <textarea name="keterangan" rows="4" class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm p-3 shadow-sm" required placeholder="Tuliskan keterangan (contoh: Persyaratan KTP tidak valid)..."></textarea>
                    
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="bg-white text-gray-700 border border-gray-300 px-5 py-2.5 rounded-lg hover:bg-gray-50 font-medium transition">Batal</button>
                        <button type="submit" class="bg-red-600 text-white px-5 py-2.5 rounded-lg hover:bg-red-700 font-bold transition shadow-md">Kirim Penolakan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

</x-app-layout>
