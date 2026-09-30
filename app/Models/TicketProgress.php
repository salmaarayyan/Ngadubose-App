<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketProgress extends Model
{
    use HasFactory;
    
    protected $table = 'ticket_progress';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'status',
        'catatan_admin',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}