<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'ticket_code',
        'nama_pelapor',
        'status',
        'jenis_pengaduan',
        'uraian_kronologi',
        'klasifikasi',
        'tanggal_kejadian',
        'bukti_pelaporan',
        'judul_laporan',
    ];
     protected $casts = [
        'bukti_pelaporan' => 'array',
        'tanggal_kejadian' => 'date',
    ];

    public function progress()
    {
        return $this->hasMany(TicketProgress::class, 'ticket_id');
    }
    public function latestProgress()
    {
        return $this->hasOne(TicketProgress::class, 'ticket_id')->latestOfMany();
    }
}