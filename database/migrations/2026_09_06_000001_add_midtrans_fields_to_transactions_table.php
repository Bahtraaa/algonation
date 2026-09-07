<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table): void {
            if (! Schema::hasColumn('transactions', 'midtrans_order_id')) {
                $table->string('midtrans_order_id')->nullable()->unique()->after('payment_method');
            }
            if (! Schema::hasColumn('transactions', 'midtrans_snap_token')) {
                $table->string('midtrans_snap_token')->nullable()->after('midtrans_order_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table): void {
            if (Schema::hasColumn('transactions', 'midtrans_order_id')) {
                $table->dropUnique(['midtrans_order_id']);
            }

            $columns = array_filter([
                Schema::hasColumn('transactions', 'midtrans_order_id') ? 'midtrans_order_id' : null,
                Schema::hasColumn('transactions', 'midtrans_snap_token') ? 'midtrans_snap_token' : null,
            ]);

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};