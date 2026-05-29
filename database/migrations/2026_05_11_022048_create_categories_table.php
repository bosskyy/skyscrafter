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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // ID Otomatis
            $table->string('name'); // Nama Kategori (contoh: Banner)
            $table->string('slug'); // URL ramah SEO (contoh: jasa-cetak-banner)
            $table->timestamps(); // Mencatat waktu dibuat & diedit
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
