<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Services\GoogleDriveService;
use App\Services\WhatsAppService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    private bool $captchaValid = true;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.recaptcha.verify_url' => 'https://recaptcha.test/verify']);
        Http::fake(['recaptcha.test/*' => fn () => Http::response(['success' => $this->captchaValid])]);

        // Layanan eksternal diganti tiruan agar pengujian tidak memanggil Google Drive dan Fonnte.
        $this->mock(WhatsAppService::class)->shouldReceive('kirimNotifikasi')->andReturn('ok');
        $this->mock(GoogleDriveService::class)->shouldReceive('uploadMultipleFiles')->andReturn(['file-id-1']);
    }

    private function pengaduan(array $override = []): array
    {
        return array_merge([
            'klasifikasi' => 'Pengaduan',
            'judul_laporan' => 'Dugaan gratifikasi pengadaan',
            'nama_terlapor' => 'Pegawai A',
            'jenis_pengaduan' => 'gratifikasi',
            'tanggal_kejadian' => now()->subDay()->toDateString(),
            'uraian_kronologi' => 'Kronologi singkat kejadian.',
            'g-recaptcha-response' => 'token-uji',
        ], $override);
    }

    public function test_pengaduan_valid_tersimpan_dengan_status_diterima(): void
    {
        $this->post(route('laporan.store'), $this->pengaduan())
            ->assertRedirect(route('laporan.sukses'));

        $ticket = Ticket::first();
        $this->assertNotNull($ticket);
        $this->assertSame('Anonim', $ticket->nama_pelapor);
        $this->assertMatchesRegularExpression('/^WBS-\d{4}-[A-Z0-9]{5}$/', $ticket->ticket_code);
        $this->assertDatabaseHas('ticket_progress', ['id_ticket' => $ticket->id, 'status' => 'diterima']);
    }

    public function test_aspirasi_tanpa_data_terlapor_tersimpan(): void
    {
        $this->post(route('laporan.store'), [
            'klasifikasi' => 'Aspirasi',
            'judul_laporan' => 'Usulan ruang tunggu',
            'uraian_kronologi' => 'Mohon ditambah kursi.',
            'g-recaptcha-response' => 'token-uji',
        ])->assertRedirect(route('laporan.sukses'));

        $this->assertDatabaseHas('tickets', ['klasifikasi' => 'Aspirasi', 'jenis_pengaduan' => null]);
    }

    public function test_laporan_ditolak_tanpa_recaptcha(): void
    {
        $data = $this->pengaduan();
        unset($data['g-recaptcha-response']);

        $this->post(route('laporan.store'), $data)->assertSessionHasErrors('g-recaptcha-response');
        $this->assertDatabaseCount('tickets', 0);
    }

    public function test_laporan_ditolak_jika_recaptcha_gagal(): void
    {
        $this->captchaValid = false;

        $this->post(route('laporan.store'), $this->pengaduan())->assertSessionHasErrors('g-recaptcha-response');
        $this->assertDatabaseCount('tickets', 0);
    }

    public function test_jenis_pengaduan_di_luar_pilihan_ditolak(): void
    {
        $this->post(route('laporan.store'), $this->pengaduan(['jenis_pengaduan' => 'sembarang']))
            ->assertSessionHasErrors('jenis_pengaduan');
    }

    public function test_tanggal_kejadian_di_masa_depan_ditolak(): void
    {
        $this->post(route('laporan.store'), $this->pengaduan(['tanggal_kejadian' => now()->addDay()->toDateString()]))
            ->assertSessionHasErrors('tanggal_kejadian');
    }

    public function test_uraian_terlalu_panjang_ditolak(): void
    {
        $this->post(route('laporan.store'), $this->pengaduan(['uraian_kronologi' => str_repeat('a', 5001)]))
            ->assertSessionHasErrors('uraian_kronologi');
    }

    public function test_file_bukti_dengan_ekstensi_terlarang_ditolak(): void
    {
        $data = $this->pengaduan(['bukti_pelaporan' => [UploadedFile::fake()->create('skrip.php', 10, 'application/x-php')]]);

        $this->post(route('laporan.store'), $data)->assertSessionHasErrors('bukti_pelaporan.0');
    }

    public function test_file_bukti_lebih_dari_5mb_ditolak(): void
    {
        $data = $this->pengaduan(['bukti_pelaporan' => [UploadedFile::fake()->create('besar.pdf', 6000, 'application/pdf')]]);

        $this->post(route('laporan.store'), $data)->assertSessionHasErrors('bukti_pelaporan.0');
    }

    public function test_file_bukti_lebih_dari_5_ditolak(): void
    {
        $files = array_map(fn ($i) => UploadedFile::fake()->image("bukti{$i}.jpg"), range(1, 6));

        $this->post(route('laporan.store'), $this->pengaduan(['bukti_pelaporan' => $files]))
            ->assertSessionHasErrors('bukti_pelaporan');
    }

    public function test_file_pada_aspirasi_tetap_divalidasi(): void
    {
        $this->post(route('laporan.store'), [
            'klasifikasi' => 'Aspirasi',
            'judul_laporan' => 'Usulan',
            'uraian_kronologi' => 'Isi usulan.',
            'g-recaptcha-response' => 'token-uji',
            'bukti_pelaporan' => [UploadedFile::fake()->create('skrip.exe', 10)],
        ])->assertSessionHasErrors('bukti_pelaporan.0');
    }

    public function test_lacak_status_menampilkan_tiket(): void
    {
        $this->post(route('laporan.store'), $this->pengaduan());
        $kode = Ticket::first()->ticket_code;

        $this->get(route('lacak.status', ['kode_tiket' => strtolower($kode)]))
            ->assertOk()
            ->assertSee($kode);
    }

    public function test_lacak_status_kode_tidak_terdaftar(): void
    {
        $this->get(route('lacak.status', ['kode_tiket' => 'WBS-2026-XXXXX']))
            ->assertOk()
            ->assertSee('tidak terdaftar');
    }

    public function test_kirim_laporan_dibatasi_lima_kali_per_menit(): void
    {
        foreach (range(1, 5) as $i) {
            $this->post(route('laporan.store'), $this->pengaduan());
        }

        $this->post(route('laporan.store'), $this->pengaduan())->assertStatus(429);
    }
}
