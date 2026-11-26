<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreReportRequest;
use App\Models\Ticket;
use App\Models\TicketProgress;
use App\Models\User;
use App\Services\WhatsAppService;
use App\Services\GoogleDriveService;
use App\Enums\StatusLaporan;
use App\Enums\SystemConfig;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    protected $whatsAppService;
    protected $googleDriveService;

    public function __construct(WhatsAppService $whatsAppService, GoogleDriveService $googleDriveService)
    {
        $this->whatsAppService = $whatsAppService;
        $this->googleDriveService = $googleDriveService;
    }

    public function storeReport(StoreReportRequest $request)
    {
        $data = $request->validated();
        $isPengaduan = $request->isPengaduan();

        $tahun = date('Y');
        do {
            $ticketCode = SystemConfig::TICKET_PREFIX->value . '-' . $tahun . '-' . strtoupper(Str::random(5));
        } while (Ticket::where('ticket_code', $ticketCode)->exists());

        
        $uploadedPaths = [];

        try {
            // Bukti pendukung hanya diterima untuk klasifikasi Pengaduan.
            if ($isPengaduan && $request->hasFile('bukti_pelaporan')) {
                $uploadedPaths = $this->googleDriveService
                    ->uploadMultipleFiles(
                        $request->file('bukti_pelaporan'),
                        $ticketCode
                    );
            }
        } catch (\Throwable $e) {
            Log::error('Upload bukti laporan ke Google Drive gagal', [
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'bukti_pelaporan' =>
                        'Bukti laporan gagal diunggah. Silakan coba kembali.',
                ]);
        }


        $ticket = Ticket::create([
            'ticket_code' => $ticketCode,
            'klasifikasi' => $data['klasifikasi'],
            'judul_laporan' => $data['judul_laporan'],
            'nama_pelapor' => $data['nama_pelapor'] ?? SystemConfig::DEFAULT_PELAPOR->value,
            'jenis_pengaduan' => $isPengaduan ? $data['jenis_pengaduan'] : null,
            'uraian' => $data['uraian_kronologi'],
            'nama_terlapor' => $isPengaduan ? $data['nama_terlapor'] : null,
            'tanggal_kejadian' => $isPengaduan ? $data['tanggal_kejadian'] : null,
            'bukti_laporan' => $uploadedPaths,
        ]);

        TicketProgress::create([
            'id_ticket' => $ticket->id,
            'status' => StatusLaporan::DITERIMA->value,
            'catatan_admin' => 'Laporan telah berhasil masuk ke sistem WBS BPS Kabupaten Bantul dan menunggu verifikasi tim pengawas.',
        ]);

        try {
            $pesan_notifikasi = "🚨 *LAPORAN WBS BARU MASUK* 🚨\n\n"
                    . "Halo Admin NGADUBOSE, ada laporan pengaduan baru masuk dari Masyarakat (Publik).\n\n"
                    . "▪ *Kode Tiket:* " . $ticketCode . "\n"
                    . "▪️ *Klasifikasi:* " . $data['klasifikasi'] . "\n"
                    . "▪️ *Jenis Pengaduan:* " . ($isPengaduan ? ucfirst(str_replace('_', ' ', $data['jenis_pengaduan'])) : '-') . "\n"
                    . "▪️ *Tanggal Kejadian:* " . ($isPengaduan ? $data['tanggal_kejadian'] : '-') . "\n\n"
                    . "Silakan segera login ke Panel Dashboard Admin BPS Bantul untuk memeriksa berkas laporan. Terima kasih.";

            $admins = User::whereNotNull('nomor_hp')
                         ->where('nomor_hp', '!=', '')
                         ->get();

            foreach ($admins as $admin) {
                $this->whatsAppService->kirimNotifikasi($admin->nomor_hp, $pesan_notifikasi);
            }

        } catch (\Exception $e) {
            Log::error('Gagal kirim WA ke Admin disebabkan: ' . $e->getMessage());
        }

        return redirect()->route('laporan.sukses')->with('kode_tiket', $ticketCode);
    }

    public function sukses()
    {
        if (!session('kode_tiket')) {
            return view('public.form-lapor'); 
        }

        return view('public.sukses-lapor');
    }

    public function lacakStatus(Request $request)
    {
        $ticketCode = strtoupper(trim((string) $request->input('kode_tiket')));

        if (!$ticketCode) {
            return view('public.lacak-status');
        }

        // Kode tiket berformat WBS-TAHUN-XXXXX; input yang terlalu panjang langsung ditolak.
        $ticket = strlen($ticketCode) > 20 ? null : Ticket::with(['ticketProgress' => function($query) {
            $query->latest();
        }])->where('ticket_code', $ticketCode)->first();

        if (!$ticket) {
            return view('public.lacak-status', [
                'ticketCode' => $ticketCode,
                'error' => 'Kode tiket yang Anda masukkan salah atau tidak terdaftar. Silakan coba lagi.'
            ]);
        }

        return view('public.lacak-status', [
            'ticketCode' => $ticketCode,
            'ticket' => $ticket
        ]);
    }

    public function create()
    {
        return view('public.form-lapor'); 
    }
}