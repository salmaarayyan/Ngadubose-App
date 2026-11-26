<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    protected $table = 'tickets';

    protected $fillable = [
        'ticket_code',
        'klasifikasi',
        'judul_laporan',
        'nama_pelapor',
        'jenis_pengaduan',
        'uraian',
        'nama_terlapor',
        'tanggal_kejadian',
        'bukti_laporan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kejadian' => 'date',
            // Disimpan sebagai JSON array berisi ID file Google Drive.
            'bukti_laporan' => 'array',
        ];
    }

    public function ticketProgress(): HasMany
    {
        return $this->hasMany(TicketProgress::class, 'id_ticket');
    }
}
