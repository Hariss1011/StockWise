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
        Schema::create('stock_outs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('products')
                ->restrictOnDelete();

            $table->string('reference_number', 100)->nullable();
            $table->unsignedInteger('quantity');
            $table->string('recipient', 150)->nullable();
            $table->date('transaction_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('transaction_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_outs');
    }
};
