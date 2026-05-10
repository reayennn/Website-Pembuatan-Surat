<?php

namespace App\Services;

use App\Models\Penduduk;

/**
 * ValidasiSuratService
 *
 * Mengelola semua aturan kelayakan pengajuan surat berdasarkan
 * data kependudukan (Penduduk) yang tersimpan di database.
 *
 * Setiap jenis surat diidentifikasi via kode_surat dari tabel jenis_surats.
 */
class ValidasiSuratService
{
    /**
     * Jalankan validasi kelayakan untuk satu penduduk & satu kode surat.
     *
     * @param  Penduduk  $penduduk
     * @param  string    $kodeSurat   kode_surat dari tabel jenis_surats
     * @return array{
     *   valid: bool,
     *   errors: string[],
     *   warnings: string[],
     *   info: string[]
     * }
     */
    public function validate(Penduduk $penduduk, string $kodeSurat): array
    {
        $errors   = [];
        $warnings = [];
        $info     = [];

        switch (strtoupper($kodeSurat)) {

            // ── 1. Surat Keterangan Domisili ────────────────────────────
            case 'SKD':
                if (empty(trim($penduduk->alamat ?? ''))) {
                    $errors[] = 'Alamat Anda belum tercatat di database kependudukan. Hubungi admin desa untuk melengkapi data.';
                }
                break;

            // ── 2. Surat Keterangan Pindah/Datang ───────────────────────
            case 'SKP':
                if (empty(trim($penduduk->alamat ?? ''))) {
                    $errors[] = 'Alamat asal Anda belum tercatat. Hubungi admin desa untuk melengkapi data sebelum mengajukan surat pindah.';
                }
                break;

            // ── 3. Surat Keterangan Kelahiran ───────────────────────────
            case 'SKL':
                // Terbuka untuk semua gender (ayah/ibu/wali bisa melapor)
                // Cek minimal nama & alamat terisi
                if (empty(trim($penduduk->nama ?? ''))) {
                    $errors[] = 'Data nama Anda belum tercatat lengkap di database kependudukan.';
                }
                $info[] = 'Surat Keterangan Kelahiran ini digunakan sebagai pengantar pengurusan akta kelahiran anak.';
                break;

            // ── 4. Surat Keterangan Kematian ────────────────────────────
            case 'SKM':
                // Siapa saja (ahli waris/keluarga) boleh mengajukan
                $info[] = 'Pastikan Anda adalah ahli waris atau keluarga dari almarhum/ah yang bersangkutan.';
                break;

            // ── 5. Surat Keterangan Belum Pernah Kawin ──────────────────
            case 'SKBK':
                $status = strtolower(trim($penduduk->status_perkawinan ?? ''));
                if ($status !== 'belum kawin' && $status !== '') {
                    $statusLabel = ucwords($penduduk->status_perkawinan);
                    $errors[] = "Data kependudukan Anda menunjukkan status perkawinan: \"{$statusLabel}\". Surat Keterangan Belum Pernah Kawin hanya dapat diterbitkan untuk warga dengan status \"Belum Kawin\".";
                } elseif ($status === '') {
                    $warnings[] = 'Status perkawinan Anda belum tercatat di database. Pastikan data sudah diperbarui oleh admin desa.';
                }
                break;

            // ── 6. Surat Keterangan Beda Nama/Identitas ─────────────────
            case 'SKBN':
                // Tidak ada syarat khusus berdasarkan data kependudukan
                $info[] = 'Siapkan dokumen asli yang menunjukkan perbedaan nama/identitas (ijazah, akta lahir, dll).';
                break;

            // ── 7. Surat Keterangan Usaha ────────────────────────────────
            case 'SKU':
                if (empty(trim($penduduk->pekerjaan ?? ''))) {
                    $errors[] = 'Data pekerjaan/usaha Anda belum tercatat di database kependudukan. Hubungi admin desa untuk melengkapi data.';
                } else {
                    $info[] = "Pekerjaan/usaha yang terdata: \"{$penduduk->pekerjaan}\". Pastikan sesuai dengan usaha yang akan diterangkan.";
                }
                break;

            // ── 8. Surat Keterangan Tidak Mampu ─────────────────────────
            case 'SKTM':
                // Tidak ada kolom status ekonomi di DB, terbuka untuk semua.
                // Verifikasi dilakukan secara manual oleh admin/kades.
                $info[]     = 'Pengajuan SKTM akan melalui proses verifikasi oleh admin dan kepala desa berdasarkan kondisi nyata di lapangan.';
                $warnings[] = 'Pastikan Anda benar-benar membutuhkan surat ini. Penyalahgunaan SKTM dapat dikenai sanksi administratif.';
                break;

            // ── 9. Surat Keterangan Domisili Usaha ──────────────────────
            case 'SKDU':
                $missingFields = [];
                if (empty(trim($penduduk->pekerjaan ?? ''))) {
                    $missingFields[] = 'pekerjaan/jenis usaha';
                }
                if (empty(trim($penduduk->alamat ?? ''))) {
                    $missingFields[] = 'alamat/lokasi usaha';
                }
                if (!empty($missingFields)) {
                    $errors[] = 'Data berikut belum tercatat di database: ' . implode(' dan ', $missingFields) . '. Hubungi admin desa untuk melengkapi sebelum mengajukan surat ini.';
                }
                break;

            // ── 10. Surat Pengantar SKCK ─────────────────────────────────
            case 'SP-SKCK':
                $kwn = strtolower(trim($penduduk->kewarganegaraan ?? 'wni'));
                if ($kwn !== 'wni' && $kwn !== '') {
                    $errors[] = 'Surat Pengantar SKCK hanya dapat diterbitkan untuk Warga Negara Indonesia (WNI). Status kewarganegaraan Anda: "' . strtoupper($penduduk->kewarganegaraan) . '".';
                }
                $info[] = 'SKCK diterbitkan oleh Kepolisian. Surat pengantar ini hanya sebagai dokumen administrasi dari desa.';
                break;

            // ── 11. Surat Pengantar Nikah (N-1) ─────────────────────────
            case 'SP-N1':
                $status = strtolower(trim($penduduk->status_perkawinan ?? ''));
                if ($status === 'kawin' || $status === 'sudah kawin' || $status === 'menikah') {
                    $errors[] = "Data kependudukan Anda menunjukkan status perkawinan: \"{$penduduk->status_perkawinan}\". Surat Pengantar Nikah hanya dapat diterbitkan bagi yang berstatus \"Belum Kawin\" atau \"Cerai\".";
                } elseif ($status === '') {
                    $warnings[] = 'Status perkawinan Anda belum tercatat. Pastikan data telah diperbarui oleh admin desa agar proses pengajuan surat nikah dapat diverifikasi.';
                } elseif (in_array($status, ['cerai hidup', 'cerai mati'])) {
                    $info[] = "Status perkawinan Anda: \"{$penduduk->status_perkawinan}\". Pengajuan dapat dilanjutkan, namun wajib melampirkan akta cerai/surat kematian pasangan.";
                }
                break;

            // ── 12. Surat Pengantar KTP/KK ──────────────────────────────
            case 'SP-KTP':
                // Semua warga bisa mengajukan
                $info[] = 'Pastikan Anda membawa dokumen pendukung yang diperlukan (KTP lama, akta kelahiran, dll) sesuai keperluan.';
                break;

            default:
                // Jenis surat tidak dikenal, tidak ada validasi khusus
                break;
        }

        return [
            'valid'    => empty($errors),
            'errors'   => $errors,
            'warnings' => $warnings,
            'info'     => $info,
        ];
    }

    /**
     * Ambil label ringkas status validasi untuk ditampilkan di frontend.
     */
    public function getStatusLabel(array $result): string
    {
        if (!$result['valid']) {
            return 'Tidak Memenuhi Syarat';
        }
        if (!empty($result['warnings'])) {
            return 'Perlu Perhatian';
        }
        return 'Memenuhi Syarat';
    }
}
