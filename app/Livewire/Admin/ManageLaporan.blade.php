<?php

namespace App\Http\Livewire\Admin;

use App\Models\Ticket;
use App\Models\TicketProgress;
use App\Enums\StatusLaporan;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ManageTicket extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    #[Url(except: '')]
    public $statusFilter = '';

    public $selectedTicket = null;
    public $isDetailOpen = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $tickets = Ticket::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('ticket_code', 'like', '%' . $this->search . '%')
                      ->orWhere('judul_laporan', 'like', '%' . $this->search . '%')
                      ->orWhere('nama_pelapor', 'like', '%' . $this->search . '%')
                      ->orWhere('nama_terlapor', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.manage-laporan', [
            'tickets' => $tickets,
            'statusOptions' => StatusLaporan::cases(),
        ])->layout('layouts.admin');
    }

    public function showDetail($id)
    {
        $this->selectedTicket = Ticket::with('progress')->findOrFail($id);
        $this->isDetailOpen = true;
    }

    public function closeDetail()
    {
        $this->isDetailOpen = false;
        $this->selectedTicket = null;
    }

    public function updateStatus($id, string $newStatusValue)
    {
        $ticket = Ticket::findOrFail($id);
        
        $statusEnum = StatusLaporan::tryFrom($newStatusValue);
        if (!$statusEnum) {
            return;
        }

        $ticket->update(['status' => $statusEnum->value]);

        TicketProgress::create([
            'ticket_id' => $ticket->id,
            'status' => $statusEnum->value,
            'catatan_admin' => 'Status laporan berhasil diperbarui oleh admin menjadi: ' . $statusEnum->value,
        ]);

        $this->selectedTicket = Ticket::with('progress')->findOrFail($id);

        session()->flash('success', 'Status tiket ' . $ticket->ticket_code . ' berhasil diperbarui!');
    }
}