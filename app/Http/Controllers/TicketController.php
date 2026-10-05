<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketProgress;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    protected $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    public function index()
    {
        $totalLaporan = Ticket::count();
        
        $laporanSelesai = TicketProgress::where('status', 'Selesai')->count();
        $laporanProses = TicketProgress::whereIn('status', ['Diverifikasi', 'Diproses'])->count();
        
        $persenSelesai = $totalLaporan > 0 ? round(($laporanSelesai / $totalLaporan) * 100) : 0;

        return view('home', compact('totalLaporan', 'laporanSelesai', 'laporanProses', 'persenSelesai'));
    }

    public function storeReport(Request $request)
    {
        $request->validate([
            'klasifikasi' => 'required',
            'jenis_pengaduan' => 'required',
            'uraian_kronologi' => 'required',
            'nama_terlapor' => 'required',
            'tanggal_kejadian' => 'required|date|before_or_equal:today',
            'bukti_pelaporan' => 'nullable|array|max:5',
            'bukti_pelaporan.*' => 'file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $tahun = date('Y');
        do {
            $ticketCode = 'WBS-' . $tahun . '-' . strtoupper(Str::random(5));
        } while (Ticket::where('ticket_code', $ticketCode)->exists());

        $pathBukti = null;
        if ($request->hasFile('bukti_pelaporan')) {
            $paths = [];
            foreach ($request->file('bukti_pelaporan') as $file) {
                $paths[] = $file->store('bukti_pelaporan', 'public');
            }
            $pathBukti = json_encode($paths); 
        }

        $ticket = Ticket::create([
            'ticket_code' => $ticketCode,
            'klasifikasi' => $request->klasifikasi,
            'nama_pelapor' => $request->nama_pelapor ?? 'Anonim', 
            'jenis_pengaduan' => $request->jenis_pengaduan,
            'uraian_kronologi' => $request->uraian_kronologi,
            'nama_terlapor' => $request->nama_terlapor,
            'tanggal_kejadian' => $request->tanggal_kejadian,
            'bukti_pelaporan' => $pathBukti,
        ]);

        TicketProgress::create([
            'ticket_id' => $ticket->id,
            'status' => 'Diterima',
            'catatan_admin' => 'Laporan telah berhasil masuk ke sistem WBS BPS Kabupaten Bantul dan menunggu verifikasi tim pengawas.',
        ]);

        try {
            $pesan_notifikasi = "🚨 *LAPORAN WBS BARU MASUK* 🚨\n\n"
                              . "Halo Admin NGADUBOSE, ada laporan pengaduan baru masuk dari Masyarakat (Publik).\n\n"
                              . "▪️️ *Kode Tiket:* " . $ticketCode . "\n"
                              . "▪️ *Klasifikasi:* " . $request->klasifikasi . "\n"
                              . "▪️ *Jenis Pengaduan:* " . ucfirst(str_replace('_', ' ', $request->jenis_pengaduan)) . "\n"
                              . "▪️ *Tanggal Kejadian:* " . $request->tanggal_kejadian . "\n\n"
                              . "Silakan segera login ke Panel Dashboard Admin BPS Bantul untuk memeriksa berkas laporan. Terima kasih.";

            $admins = User::where('is_admin', 1)
                          ->whereNotNull('nomor_hp')
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
            return redirect('/form-lapor'); 
        }

        return view('sukses-lapor');
    }

    public function lacakStatus(Request $request)
    {
        $ticketCode = $request->input('kode_tiket');

        if (!$ticketCode) {
            return view('lacak-status');
        }

        $ticket = Ticket::with(['progress' => function($query) {
            $query->latest();
        }])->where('ticket_code', $ticketCode)->first();

        if (!$ticket) {
            return view('lacak-status', [
                'ticketCode' => $ticketCode,
                'error' => 'Kode tiket yang Anda masukkan salah atau tidak terdaftar. Silakan coba lagi.'
            ]);
        }

        return view('lacak-status', [
            'ticketCode' => $ticketCode,
            'ticket' => $ticket
        ]);
    }
}