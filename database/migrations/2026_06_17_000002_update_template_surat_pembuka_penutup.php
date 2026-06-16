<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Update isi_pembuka & isi_penutup semua template surat
     * agar sesuai format resmi Desa Karombo, Kecamatan Pekat, Kabupaten Dompu.
     */
    public function up(): void
    {
        $pembuka = '<p>Yang bertanda tangan di bawah ini Kepala Desa Karombo Kecamatan Pekat Kabupaten Dompu menerangkan dengan sebenar &#8209; sebenarnya kepada :</p>';

        $penutup = '<p>Bahwa yang tersebut namanya di atas adalah benar-benar warga / penduduk Asli Desa Karombo Kecamatan Pekat Kabupaten Dompu dan sampai saat surat ini dikeluarkan yang bersangkutan masih Berdomisili di Desa Karombo Kecamatan Pekat Kabupaten Dompu.</p><p><br></p><p>Demikian surat keterangan ini kami berikan untuk dipergunakan sebagaimana mestinya.</p>';

        // Template yang bersifat keterangan domisili (gunakan penutup berdomisili)
        DB::table('template_surats')->update([
            'isi_pembuka' => $pembuka,
            'isi_penutup' => $penutup,
        ]);
    }

    public function down(): void
    {
        // Restore ke teks lama (generik)
        $pembukaLama = '<p>Yang bertanda tangan di bawah ini, <strong>Kepala Desa Karombo</strong>, Kecamatan Sape, Kabupaten Bima, Provinsi Nusa Tenggara Barat, dengan ini menerangkan dengan sesungguhnya bahwa:</p>';
        $penutupLama = '<p>Demikian surat keterangan ini kami buat dengan sebenarnya berdasarkan data administrasi kependudukan yang ada, untuk dapat dipergunakan sebagaimana mestinya.</p>';

        DB::table('template_surats')->update([
            'isi_pembuka' => $pembukaLama,
            'isi_penutup' => $penutupLama,
        ]);
    }
};
