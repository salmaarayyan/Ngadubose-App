<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Ticket;
use App\Enums\StatusLaporan;
use App\Enums\JenisPengaduan;

class DashboardIndex extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard-index', [
            'totalTickets' => Ticket::count(),
            'statusDiterima' => Ticket::where('status', StatusLaporan::DITERIMA)->count(),
            'statusDiverifikasi' => Ticket::where('status', StatusLaporan::DIVERIFIKASI)->count(),
            'statusDiproses' => Ticket::where('status', StatusLaporan::DIPROSES)->count(),
            'statusSelesai' => Ticket::where('status', StatusLaporan::SELESAI)->count(),

            'gratifikasi' => Ticket::where('jenis_pengaduan', JenisPengaduan::GRATIFIKASI)->count(),
            'benturanKepentingan' => Ticket::where('jenis_pengaduan', JenisPengaduan::BENTURAN_KEPENTINGAN)->count(),
            'korupsi' => Ticket::where('jenis_pengaduan', JenisPengaduan::KORUPSI)->count(),
            'pelanggaranAturan' => Ticket::where('jenis_pengaduan', JenisPengaduan::PELANGGARAN_ATURAN)->count(),
            'lainnya' => Ticket::where('jenis_pengaduan', JenisPengaduan::LAINNYA)->count(),
        ])->layout('layouts.admin');
    }
}