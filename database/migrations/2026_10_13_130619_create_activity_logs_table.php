<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // ID pengguna yang melakukan aktivitas
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Salinan nama pelaku agar histori tetap terbaca
            // meskipun akun pengguna dihapus
            $table->string('user_name');

            // Contoh: created, updated, deleted
            $table->string('action', 30);

            // Contoh: Produk, Supplier, Stok Masuk
            $table->string('module', 60);

            // Ringkasan aktivitas yang mudah dibaca
            $table->text('description');

            // Identitas objek yang berubah
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();

            // Rincian perubahan, jika tersedia
            $table->json('properties')->nullable();

            // Informasi teknis permintaan
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['module', 'created_at']);
            $table->index(['action', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
