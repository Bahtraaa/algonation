<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('normal_price', 12, 2);
            $table->decimal('sale_price', 12, 2);
            $table->decimal('discount_percentage', 5, 2);
            $table->unsignedInteger('stock');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->enum('status', ['scheduled', 'active', 'expired', 'inactive'])->default('scheduled');
            $table->timestamps();
            $table->index(['status', 'start_at', 'end_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flash_sales');
    }
};
