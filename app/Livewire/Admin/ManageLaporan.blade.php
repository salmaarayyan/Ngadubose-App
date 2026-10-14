<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ticket;
use App\Models\TicketProgress;
use Illuminate\Support\Facades\Auth;

class ManageTicket extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    
    // Properti untuk Modal Detail Laporan
    public $isDetailOpen = false;
    public $selectedTicket = null;

    // Properti untuk Modal Status & Catatan Admin
    public $isStatusModalOpen = false;
    public $statusTicketId = null;
    public $newStatus = null;
    public $catatanAdmin = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    // Fungsi menampilkan modal detail laporan
    public function showDetail($ticketId)
    {
        $this->selectedTicket = Ticket::findOrFail($ticketId);
        $this->isDetailOpen = true;
    }

    // Fungsi menutup modal detail laporan
    public function closeDetail()
    {
        $this->isDetailOpen = false;
        $this->selectedTicket = null;
    }

    // Fungsi untuk membuka modal catatan admin saat dropdown status diubah
    public function openStatusModal($ticketId, $status)
    {
        $this->statusTicketId = $ticketId;
        $this->newStatus = $status;
        $this->catatanAdmin = '';
        $this->isStatusModalOpen = true;
    }

    // Fungsi untuk menyimpan perubahan status dan catatan admin ke database
    public function saveStatusProgress()
    {
        $this->validate([
            'catatanAdmin' => 'required|string|min:3',
        ], [
            'catatanAdmin.required' => 'Catatan admin wajib diisi.',
            'catatanAdmin.min' => 'Catatan admin minimal 3 karakter.',
        ]);

        // 1. Update status di tabel utama tickets
        $ticket = Ticket::findOrFail($this->statusTicketId);
        $ticket->update([
            'status' => $this->newStatus
        ]);

        // 2. Simpan riwayat ke tabel ticket_progres
        TicketProgress::create([
            'id_ticket' => $ticket->id,
            'id_user' => Auth::id(), // Admin yang sedang login
            'status' => $this->newStatus,
            'catatan_admin' => $this->catatanAdmin,
        ]);

        $this->isStatusModalOpen = false;
        session()->flash('success', 'Status laporan dan catatan admin berhasil diperbarui.');
    }

    public function render()
    {
        $tickets = Ticket::query()
            ->when($this->search, function ($query) {
                $query->where('ticket_code', 'like', '%' . $this->search . '%')
                      ->orWhere('judul_laporan', 'like', '%' . $this->search . '%')
                      ->orWhere('nama_pelapor', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.manage-laporan', [
            'tickets' => $tickets
        ])->layout('layouts.admin');
    }
}