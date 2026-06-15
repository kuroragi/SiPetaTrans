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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();

            $table->string('pengusul');
            $table->string('email_pengusul');
            $table->date('tanggal');
            $table->string('jenis_permintaan');
            $table->integer('jumlah');
            $table->text('lokasi');
            $table->json('coordinates')->nullable();
            $table->bigInteger('perkiraan_anggaran')->nullable();
            $table->string('foto');
            $table->string('arsip_surat');
            $table->text('tindak_lanjut')->nullable();
            
            $table->enum('status', ['pending', 'ditindak lanjuti', 'ditolak', 'selesai'])->default('pending');
            $table->text('keterangan_admin')->nullable();

            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
