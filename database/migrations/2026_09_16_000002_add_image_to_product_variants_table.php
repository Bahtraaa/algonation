<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Upload gambar per variant: tambah kolom image (path storage, nullable)
     * pada product_variants. File disimpan di storage/app/public/product-variants,
     * database hanya menyimpan path-nya.
     */
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (! Schema::hasColumn('product_variants', 'image')) {
                $table->string('image')->nullable()->after('price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (Schema::hasColumn('product_variants', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
};
