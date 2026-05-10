<x-app-layout>
    <x-slot name="header">Edit Data Warga</x-slot>

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
                    <h3>Edit Data Warga</h3>
                    <p>NIK tidak dapat diubah setelah disimpan</p>
                </div>
                <a href="{{ route('admin.penduduk.index') }}" class="btn btn-yellow btn-sm">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>
            </div>

            <div style="padding:24px">
                <form action="{{ route('admin.penduduk.update', $penduduk->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px">

                        {{-- NIK (readonly) --}}
                        <div>
                            <label class="f-label" for="nik">Nomor Induk Kependudukan (NIK)</label>
                            <input id="nik" name="nik" type="text" class="f-input"
                                   value="{{ old('nik', $penduduk->nik) }}"
                                   readonly>
                            <p style="font-size:.72rem;color:#9ca3af;margin-top:4px">NIK tidak dapat diubah</p>
                        </div>

                        {{-- Nama --}}
                        <div>
                            <label class="f-label" for="nama">Nama Lengkap</label>
                            <input id="nama" name="nama" type="text" class="f-input"
                                   value="{{ old('nama', $penduduk->nama) }}"
                                   placeholder="Nama sesuai KTP"
                                   required>
                        </div>

                        {{-- Tempat Lahir --}}
                        <div>
                            <label class="f-label" for="tempat_lahir">Tempat Lahir</label>
                            <input id="tempat_lahir" name="tempat_lahir" type="text" class="f-input"
                                   value="{{ old('tempat_lahir', $penduduk->tempat_lahir) }}"
                                   placeholder="Kota/Kabupaten tempat lahir"
                                   required>
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label class="f-label" for="tanggal_lahir">Tanggal Lahir</label>
                            <input id="tanggal_lahir" name="tanggal_lahir" type="date" class="f-input"
                                   value="{{ old('tanggal_lahir', $penduduk->tanggal_lahir) }}"
                                   required>
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label class="f-label" for="jenis_kelamin">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="f-input" required>
                                <option value="Laki-laki"  {{ old('jenis_kelamin', $penduduk->jenis_kelamin) == 'Laki-laki'  ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan"  {{ old('jenis_kelamin', $penduduk->jenis_kelamin) == 'Perempuan'  ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        {{-- Status Perkawinan --}}
                        <div>
                            <label class="f-label" for="status_perkawinan">Status Perkawinan</label>
                            <select id="status_perkawinan" name="status_perkawinan" class="f-input" required>
                                @foreach(['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'] as $status)
                                    <option value="{{ $status }}" {{ old('status_perkawinan', $penduduk->status_perkawinan) == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Kewarganegaraan --}}
                        <div>
                            <label class="f-label" for="kewarganegaraan">Kewarganegaraan</label>
                            <select id="kewarganegaraan" name="kewarganegaraan" class="f-input" required>
                                <option value="WNI" {{ old('kewarganegaraan', $penduduk->kewarganegaraan ?? 'WNI') == 'WNI' ? 'selected' : '' }}>WNI (Warga Negara Indonesia)</option>
                                <option value="WNA" {{ old('kewarganegaraan', $penduduk->kewarganegaraan) == 'WNA' ? 'selected' : '' }}>WNA (Warga Negara Asing)</option>
                            </select>
                        </div>

                        {{-- Agama --}}
                        <div>
                            <label class="f-label" for="agama">Agama</label>
                            <select id="agama" name="agama" class="f-input" required>
                                @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $agama)
                                    <option value="{{ $agama }}" {{ old('agama', $penduduk->agama) == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                                @endforeach
                            </select>
                        </div>


                        {{-- Pekerjaan --}}
                        <div>
                            <label class="f-label" for="pekerjaan">Pekerjaan</label>
                            <input id="pekerjaan" name="pekerjaan" type="text" class="f-input"
                                   value="{{ old('pekerjaan', $penduduk->pekerjaan) }}"
                                   placeholder="Contoh: Petani, Pedagang, PNS, dll."
                                   required>
                        </div>

                        {{-- Alamat (full width) --}}
                        <div style="grid-column:1 / -1">
                            <label class="f-label" for="alamat">Alamat Lengkap</label>
                            <textarea id="alamat" name="alamat" rows="3" class="f-input"
                                      placeholder="Jalan, RT/RW, Dusun, Desa Karombo..."
                                      required>{{ old('alamat', $penduduk->alamat) }}</textarea>
                        </div>

                    </div>

                    {{-- Actions --}}
                    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:24px;padding-top:20px;border-top:1px solid #f3f4f6">
                        <a href="{{ route('admin.penduduk.index') }}" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Perbarui Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
