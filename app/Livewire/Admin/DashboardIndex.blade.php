<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Ticket;
use App\Enums\StatusLaporan;
use App\Enums\JenisTicket;

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

            'gratifikasi' => Ticket::where('jenis_pengaduan', JenisTicket::GRATIFIKASI)->count(),
            'benturanKepentingan' => Ticket::where('jenis_pengaduan', JenisTicket::BENTURAN_KEPENTINGAN)->count(),
            'korupsi' => Ticket::where('jenis_pengaduan', JenisPengaduni::KORUPSI ?? JenisTicket::KORUPSI)->count(),
            'pelanggaranAturan' => Ticket::where('jenis_pengaduan', JenisTicket::PELANGGARAN_ATURAN)->count(),
            'lainnya' => Ticket::where('jenis_pengaduan', JenisTicket::LAINNYA)->count(),
        ])->layout('layouts.admin');
    }
}