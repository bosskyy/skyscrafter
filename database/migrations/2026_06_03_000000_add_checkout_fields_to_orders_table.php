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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('checkout_id')->nullable()->index();
            $table->string('document_file')->nullable();
            $table->text('options')->nullable();
            $table->string('delivery_method')->nullable();
            $table->text('delivery_address')->nullable();
            $table->text('additional_note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Down migrations intentionally omitted (SQLite drop column limitations).
    }
};
