<?php

namespace App\Http\Requests;

use App\Enums\JenisPengaduan;
use App\Rules\Recaptcha;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReportRequest extends FormRequest
{
    public const KLASIFIKASI = ['Pengaduan', 'Aspirasi', 'Informasi / Saran'];

    public const MAX_FILES = 5;

    public const MAX_FILE_KB = 5120;

    public const MAX_URAIAN = 5000;

    public function authorize(): bool
    {
        return true;
    }

    public function isPengaduan(): bool
    {
        return $this->input('klasifikasi') === 'Pengaduan';
    }

    public function rules(): array
    {
        $wajibUntukPengaduan = $this->isPengaduan() ? 'required' : 'nullable';

        return [
            'klasifikasi'          => ['required', Rule::in(self::KLASIFIKASI)],
            'judul_laporan'        => ['required', 'string', 'max:255'],
            'nama_pelapor'         => ['nullable', 'string', 'max:100'],
            'uraian_kronologi'     => ['required', 'string', 'max:' . self::MAX_URAIAN],
            'nama_terlapor'        => [$wajibUntukPengaduan, 'string', 'max:255'],
            'jenis_pengaduan'      => [$wajibUntukPengaduan, Rule::enum(JenisPengaduan::class)],
            'tanggal_kejadian'     => [$wajibUntukPengaduan, 'date', 'before_or_equal:today'],
            'bukti_pelaporan'      => ['nullable', 'array', 'max:' . self::MAX_FILES],
            'bukti_pelaporan.*'    => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:' . self::MAX_FILE_KB],
            'g-recaptcha-response' => ['required', 'string', new Recaptcha],
        ];
    }

    public function messages(): array
    {
        return [
            'required'                      => ':attribute wajib diisi.',
            'max.string'                    => ':attribute maksimal :max karakter.',
            'bukti_pelaporan.max'           => 'Maksimal ' . self::MAX_FILES . ' file yang dapat diunggah.',
            'bukti_pelaporan.*.mimes'       => 'Bukti pendukung harus berformat JPG, JPEG, PNG, atau PDF.',
            'bukti_pelaporan.*.max'         => 'Ukuran setiap file bukti pendukung maksimal 5 MB.',
            'bukti_pelaporan.*.file'        => 'Bukti pendukung gagal diunggah. Silakan pilih ulang file.',
            'jenis_pengaduan.enum'          => 'Jenis pengaduan tidak valid.',
            'klasifikasi.in'                => 'Klasifikasi laporan tidak valid.',
            'tanggal_kejadian.date'         => 'Tanggal kejadian tidak valid.',
            'tanggal_kejadian.before_or_equal' => 'Tanggal kejadian tidak boleh melebihi hari ini.',
            'g-recaptcha-response.required' => 'Silakan centang kotak verifikasi bahwa Anda bukan robot.',
        ];
    }

    public function attributes(): array
    {
        return [
            'klasifikasi'      => 'Klasifikasi',
            'judul_laporan'    => 'Judul laporan',
            'nama_pelapor'     => 'Nama pelapor',
            'uraian_kronologi' => 'Uraian',
            'nama_terlapor'    => 'Nama terlapor',
            'jenis_pengaduan'  => 'Jenis pengaduan',
            'tanggal_kejadian' => 'Tanggal kejadian',
        ];
    }
}
