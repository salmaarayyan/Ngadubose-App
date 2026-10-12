<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Implementasi relasi N:M "Memproses" tickets <-> users: riwayat pembaruan status laporan.
        Schema::create('ticket_progress', function (Blueprint $table) {
            $table->id();
            // cascadeOnDelete hanya terpicu saat ticket dihapus permanen; soft delete tidak memicu cascade.
            $table->foreignId('id_ticket')->constrained('tickets')->cascadeOnDelete();
            // Nullable: entri "diterima" dibuat otomatis saat pelapor (tanpa login) mengirim laporan.
            // nullOnDelete: riwayat tetap tersimpan walaupun akun admin dihapus.
            $table->foreignId('id_user')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['diterima', 'diverifikasi', 'diproses', 'selesai']);
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_progress');
    }
};
