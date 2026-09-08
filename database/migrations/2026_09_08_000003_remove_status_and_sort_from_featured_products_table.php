<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A featured product now simply references an existing product:
     * the "status" and "sort order" columns are no longer used.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('featured_products', 'is_featured')) {
            return;
        }

        Schema::table('featured_products', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'sort_order']);
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('featured_products', 'is_featured')) {
            return;
        }

        Schema::table('featured_products', function (Blueprint $table) {
            $table->boolean('is_featured')->default(true);
            $table->integer('sort_order')->default(0);
        });
    }
};
