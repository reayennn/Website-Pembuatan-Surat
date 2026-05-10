<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Informasi Layanan Surat Desa Karombo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-green-800 text-white shadow-lg">
        <div class="w-full px-6 lg:px-12">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <div class="bg-yellow-400 rounded-full p-1.5">
                        <svg class="w-6 h-6 text-green-800" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/></svg>
                    </div>
                    <div>
                        <span class="font-bold text-sm leading-tight block" style="margin-top: 4px;">Desa Karombo</span>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="bg-yellow-400 text-green-900 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-yellow-300 transition">Dashboard Admin</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="bg-yellow-400 text-green-900 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-yellow-300 transition">Dashboard Saya</a>
                        @endif
                    @else
                        <a href="{{ route('login.masyarakat') }}" class="bg-yellow-400 text-green-900 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-yellow-300 transition">Login Warga</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-green-800 via-green-700 to-green-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-yellow-400 rounded-full mb-6 shadow-lg">
                <svg class="w-10 h-10 text-green-800" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
            </div>
            <h1 class="text-4xl font-bold mb-4 leading-tight">Sistem Informasi Layanan Surat</h1>
            <h2 class="text-2xl font-semibold text-yellow-300 mb-6">Desa Karombo</h2>
            <p class="text-green-100 text-lg max-w-2xl mx-auto mb-10">
                Platform digital pelayanan pembuatan surat untuk masyarakat Desa Karombo. Ajukan permohonan surat secara online, mudah, cepat, dan transparan.
            </p>
            @guest
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('login.masyarakat') }}" class="bg-yellow-400 text-green-900 px-8 py-3 rounded-lg font-semibold text-lg hover:bg-yellow-300 transition shadow-lg">
                        Login Warga
                    </a>
                </div>
            @endguest
        </div>
    </div>

    <!-- Layanan Cards -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-2xl font-bold text-center text-gray-800 mb-3">Jenis Layanan Surat</h3>
            <p class="text-gray-500 text-center mb-10">Kami menyediakan berbagai jenis surat keterangan resmi dari Desa Karombo</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $layanan = [
                        ['kode' => 'SKD',     'nama' => 'Surat Keterangan Domisili',             'desc' => 'Keterangan resmi bahwa warga benar-benar berdomisili di wilayah Desa Karombo.'],
                        ['kode' => 'SKP',     'nama' => 'Surat Ket Pindah/Datang (WNI)',         'desc' => 'Pengantar pindah atau datang kependudukan masyarakat.'],
                        ['kode' => 'SKL',     'nama' => 'Surat Keterangan Kelahiran',            'desc' => 'Keterangan resmi kelahiran warga sebagai pengantar penerbitan Akta Lahir.'],
                        ['kode' => 'SKM',     'nama' => 'Surat Keterangan Kematian',             'desc' => 'Keterangan administrasi warga yang telah meninggal dunia.'],
                        ['kode' => 'SKBK',    'nama' => 'Surat Keterangan Belum Menikah',        'desc' => 'Keterangan bahwa warga belum pernah kawin atau masih lajang.'],
                        ['kode' => 'SKBN',    'nama' => 'Surat Keterangan Beda Identitas',       'desc' => 'Keterangan mengenai kesalahan/perbedaan penulisan nama di dokumen resmi.'],
                        ['kode' => 'SKU',     'nama' => 'Surat Keterangan Usaha',                'desc' => 'Keterangan kepemilikan usaha atau bisnis mandiri.'],
                        ['kode' => 'SKTM',    'nama' => 'Surat Keterangan Tidak Mampu',          'desc' => 'Dokumen keterangan status tidak mampu untuk keperluan bantuan / jaminan sosial.'],
                        ['kode' => 'SKDU',    'nama' => 'Surat Keterangan Domisili Usaha',       'desc' => 'Keterangan posisi domisili atau keberadaan lokasi usaha / perusahaan.'],
                        ['kode' => 'SP-SKCK', 'nama' => 'Surat Pengantar SKCK',                  'desc' => 'Pengantar pembuatan/perpanjangan Surat Keterangan Catatan Kepolisian.'],
                        ['kode' => 'SP-N1',   'nama' => 'Surat Pengantar Nikah',           'desc' => 'Keterangan pengantar pendaftaran pernikahan ke instansi KUA.'],
                        ['kode' => 'SP-KTP',  'nama' => 'Surat Pengantar KTP/KK',                'desc' => 'Pengantar permohonan penerbitan Dokumen Kartu Keluar dan e-KTP.'],
                        ['kode' => 'SK-ISTRI','nama' => 'Surat Keterangan Istri Tidak Di Tempat','desc' => 'Keterangan ketidakhadiran istri karena menjadi tenaga kerja / ke luar daerah.'],
                        ['kode' => 'SK-NKK',  'nama' => 'Surat Tidak Memiliki KK',               'desc' => 'Keterangan bahwa yang bersangkutan belum terdaftar/proses pembuatan baru.'],
                        ['kode' => 'SP-IZIN', 'nama' => 'Surat Pengantar Izin Keramaian',        'desc' => 'Surat rekomendasi izin pelaksanaan kegiatan hiburan, hajatan, atau nyongkolan.'],
                    ];
                @endphp
                @foreach($layanan as $item)
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-100 hover:shadow-md hover:border-green-200 transition flex flex-col h-full">
                        <div>
                            <span class="text-xs font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded-full uppercase">{{ $item['kode'] }}</span>
                        </div>
                        <h4 class="font-semibold text-gray-800 mt-3 mb-2 leading-snug">{{ $item['nama'] }}</h4>
                        <p class="text-gray-500 text-sm flex-grow leading-relaxed">{{ $item['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Cara Pengajuan -->
    <div class="bg-green-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-2xl font-bold text-center text-gray-800 mb-10">Cara Pengajuan Surat</h3>
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 text-center">
                @php
                    $cara = [
                        ['step' => '1', 'title' => 'Siapkan NIK',     'desc' => 'Pastikan NIK Anda sudah terdaftar di Kantor Desa Karombo.'],
                        ['step' => '2', 'title' => 'Login ke Portal', 'desc' => 'Masuk menggunakan NIK dan Tanggal Lahir Anda.'],
                        ['step' => '3', 'title' => 'Ajukan Surat',    'desc' => 'Pilih jenis surat dan isi formulir pengajuan online.'],
                        ['step' => '4', 'title' => 'Unduh Surat',     'desc' => 'Surat resmi siap diunduh setelah disetujui petugas.'],
                    ];
                @endphp
                @foreach($cara as $c)
                    <div>
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-green-700 text-white font-bold text-lg rounded-full mb-4">{{ $c['step'] }}</div>
                        <h4 class="font-semibold text-gray-800 mb-2">{{ $c['title'] }}</h4>
                        <p class="text-gray-500 text-sm">{{ $c['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-green-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="font-semibold">Kantor Desa Karombo</p>
            <p class="text-green-300 text-sm mt-1">Jln. Lintas Persiapan Karombo, Desa Karombo, NTB</p>
            <p class="text-green-400 text-xs mt-3">© {{ date('Y') }} Sistem Informasi Layanan Surat Desa Karombo - laluryanputrawan. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
