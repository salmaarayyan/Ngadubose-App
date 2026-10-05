<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code')->unique();
            $table->string('klasifikasi');
            $table->string('nama_pelapor')->default('Anonim');
            $table->enum('jenis_pengaduan',  ['gratifikasi', 'benturan_kepentingan', 'korupsi', 'pelanggaran_aturan', 'lainnya']);
            $table->text('uraian_kronologi');
            $table->string('nama_terlapor');
            $table->date('tanggal_kejadian');
            $table->text('bukti_pelaporan')->nullable();
            $table->text('komentar_bps')->nullable();
            $table->enum('status', ['Diterima', 'Diverifikasi', 'Diproses', 'Selesai'])->default('Diterima');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
