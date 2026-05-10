<x-app-layout>
    <x-slot name="header">Kelola Pengguna</x-slot>

    @if(session('success'))
        <div class="alert-success">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert-error">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert-error">
            <ul style="list-style:disc;list-style-position:inside">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Info box --}}
    <div style="background:#fffbeb;border:1.5px solid #fde68a;border-radius:12px;padding:14px 18px;margin-bottom:20px;display:flex;gap:12px;align-items:flex-start">
        <svg width="18" height="18" fill="none" stroke="#d97706" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div style="font-size:.82rem;color:#92400e;line-height:1.5">
            <strong>Cara menautkan NIK:</strong> Isi kolom <strong>NIK</strong> (16 digit) dan <strong>Nama</strong> warga, lalu klik <strong>Tautkan</strong>. Jika NIK belum pernah didaftarkan, data warga akan dibuat otomatis.
        </div>
    </div>

    <div class="g-card" style="box-shadow:0 1px 4px rgba(0,0,0,.07)">
        <div class="g-card-header">
            <div>
                <h3>Daftar Akun Masyarakat</h3>
                <p>Tautkan akun pengguna dengan NIK warga</p>
            </div>
        </div>
        <div style="overflow-x:auto">
            <table class="g-table">
                <thead>
                    <tr>
                        <th style="width:40px">No</th>
                        <th>Nama Akun</th>
                        <th>Email</th>
                        <th>Status NIK</th>
                        <th style="width:360px">Tautkan NIK</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $user)
                        <tr>
                            <td style="color:#9ca3af;font-weight:600">{{ $users->firstItem() + $i }}</td>
                            <td style="font-weight:600;color:#111827">{{ $user->name }}</td>
                            <td style="color:#6b7280;font-size:.83rem">{{ $user->email }}</td>
                            <td>
                                @if($user->penduduk)
                                    <div>
                                        <span class="badge-green"><span class="badge-dot" style="background:#16a34a"></span>Tertaut</span>
                                        <p style="font-size:.75rem;color:#374151;margin-top:4px;font-weight:600">{{ $user->penduduk->nama }}</p>
                                        <p style="font-size:.70rem;color:#9ca3af">NIK: {{ $user->penduduk->nik }}</p>
                                    </div>
                                @else
                                    <span class="badge-red"><span class="badge-dot" style="background:#dc2626"></span>Belum Tertaut</span>
                                @endif
                            </td>
                            <td>
                                @if(!$user->penduduk)
                                    <form action="{{ route('admin.users.link', $user->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <div style="display:flex;flex-direction:column;gap:6px">
                                            <input type="text" name="nik" class="f-input"
                                                   placeholder="NIK (16 digit)" maxlength="16"
                                                   value="{{ old('nik') }}"
                                                   style="padding:7px 10px;font-size:.8rem;font-family:monospace"
                                                   required>
                                            <input type="text" name="nama" class="f-input"
                                                   placeholder="Nama lengkap warga"
                                                   value="{{ old('nama') }}"
                                                   style="padding:7px 10px;font-size:.8rem"
                                                   required>
                                            <button type="submit" class="btn btn-sm btn-primary" style="width:100%;justify-content:center;padding:8px">
                                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                                Tautkan
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    <div style="display:flex;gap:6px;align-items:center">
                                        <span style="font-size:.75rem;color:#9ca3af;flex:1">Sudah ditautkan</span>
                                        <form action="{{ route('admin.users.link', $user->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="nik" value="{{ $user->penduduk->nik }}">
                                            <input type="hidden" name="nama" value="{{ $user->penduduk->nama }}">
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:60px 0;color:#9ca3af">
                                <p style="font-weight:600;color:#6b7280">Belum ada akun pengguna masyarakat</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div style="padding:14px 18px;border-top:1px solid #f3f4f6">{{ $users->links() }}</div>
        @endif
    </div>
</x-app-layout>
