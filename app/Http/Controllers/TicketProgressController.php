<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;

class TicketProgressController extends Controller
{
    public function adminDashboard()
    {
        $totalTickets = DB::table('tickets')->count();

        $statuses = DB::table('ticket_progress')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $jenisPengaduan = DB::table('tickets')
            ->select('jenis_pengaduan', DB::raw('count(*) as total'))
            ->groupBy('jenis_pengaduan')
            ->pluck('total', 'jenis_pengaduan');

        return view('admin.dashboard', [
            'totalTickets'       => $totalTickets,
            'statusDiterima'     => $statuses['Diterima'] ?? 0,
            'statusDiverifikasi' => $statuses['Diverifikasi'] ?? 0,
            'statusDiproses'     => $statuses['Diproses'] ?? 0,
            'statusSelesai'      => $statuses['Selesai'] ?? 0,
            'gratifikasi'        => $jenisPengaduan['gratifikasi'] ?? 0,
            'benturanKepentingan'=> $jenisPengaduan['benturan_kepentingan'] ?? 0,
            'korupsi'            => $jenisPengaduan['korupsi'] ?? 0,
            'pelanggaranAturan'  => $jenisPengaduan['pelanggaran_aturan'] ?? 0,
            'lainnya'            => $jenisPengaduan['lainnya'] ?? 0,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Diterima,Diverifikasi,Diproses,Selesai',
            'komentar_bps' => 'nullable|string'
        ]);

        $ticket = Ticket::findOrFail($id);
        
        $ticket->progress()->create([
            'status' => $request->status,
            'catatan_admin' => $request->komentar_bps ?? 'Status diperbarui oleh admin.', 
        ]);

        $ticket->update([
            'komentar_bps' => $request->komentar_bps
        ]);
        
        return redirect()->back()->with('success', 'Status tiket berhasil diperbarui!');
    }
}