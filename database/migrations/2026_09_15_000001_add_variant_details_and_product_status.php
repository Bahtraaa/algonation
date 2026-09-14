<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pemisahan Produk vs Produk Variant:
     * - product_variants: tambah color, size, sku (nullable, BC dengan kolom name lama).
     * - products: tambah status (active/inactive, default active).
     */
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (! Schema::hasColumn('product_variants', 'color')) {
                $table->string('color', 50)->nullable()->after('product_id');
            }
            if (! Schema::hasColumn('product_variants', 'size')) {
                $table->string('size', 20)->nullable()->after('color');
            }
            if (! Schema::hasColumn('product_variants', 'sku')) {
                $table->string('sku', 100)->nullable()->after('size');
            }
        });

        // Index unik untuk SKU (hanya bila belum ada).
        try {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->unique('sku', 'product_variants_sku_unique');
            });
        } catch (\Throwable $e) {
            // Index mungkin sudah ada — abaikan.
        }

        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'status')) {
                $table->string('status', 20)->default('active')->after('category');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            try {
                $table->dropUnique('product_variants_sku_unique');
            } catch (\Throwable $e) {
                // Abaikan bila index tidak ada.
            }
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('product_variants', 'color')) {
                $columns[] = 'color';
            }
            if (Schema::hasColumn('product_variants', 'size')) {
                $columns[] = 'size';
            }
            if (Schema::hasColumn('product_variants', 'sku')) {
                $columns[] = 'sku';
            }
            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
