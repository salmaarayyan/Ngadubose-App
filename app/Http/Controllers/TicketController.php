<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function storeReport(Request $request)
    {   
        $rules = [
            'klasifikasi'      => 'required|in:Pengaduan,Aspirasi,Informasi / Saran',
            'judul_laporan'    => 'required|string|max:255',
            'uraian_kronologi' => 'required|string',
            'g-recaptcha-response' => function ($attribute, $value, $fail) {
                $response = \Illuminate\Support\Facades\Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => env('RECAPTCHA_SECRET_KEY'),
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);

                if (!$response->json('success')) {
                    $fail('Verifikasi reCAPTCHA gagal. Silakan centang kotak verifikasi bahwa Anda bukan robot.');
                }
            },
        ];

        if ($request->klasifikasi === 'Pengaduan') {
            $rules['nama_terlapor']    = 'required|string|max:255';
            $rules['jenis_pengaduan']  = 'required|string';
            $rules['tanggal_kejadian'] = 'required|date';
            $rules['bukti_pelaporan'] = 'nullable|array|max:5';
            $rules['bukti_pelaporan.*'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'; 
        } else {
            $rules['nama_terlapor']    = 'nullable';
            $rules['jenis_pengaduan']  = 'nullable';
            $rules['tanggal_kejadian'] = 'nullable|date';
        }

        $validatedData = $request->validate($rules);

        $tahun = date('Y');
        do {
            $ticketCode = SystemConfig::TICKET_PREFIX->value . '-' . $tahun . '-' . strtoupper(Str::random(5));
        } while (Ticket::where('ticket_code', $ticketCode)->exists());

        
        $uploadedPaths = [];

        try {
            if ($request->hasFile('bukti_pelaporan')) {
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
            'klasifikasi' => $request->klasifikasi,
            'judul_laporan' => $request->judul_laporan,
            'nama_pelapor' => $request->nama_pelapor ?? SystemConfig::DEFAULT_PELAPOR->value, 
            'jenis_pengaduan' => $request->jenis_pengaduan,
            'uraian' => $request->uraian_kronologi,
            'nama_terlapor' => $request->nama_terlapor,
            'tanggal_kejadian' => $request->tanggal_kejadian,
            'bukti_laporan' => $uploadedPaths,
        ]);

        // DIPERBAIKI: Menggunakan 'id_ticket' sesuai struktur tabel database
        TicketProgress::create([
            'id_ticket' => $ticket->id,
            'status' => StatusLaporan::DITERIMA->value,
            'catatan_admin' => 'Laporan telah berhasil masuk ke sistem WBS BPS Kabupaten Bantul dan menunggu verifikasi tim pengawas.',
        ]);

        try {
            $pesan_notifikasi = "🚨 *LAPORAN WBS BARU MASUK* 🚨\n\n"
                    . "Halo Admin NGADUBOSE, ada laporan pengaduan baru masuk dari Masyarakat (Publik).\n\n"
                    . "▪ *Kode Tiket:* " . $ticketCode . "\n"
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
            return view('public.form-lapor'); 
        }

        return view('public.sukses-lapor');
    }

    public function lacakStatus(Request $request)
    {
        $ticketCode = $request->input('kode_tiket');

        if (!$ticketCode) {
            return view('public.lacak-status');
        }

        // DIPERBAIKI: Menggunakan relasi 'ticketProgress' sesuai yang ada di Model Ticket
        $ticket = Ticket::with(['ticketProgress' => function($query) {
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