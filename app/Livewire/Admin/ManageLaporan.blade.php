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
    public $jenisPengaduanFilter = '';
    public $yearFilter = '';
    
    public $isDetailOpen = false;
    public $selectedTicket = null;

    public $isStatusModalOpen = false;
    public $statusTicketId = null;
    public $newStatus = null;
    public $catatanAdmin = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingJenisPengaduanFilter() { $this->resetPage(); }
    public function updatingYearFilter() { $this->resetPage(); }

    public function showDetail($ticketId)
    {
        $this->selectedTicket = Ticket::findOrFail($ticketId);
        $this->isDetailOpen = true;
    }

    public function closeDetail()
    {
        $this->isDetailOpen = false;
        $this->selectedTicket = null;
    }

    public function openStatusModal($ticketId, $status)
    {
        $this->statusTicketId = $ticketId;
        $this->newStatus = $status;
        $this->catatanAdmin = '';
        $this->isStatusModalOpen = true;
    }

    public function saveStatusProgress()
    {
        $this->validate([
            'catatanAdmin' => 'required|string|min:3',
        ]);

        $ticket = Ticket::findOrFail($this->statusTicketId);
        $ticket->update(['status' => $this->newStatus]);

        TicketProgress::create([
            'id_ticket' => $ticket->id,
            'id_user' => Auth::id(),
            'status' => $this->newStatus,
            'catatan_admin' => $this->catatanAdmin,
        ]);

        $this->isStatusModalOpen = false;
        session()->flash('success', 'Status laporan dan catatan admin berhasil diperbarui.');
    }

    public function render()
    {
        // 1. Query dasar untuk filter tahun & jenis pengaduan (berlaku untuk diagram/statistik)
        $baseQuery = Ticket::query()
            ->when($this->yearFilter, function ($query) {
                $query->whereYear('created_at', $this->yearFilter);
            })
            ->when($this->jenisPengaduanFilter, function ($query) {
                $query->where('jenis_pengaduan', $this->jenisPengaduanFilter);
            });

        // 2. Query untuk tabel (ditambah pencarian dan status)
        $tickets = (clone $baseQuery)
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

        // 3. Hitung data untuk Diagram Status Penanganan (Proporsi Status)
        $statusCounts = [
            'Diterima' => (clone $baseQuery)->where('status', 'Diterima')->count(),
            'Diverifikasi' => (clone $baseQuery)->where('status', 'Diverifikasi')->count(),
            'Diproses' => (clone $baseQuery)->where('status', 'Diproses')->count(),
            'Selesai' => (clone $baseQuery)->where('status', 'Selesai')->count(),
        ];

        // 4. Hitung data untuk Diagram Kategori Pelanggaran (Jumlah Kategori)
        $kategoriCounts = [
            'gratifikasi' => (clone $baseQuery)->where('jenis_pengaduan', 'gratifikasi')->count(),
            'benturan_kepentingan' => (clone $baseQuery)->where('jenis_pengaduan', 'benturan_kepentingan')->count(),
            'korupsi' => (clone $baseQuery)->where('jenis_pengaduan', 'korupsi')->count(),
            'pelanggaran_aturan' => (clone $baseQuery)->where('jenis_pengaduan', 'pelanggaran_aturan')->count(),
            'lainnya' => (clone $baseQuery)->where('jenis_pengaduan', 'lainnya')->count(),
        ];

        // Ambil daftar tahun unik untuk opsi select filter
        $availableYears = Ticket::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('livewire.admin.manage-laporan', [
            'tickets' => $tickets,
            'availableYears' => $availableYears,
            'statusCounts' => $statusCounts,       // Kirim data status ke view
            'kategoriCounts' => $kategoriCounts   // Kirim data kategori ke view
        ])->layout('layouts.admin');
    }
}