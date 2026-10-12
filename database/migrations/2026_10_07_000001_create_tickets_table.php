<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            // Format WBS-Tahun-XXXX, dipakai pelapor untuk melacak status laporan.
            $table->string('ticket_code')->unique();
            $table->string('klasifikasi')->nullable();
            $table->string('judul_laporan');
            // Nullable: pelapor boleh anonim.
            $table->string('nama_pelapor')->nullable();
            $table->enum('jenis_pengaduan', ['gratifikasi', 'benturan_kepentingan', 'korupsi', 'pelanggaran_aturan', 'lainnya']);
            $table->text('uraian');
            $table->string('nama_terlapor');
            $table->date('tanggal_kejadian');
            // Daftar ID file Google Drive (maks. 5 file) dalam format JSON array.
            $table->text('bukti_laporan')->nullable();
            $table->enum('status', ['diterima', 'diverifikasi', 'diproses', 'selesai'])->default('diterima');
            // created_at juga dipakai untuk filter periode dan perhitungan SLA tiga hari kerja.
            $table->timestamps();
            // Laporan WBS adalah bukti pengaduan, tidak boleh hilang permanen.
            $table->softDeletes();

            $table->index('status');
            $table->index('jenis_pengaduan');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
