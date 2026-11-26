<?php

namespace Tests\Feature;

use App\Livewire\Admin\ManageLaporan;
use App\Livewire\Admin\ManageUser;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
        $this->actingAs($this->admin);
    }

    public function test_halaman_admin_dapat_dibuka(): void
    {
        $this->get(route('admin.dashboard'))->assertOk();
        $this->get(route('manage-user'))->assertOk();
        $this->get(route('manage-laporan'))->assertOk();
    }

    public function test_tambah_admin_baru_dengan_password_terenkripsi(): void
    {
        Livewire::test(ManageUser::class)
            ->call('create')
            ->set('nama', 'Admin Baru')
            ->set('email', 'Baru@Contoh.com')
            ->set('nomor_hp', '0812-3456-7890')
            ->set('password', 'rahasia123')
            ->call('save')
            ->assertHasNoErrors();

        $user = User::where('email', 'baru@contoh.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('081234567890', $user->nomor_hp);
        $this->assertNotSame('rahasia123', $user->password);
        $this->assertTrue(Hash::check('rahasia123', $user->password));
    }

    public function test_tambah_admin_ditolak_jika_email_sudah_dipakai(): void
    {
        Livewire::test(ManageUser::class)
            ->call('create')
            ->set('nama', 'Duplikat')
            ->set('email', $this->admin->email)
            ->set('nomor_hp', '081200000000')
            ->set('password', 'rahasia123')
            ->call('save')
            ->assertHasErrors(['email' => 'unique']);
    }

    public function test_edit_admin(): void
    {
        $user = User::factory()->create(['nomor_hp' => '081111111111']);

        Livewire::test(ManageUser::class)
            ->call('edit', $user->id)
            ->set('nama', 'Nama Diubah')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Nama Diubah', $user->fresh()->nama);
    }

    public function test_hapus_admin_lain(): void
    {
        $user = User::factory()->create();

        Livewire::test(ManageUser::class)->call('delete', $user->id);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_tidak_dapat_menghapus_akunnya_sendiri(): void
    {
        Livewire::test(ManageUser::class)->call('delete', $this->admin->id);

        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    private function buatTiket(): Ticket
    {
        return Ticket::create([
            'ticket_code' => 'WBS-2026-ABCDE',
            'klasifikasi' => 'Pengaduan',
            'judul_laporan' => 'Uji',
            'nama_pelapor' => 'Anonim',
            'jenis_pengaduan' => 'korupsi',
            'uraian' => 'Uji',
            'nama_terlapor' => 'Pegawai B',
            'tanggal_kejadian' => now()->toDateString(),
        ]);
    }

    public function test_ubah_status_laporan_dengan_catatan(): void
    {
        $ticket = $this->buatTiket();

        Livewire::test(ManageLaporan::class)
            ->call('openStatusModal', $ticket->id, 'diverifikasi')
            ->set('catatanAdmin', 'Laporan sudah diverifikasi.')
            ->call('saveStatusProgress')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('tickets', ['id' => $ticket->id, 'status' => 'diverifikasi']);
        $this->assertDatabaseHas('ticket_progress', [
            'id_ticket' => $ticket->id,
            'id_user' => $this->admin->id,
            'status' => 'diverifikasi',
        ]);
    }

    public function test_status_laporan_di_luar_pilihan_ditolak(): void
    {
        $ticket = $this->buatTiket();

        Livewire::test(ManageLaporan::class)
            ->call('openStatusModal', $ticket->id, 'diverifikasi')
            ->set('newStatus', 'dihapus')
            ->set('catatanAdmin', 'Catatan')
            ->call('saveStatusProgress')
            ->assertHasErrors(['newStatus']);

        $this->assertDatabaseHas('tickets', ['id' => $ticket->id, 'status' => 'diterima']);
    }
}
