<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use App\Models\TemplateSurat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSuratSeeder extends Seeder
{
    public function run(): void
    {
        // Disable FK checks, truncate, then re-enable
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        TemplateSurat::truncate();
        JenisSurat::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        $data = [
            [
                'kode'        => 'SKD',
                'nama'        => 'Surat Keterangan Domisili',
                'judul'       => 'SURAT KETERANGAN DOMISILI',
                'keterangan'  => 'Surat yang menerangkan bahwa seseorang benar-benar bertempat tinggal atau berdomisili di wilayah Desa Karombo.',
                'persyaratan' => "1. Fotokopi KTP yang masih berlaku\n2. Fotokopi Kartu Keluarga (KK)\n3. Surat pengantar dari RT/RW setempat",
                'isi'   => '<p>Yang bertanda tangan di bawah ini, Kepala Desa Karombo, Kecamatan Sape, Kabupaten Bima, Provinsi Nusa Tenggara Barat, menerangkan bahwa:</p>
<table style="width:100%;margin:12px 0;font-size:9.5pt">
  <tr><td style="width:170px;padding:3px 0">Nama Lengkap</td><td style="width:16px">:</td><td><strong>{$nama}</strong></td></tr>
  <tr><td style="padding:3px 0">NIK</td><td>:</td><td>{$nik}</td></tr>
  <tr><td style="padding:3px 0">Tempat / Tgl. Lahir</td><td>:</td><td>{$tempat_lahir} / {$tanggal_lahir}</td></tr>
  <tr><td style="padding:3px 0">Jenis Kelamin</td><td>:</td><td>{$jenis_kelamin}</td></tr>
  <tr><td style="padding:3px 0">Agama</td><td>:</td><td>{$agama}</td></tr>
  <tr><td style="padding:3px 0">Pekerjaan</td><td>:</td><td>{$pekerjaan}</td></tr>
  <tr><td style="padding:3px 0">Alamat</td><td>:</td><td>{$alamat}</td></tr>
</table>
<p>Berdasarkan data yang ada, orang tersebut di atas <strong>benar-benar berdomisili / bertempat tinggal</strong> di alamat yang tersebut di atas, dan merupakan warga Desa Karombo.</p>
<p style="margin-top:10px">Surat keterangan ini dibuat untuk keperluan: <strong>{$keperluan}</strong></p>',
            ],
            [
                'kode'        => 'SKP',
                'nama'        => 'Surat Keterangan Pindah/Datang (WNI)',
                'judul'       => 'SURAT KETERANGAN PINDAH / DATANG',
                'keterangan'  => 'Surat pengantar yang menerangkan bahwa seseorang telah pindah dari atau datang ke wilayah Desa Karombo dan telah melapor kepada pihak desa.',
                'persyaratan' => "1. Fotokopi KTP\n2. Fotokopi Kartu Keluarga (KK)\n3. Surat keterangan pindah dari daerah asal (jika pendatang)\n4. Surat pengantar RT/RW",
                'isi'   => '<p>Yang bertanda tangan di bawah ini, Kepala Desa Karombo, Kecamatan Sape, Kabupaten Bima, menerangkan bahwa orang di bawah ini:</p>
<table style="width:100%;margin:12px 0;font-size:9.5pt">
  <tr><td style="width:170px;padding:3px 0">Nama Lengkap</td><td style="width:16px">:</td><td><strong>{$nama}</strong></td></tr>
  <tr><td style="padding:3px 0">NIK</td><td>:</td><td>{$nik}</td></tr>
  <tr><td style="padding:3px 0">Tempat / Tgl. Lahir</td><td>:</td><td>{$tempat_lahir} / {$tanggal_lahir}</td></tr>
  <tr><td style="padding:3px 0">Jenis Kelamin</td><td>:</td><td>{$jenis_kelamin}</td></tr>
  <tr><td style="padding:3px 0">Agama</td><td>:</td><td>{$agama}</td></tr>
  <tr><td style="padding:3px 0">Pekerjaan</td><td>:</td><td>{$pekerjaan}</td></tr>
  <tr><td style="padding:3px 0">Alamat Asal</td><td>:</td><td>{$alamat}</td></tr>
</table>
<p>Adalah benar warga yang pindah/datang ke wilayah Desa Karombo dan telah melapor kepada pihak desa.</p>
<p style="margin-top:10px">Keperluan: <strong>{$keperluan}</strong></p>',
            ],
            [
                'kode'        => 'SKL',
                'nama'        => 'Surat Keterangan Kelahiran',
                'judul'       => 'SURAT KETERANGAN KELAHIRAN',
                'keterangan'  => 'Surat yang menerangkan telah lahirnya seorang anak di wilayah Desa Karombo sebagai pengantar pengurusan akta kelahiran.',
                'persyaratan' => "1. Fotokopi KTP kedua orang tua\n2. Fotokopi Kartu Keluarga (KK)\n3. Surat keterangan lahir dari bidan/dokter/rumah sakit\n4. Surat pengantar RT/RW",
                'isi'   => '<p>Yang bertanda tangan di bawah ini, Kepala Desa Karombo, Kecamatan Sape, Kabupaten Bima, menerangkan bahwa telah lahir seorang anak dari:</p>
<table style="width:100%;margin:12px 0;font-size:9.5pt">
  <tr><td style="width:170px;padding:3px 0">Nama Orang Tua (Ibu)</td><td style="width:16px">:</td><td><strong>{$nama}</strong></td></tr>
  <tr><td style="padding:3px 0">NIK</td><td>:</td><td>{$nik}</td></tr>
  <tr><td style="padding:3px 0">Tempat / Tgl. Lahir</td><td>:</td><td>{$tempat_lahir} / {$tanggal_lahir}</td></tr>
  <tr><td style="padding:3px 0">Agama</td><td>:</td><td>{$agama}</td></tr>
  <tr><td style="padding:3px 0">Alamat</td><td>:</td><td>{$alamat}</td></tr>
</table>
<p>Surat keterangan ini dibuat sebagai pengantar untuk pengurusan dokumen kelahiran.</p>
<p style="margin-top:10px">Keperluan: <strong>{$keperluan}</strong></p>',
            ],
            [
                'kode'        => 'SKM',
                'nama'        => 'Surat Keterangan Kematian',
                'judul'       => 'SURAT KETERANGAN KEMATIAN',
                'keterangan'  => 'Surat yang menerangkan bahwa seseorang telah meninggal dunia, diperlukan untuk pengurusan akta kematian dan administrasi lainnya.',
                'persyaratan' => "1. Fotokopi KTP almarhum/ah\n2. Fotokopi Kartu Keluarga (KK)\n3. Surat keterangan kematian dari dokter/puskesmas/rumah sakit\n4. KTP pelapor (ahli waris)\n5. Surat pengantar RT/RW",
                'isi'   => '<p>Yang bertanda tangan di bawah ini, Kepala Desa Karombo, Kecamatan Sape, Kabupaten Bima, menerangkan bahwa:</p>
<table style="width:100%;margin:12px 0;font-size:9.5pt">
  <tr><td style="width:170px;padding:3px 0">Nama Almarhum/ah</td><td style="width:16px">:</td><td><strong>{$nama}</strong></td></tr>
  <tr><td style="padding:3px 0">NIK</td><td>:</td><td>{$nik}</td></tr>
  <tr><td style="padding:3px 0">Tempat / Tgl. Lahir</td><td>:</td><td>{$tempat_lahir} / {$tanggal_lahir}</td></tr>
  <tr><td style="padding:3px 0">Jenis Kelamin</td><td>:</td><td>{$jenis_kelamin}</td></tr>
  <tr><td style="padding:3px 0">Agama</td><td>:</td><td>{$agama}</td></tr>
  <tr><td style="padding:3px 0">Alamat Terakhir</td><td>:</td><td>{$alamat}</td></tr>
</table>
<p>Adalah benar telah meninggal dunia. Surat keterangan ini dibuat berdasarkan laporan keluarga dan data kependudukan desa.</p>
<p style="margin-top:10px">Keperluan: <strong>{$keperluan}</strong></p>',
            ],
            [
                'kode'        => 'SKBK',
                'nama'        => 'Surat Keterangan Belum Pernah Kawin/Menikah',
                'judul'       => 'SURAT KETERANGAN BELUM PERNAH KAWIN',
                'keterangan'  => 'Surat yang menerangkan bahwa seseorang berstatus lajang dan belum pernah melangsungkan pernikahan secara resmi.',
                'persyaratan' => "1. Fotokopi KTP yang masih berlaku\n2. Fotokopi Kartu Keluarga (KK)\n3. Surat pengantar RT/RW\n4. Pas foto 3x4 (2 lembar)",
                'isi'   => '<p>Yang bertanda tangan di bawah ini, Kepala Desa Karombo, Kecamatan Sape, Kabupaten Bima, menerangkan dengan sesungguhnya bahwa:</p>
<table style="width:100%;margin:12px 0;font-size:9.5pt">
  <tr><td style="width:170px;padding:3px 0">Nama Lengkap</td><td style="width:16px">:</td><td><strong>{$nama}</strong></td></tr>
  <tr><td style="padding:3px 0">NIK</td><td>:</td><td>{$nik}</td></tr>
  <tr><td style="padding:3px 0">Tempat / Tgl. Lahir</td><td>:</td><td>{$tempat_lahir} / {$tanggal_lahir}</td></tr>
  <tr><td style="padding:3px 0">Jenis Kelamin</td><td>:</td><td>{$jenis_kelamin}</td></tr>
  <tr><td style="padding:3px 0">Agama</td><td>:</td><td>{$agama}</td></tr>
  <tr><td style="padding:3px 0">Pekerjaan</td><td>:</td><td>{$pekerjaan}</td></tr>
  <tr><td style="padding:3px 0">Alamat</td><td>:</td><td>{$alamat}</td></tr>
</table>
<p>Yang bersangkutan <strong>benar-benar belum pernah kawin/menikah</strong> dan berstatus lajang berdasarkan data administrasi kependudukan Desa Karombo.</p>
<p style="margin-top:10px">Keperluan: <strong>{$keperluan}</strong></p>',
            ],
            [
                'kode'        => 'SKBN',
                'nama'        => 'Surat Keterangan Beda Nama/Identitas',
                'judul'       => 'SURAT KETERANGAN BEDA NAMA / IDENTITAS',
                'keterangan'  => 'Surat yang menerangkan bahwa seseorang dengan nama berbeda pada dokumen yang berbeda adalah satu orang yang sama.',
                'persyaratan' => "1. Fotokopi KTP\n2. Fotokopi Kartu Keluarga (KK)\n3. Fotokopi dokumen yang memuat perbedaan nama/identitas (ijazah, akta lahir, dll)\n4. Surat pengantar RT/RW",
                'isi'   => '<p>Yang bertanda tangan di bawah ini, Kepala Desa Karombo, Kecamatan Sape, Kabupaten Bima, menerangkan bahwa:</p>
<table style="width:100%;margin:12px 0;font-size:9.5pt">
  <tr><td style="width:170px;padding:3px 0">Nama (KTP/NIK)</td><td style="width:16px">:</td><td><strong>{$nama}</strong></td></tr>
  <tr><td style="padding:3px 0">NIK</td><td>:</td><td>{$nik}</td></tr>
  <tr><td style="padding:3px 0">Tempat / Tgl. Lahir</td><td>:</td><td>{$tempat_lahir} / {$tanggal_lahir}</td></tr>
  <tr><td style="padding:3px 0">Jenis Kelamin</td><td>:</td><td>{$jenis_kelamin}</td></tr>
  <tr><td style="padding:3px 0">Agama</td><td>:</td><td>{$agama}</td></tr>
  <tr><td style="padding:3px 0">Alamat</td><td>:</td><td>{$alamat}</td></tr>
</table>
<p>Adalah benar merupakan satu orang yang sama meskipun terdapat perbedaan penulisan nama/identitas pada dokumen yang dimiliki.</p>
<p style="margin-top:10px">Keperluan: <strong>{$keperluan}</strong></p>',
            ],
            [
                'kode'        => 'SKU',
                'nama'        => 'Surat Keterangan Usaha',
                'judul'       => 'SURAT KETERANGAN USAHA',
                'keterangan'  => 'Surat yang menerangkan bahwa seseorang benar-benar memiliki dan menjalankan usaha di wilayah Desa Karombo.',
                'persyaratan' => "1. Fotokopi KTP pemilik usaha\n2. Fotokopi Kartu Keluarga (KK)\n3. Surat pengantar RT/RW\n4. Foto tempat usaha (jika ada)",
                'isi'   => '<p>Yang bertanda tangan di bawah ini, Kepala Desa Karombo, Kecamatan Sape, Kabupaten Bima, menerangkan bahwa:</p>
<table style="width:100%;margin:12px 0;font-size:9.5pt">
  <tr><td style="width:170px;padding:3px 0">Nama Pemilik</td><td style="width:16px">:</td><td><strong>{$nama}</strong></td></tr>
  <tr><td style="padding:3px 0">NIK</td><td>:</td><td>{$nik}</td></tr>
  <tr><td style="padding:3px 0">Tempat / Tgl. Lahir</td><td>:</td><td>{$tempat_lahir} / {$tanggal_lahir}</td></tr>
  <tr><td style="padding:3px 0">Pekerjaan / Usaha</td><td>:</td><td>{$pekerjaan}</td></tr>
  <tr><td style="padding:3px 0">Alamat Pemilik</td><td>:</td><td>{$alamat}</td></tr>
</table>
<p>Adalah benar memiliki dan menjalankan usaha sebagaimana tersebut di atas, yang berlokasi di wilayah Desa Karombo.</p>
<p style="margin-top:10px">Keperluan: <strong>{$keperluan}</strong></p>',
            ],
            [
                'kode'        => 'SKTM',
                'nama'        => 'Surat Keterangan Tidak Mampu (SKTM)',
                'judul'       => 'SURAT KETERANGAN TIDAK MAMPU',
                'keterangan'  => 'Surat yang menerangkan bahwa seseorang tergolong warga kurang/tidak mampu secara ekonomi, digunakan untuk memperoleh keringanan biaya layanan publik.',
                'persyaratan' => "1. Fotokopi KTP\n2. Fotokopi Kartu Keluarga (KK)\n3. Surat pengantar RT/RW\n4. Surat keterangan penghasilan (jika ada)\n5. Pas foto 3x4 (2 lembar)",
                'isi'   => '<p>Yang bertanda tangan di bawah ini, Kepala Desa Karombo, Kecamatan Sape, Kabupaten Bima, menerangkan bahwa:</p>
<table style="width:100%;margin:12px 0;font-size:9.5pt">
  <tr><td style="width:170px;padding:3px 0">Nama Lengkap</td><td style="width:16px">:</td><td><strong>{$nama}</strong></td></tr>
  <tr><td style="padding:3px 0">NIK</td><td>:</td><td>{$nik}</td></tr>
  <tr><td style="padding:3px 0">Tempat / Tgl. Lahir</td><td>:</td><td>{$tempat_lahir} / {$tanggal_lahir}</td></tr>
  <tr><td style="padding:3px 0">Jenis Kelamin</td><td>:</td><td>{$jenis_kelamin}</td></tr>
  <tr><td style="padding:3px 0">Agama</td><td>:</td><td>{$agama}</td></tr>
  <tr><td style="padding:3px 0">Pekerjaan</td><td>:</td><td>{$pekerjaan}</td></tr>
  <tr><td style="padding:3px 0">Alamat</td><td>:</td><td>{$alamat}</td></tr>
</table>
<p>Adalah benar warga Desa Karombo yang tergolong dalam kategori <strong>kurang mampu / tidak mampu secara ekonomi</strong> berdasarkan data sosial ekonomi desa.</p>
<p style="margin-top:10px">Surat keterangan ini diberikan untuk keperluan: <strong>{$keperluan}</strong></p>',
            ],
            [
                'kode'        => 'SKDU',
                'nama'        => 'Surat Keterangan Domisili Usaha (SKDU)',
                'judul'       => 'SURAT KETERANGAN DOMISILI USAHA',
                'keterangan'  => 'Surat yang menerangkan bahwa suatu usaha benar-benar berdomisili dan beroperasi di wilayah Desa Karombo.',
                'persyaratan' => "1. Fotokopi KTP pemilik usaha\n2. Fotokopi Kartu Keluarga (KK)\n3. Surat pengantar RT/RW\n4. Foto tempat usaha\n5. Denah lokasi usaha (jika diperlukan)",
                'isi'   => '<p>Yang bertanda tangan di bawah ini, Kepala Desa Karombo, Kecamatan Sape, Kabupaten Bima, menerangkan bahwa:</p>
<table style="width:100%;margin:12px 0;font-size:9.5pt">
  <tr><td style="width:170px;padding:3px 0">Nama Pemilik</td><td style="width:16px">:</td><td><strong>{$nama}</strong></td></tr>
  <tr><td style="padding:3px 0">NIK</td><td>:</td><td>{$nik}</td></tr>
  <tr><td style="padding:3px 0">Pekerjaan / Jenis Usaha</td><td>:</td><td>{$pekerjaan}</td></tr>
  <tr><td style="padding:3px 0">Alamat / Lokasi Usaha</td><td>:</td><td>{$alamat}</td></tr>
</table>
<p>Adalah benar memiliki usaha yang <strong>berdomisili di wilayah Desa Karombo</strong>, dan usaha tersebut nyata adanya serta tidak melanggar peraturan yang berlaku.</p>
<p style="margin-top:10px">Keperluan: <strong>{$keperluan}</strong></p>',
            ],
            [
                'kode'        => 'SP-SKCK',
                'nama'        => 'Surat Pengantar SKCK',
                'judul'       => 'SURAT PENGANTAR SKCK',
                'keterangan'  => 'Surat pengantar dari desa untuk keperluan pembuatan Surat Keterangan Catatan Kepolisian (SKCK) di Kepolisian Resort setempat.',
                'persyaratan' => "1. Fotokopi KTP yang masih berlaku\n2. Fotokopi Kartu Keluarga (KK)\n3. Pas foto 4x6 latar merah (4 lembar)\n4. Fotokopi ijazah terakhir\n5. Surat pengantar RT/RW",
                'isi'   => '<p>Yang bertanda tangan di bawah ini, Kepala Desa Karombo, Kecamatan Sape, Kabupaten Bima, dengan ini menerangkan dan mengantarkan bahwa:</p>
<table style="width:100%;margin:12px 0;font-size:9.5pt">
  <tr><td style="width:170px;padding:3px 0">Nama Lengkap</td><td style="width:16px">:</td><td><strong>{$nama}</strong></td></tr>
  <tr><td style="padding:3px 0">NIK</td><td>:</td><td>{$nik}</td></tr>
  <tr><td style="padding:3px 0">Tempat / Tgl. Lahir</td><td>:</td><td>{$tempat_lahir} / {$tanggal_lahir}</td></tr>
  <tr><td style="padding:3px 0">Jenis Kelamin</td><td>:</td><td>{$jenis_kelamin}</td></tr>
  <tr><td style="padding:3px 0">Agama</td><td>:</td><td>{$agama}</td></tr>
  <tr><td style="padding:3px 0">Pekerjaan</td><td>:</td><td>{$pekerjaan}</td></tr>
  <tr><td style="padding:3px 0">Alamat</td><td>:</td><td>{$alamat}</td></tr>
</table>
<p>Yang bersangkutan adalah warga Desa Karombo yang bermaksud mengurus <strong>Surat Keterangan Catatan Kepolisian (SKCK)</strong> ke Kepolisian Resort setempat.</p>
<p style="margin-top:10px">Keperluan: <strong>{$keperluan}</strong></p>',
            ],
            [
                'kode'        => 'SP-N1',
                'nama'        => 'Surat Pengantar Nikah (Model N-1)',
                'judul'       => 'SURAT PENGANTAR NIKAH (MODEL N-1)',
                'keterangan'  => 'Surat pengantar dari desa untuk keperluan pendaftaran pernikahan di Kantor Urusan Agama (KUA) Kecamatan Sape.',
                'persyaratan' => "1. Fotokopi KTP calon pengantin\n2. Fotokopi Kartu Keluarga (KK)\n3. Pas foto 3x4 (4 lembar)\n4. Akta kelahiran calon pengantin\n5. Surat keterangan belum menikah dari RT/RW\n6. Surat pengantar RT/RW",
                'isi'   => '<p>Yang bertanda tangan di bawah ini, Kepala Desa Karombo, Kecamatan Sape, Kabupaten Bima, menerangkan bahwa:</p>
<table style="width:100%;margin:12px 0;font-size:9.5pt">
  <tr><td style="width:170px;padding:3px 0">Nama Calon</td><td style="width:16px">:</td><td><strong>{$nama}</strong></td></tr>
  <tr><td style="padding:3px 0">NIK</td><td>:</td><td>{$nik}</td></tr>
  <tr><td style="padding:3px 0">Tempat / Tgl. Lahir</td><td>:</td><td>{$tempat_lahir} / {$tanggal_lahir}</td></tr>
  <tr><td style="padding:3px 0">Jenis Kelamin</td><td>:</td><td>{$jenis_kelamin}</td></tr>
  <tr><td style="padding:3px 0">Agama</td><td>:</td><td>{$agama}</td></tr>
  <tr><td style="padding:3px 0">Pekerjaan</td><td>:</td><td>{$pekerjaan}</td></tr>
  <tr><td style="padding:3px 0">Alamat</td><td>:</td><td>{$alamat}</td></tr>
</table>
<p>Adalah benar warga Desa Karombo yang hendak melangsungkan pernikahan. Surat pengantar ini diberikan untuk disampaikan kepada Kantor Urusan Agama (KUA) Kecamatan Sape.</p>
<p style="margin-top:10px">Keperluan: <strong>{$keperluan}</strong></p>',
            ],
            [
                'kode'        => 'SP-KTP',
                'nama'        => 'Surat Pengantar KTP/KK',
                'judul'       => 'SURAT PENGANTAR KTP / KARTU KELUARGA',
                'keterangan'  => 'Surat pengantar dari desa untuk keperluan pembuatan atau perubahan Kartu Tanda Penduduk (KTP) dan/atau Kartu Keluarga (KK) di Dinas Kependudukan dan Catatan Sipil.',
                'persyaratan' => "1. Surat pengantar RT/RW\n2. Fotokopi akta kelahiran (untuk KTP baru)\n3. Fotokopi KK lama\n4. Foto 3x4 (2 lembar) berlatar merah\n5. KTP lama (jika perpanjangan/perubahan data)",
                'isi'   => '<p>Yang bertanda tangan di bawah ini, Kepala Desa Karombo, Kecamatan Sape, Kabupaten Bima, dengan ini menerangkan bahwa:</p>
<table style="width:100%;margin:12px 0;font-size:9.5pt">
  <tr><td style="width:170px;padding:3px 0">Nama Lengkap</td><td style="width:16px">:</td><td><strong>{$nama}</strong></td></tr>
  <tr><td style="padding:3px 0">NIK</td><td>:</td><td>{$nik}</td></tr>
  <tr><td style="padding:3px 0">Tempat / Tgl. Lahir</td><td>:</td><td>{$tempat_lahir} / {$tanggal_lahir}</td></tr>
  <tr><td style="padding:3px 0">Jenis Kelamin</td><td>:</td><td>{$jenis_kelamin}</td></tr>
  <tr><td style="padding:3px 0">Agama</td><td>:</td><td>{$agama}</td></tr>
  <tr><td style="padding:3px 0">Pekerjaan</td><td>:</td><td>{$pekerjaan}</td></tr>
  <tr><td style="padding:3px 0">Alamat</td><td>:</td><td>{$alamat}</td></tr>
</table>
<p>Adalah benar warga Desa Karombo yang bermaksud mengurus <strong>Kartu Tanda Penduduk (KTP) / Kartu Keluarga (KK)</strong> ke Dinas Kependudukan dan Catatan Sipil Kabupaten Bima.</p>
<p style="margin-top:10px">Keperluan: <strong>{$keperluan}</strong></p>',
            ],
        ];

        foreach ($data as $item) {
            $jenis = JenisSurat::create([
                'kode_surat' => $item['kode'],
                'nama_surat' => $item['nama'],
            ]);

            TemplateSurat::create([
                'jenis_surat_id' => $jenis->id,
                'nama_surat'     => $item['nama'],
                'judul_surat'    => $item['judul'],
                'keterangan'     => $item['keterangan'],
                'isi_surat'      => $item['isi'],
                'persyaratan'    => $item['persyaratan'],
                'status'         => 'Aktif',
            ]);
        }

        $this->command->info('✓ 12 Jenis Surat dan Template berhasil dibuat.');
    }
}
