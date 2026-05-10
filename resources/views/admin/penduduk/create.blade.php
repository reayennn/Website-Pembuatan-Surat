<x-app-layout>
    <x-slot name="header">Tambah Data Warga</x-slot>

    <div>

        {{-- Errors --}}
        @if($errors->any())
            <div class="alert-error" style="margin-bottom:20px">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <ul style="margin:0;padding-left:4px">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="g-card">
            <div class="g-card-header">
                <div>
                    <h3>Form Tambah Data Warga</h3>
                    <p>Isi data warga dengan lengkap dan benar</p>
                </div>
                <a href="{{ route('admin.penduduk.index') }}" class="btn btn-yellow btn-sm">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>
            </div>

            <div style="padding:24px">
                <form action="{{ route('admin.penduduk.store') }}" method="POST">
                    @csrf

                    {{-- Info banner --}}
                    <div style="background:#f0fdf4;border:1.5px solid #86efac;border-radius:10px;padding:12px 16px;margin-bottom:24px;display:flex;align-items:flex-start;gap:10px;font-size:.82rem;color:#166534">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" style="flex-shrink:0;margin-top:1px"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                        Setelah data berhasil disimpan, warga terdaftar dapat login ke Portal Masyarakat menggunakan <strong>&nbsp;NIK&nbsp;</strong> dan <strong>&nbsp;Tanggal Lahir</strong>.
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px">

                        {{-- NIK --}}
                        <div>
                            <label class="f-label" for="nik">Nomor Induk Kependudukan (NIK)</label>
                            <input id="nik" name="nik" type="text" class="f-input"
                                   value="{{ old('nik') }}"
                                   placeholder="16 digit NIK"
                                   maxlength="16" required autofocus>
                        </div>

                        {{-- Nama --}}
                        <div>
                            <label class="f-label" for="nama">Nama Lengkap</label>
                            <input id="nama" name="nama" type="text" class="f-input"
                                   value="{{ old('nama') }}"
                                   placeholder="Nama sesuai KTP"
                                   required>
                        </div>

                        {{-- Tempat Lahir --}}
                        <div>
                            <label class="f-label" for="tempat_lahir">Tempat Lahir</label>
                            <input id="tempat_lahir" name="tempat_lahir" type="text" class="f-input"
                                   value="{{ old('tempat_lahir') }}"
                                   placeholder="Kota/Kabupaten tempat lahir"
                                   required>
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label class="f-label" for="tanggal_lahir">Tanggal Lahir</label>
                            <input id="tanggal_lahir" name="tanggal_lahir" type="date" class="f-input"
                                   value="{{ old('tanggal_lahir') }}"
                                   required>
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label class="f-label" for="jenis_kelamin">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="f-input" required>
                                <option value="">— Pilih Jenis Kelamin —</option>
                                <option value="Laki-laki"  {{ old('jenis_kelamin') == 'Laki-laki'  ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan"  {{ old('jenis_kelamin') == 'Perempuan'  ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        {{-- Status Perkawinan --}}
                        <div>
                            <label class="f-label" for="status_perkawinan">Status Perkawinan</label>
                            <select id="status_perkawinan" name="status_perkawinan" class="f-input" required>
                                <option value="">— Pilih Status —</option>
                                @foreach(['Belum Kawin','Kawin','Cerai'] as $status)
                                    <option value="{{ $status }}" {{ old('status_perkawinan') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Kewarganegaraan --}}
                        <div>
                            <label class="f-label" for="kewarganegaraan">Kewarganegaraan</label>
                            <select id="kewarganegaraan" name="kewarganegaraan" class="f-input" required>
                                <option value="WNI" {{ old('kewarganegaraan', 'WNI') == 'WNI' ? 'selected' : '' }}>WNI (Warga Negara Indonesia)</option>
                                <option value="WNA" {{ old('kewarganegaraan') == 'WNA' ? 'selected' : '' }}>WNA (Warga Negara Asing)</option>
                            </select>
                        </div>

                        {{-- Agama --}}
                        <div>
                            <label class="f-label" for="agama">Agama</label>
                            <select id="agama" name="agama" class="f-input" required>
                                <option value="">— Pilih Agama —</option>
                                @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $agama)
                                    <option value="{{ $agama }}" {{ old('agama') == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Pekerjaan --}}
                        <div>
                            <label class="f-label" for="pekerjaan">Pekerjaan</label>
                            <input id="pekerjaan" name="pekerjaan" type="text" class="f-input"
                                   value="{{ old('pekerjaan') }}"
                                   placeholder="Contoh: Petani, Pedagang, PNS, dll."
                                   required>
                        </div>

                        {{-- Alamat (full width) --}}
                        <div style="grid-column:1 / -1">
                            <label class="f-label" for="alamat">Alamat Lengkap</label>
                            <textarea id="alamat" name="alamat" rows="3" class="f-input"
                                      placeholder="Contoh:Dusun Karombo..."
                                      required>{{ old('alamat') }}</textarea>
                        </div>

                    </div>

                    {{-- Actions --}}
                    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:24px;padding-top:20px;border-top:1px solid #f3f4f6">
                        <a href="{{ route('admin.penduduk.index') }}" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
