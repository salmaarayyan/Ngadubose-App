<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Klasifikasi Aspirasi dan Informasi / Saran tidak mewajibkan nama terlapor,
     * jenis pengaduan, dan tanggal kejadian. Ketiganya hanya wajib untuk klasifikasi
     * Pengaduan, dan kewajiban itu divalidasi di aplikasi.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('nama_terlapor')->nullable()->change();
            $table->enum('jenis_pengaduan', ['gratifikasi', 'benturan_kepentingan', 'korupsi', 'pelanggaran_aturan', 'lainnya'])->nullable()->change();
            $table->date('tanggal_kejadian')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('nama_terlapor')->nullable(false)->change();
            $table->enum('jenis_pengaduan', ['gratifikasi', 'benturan_kepentingan', 'korupsi', 'pelanggaran_aturan', 'lainnya'])->nullable(false)->change();
            $table->date('tanggal_kejadian')->nullable(false)->change();
        });
    }
};
