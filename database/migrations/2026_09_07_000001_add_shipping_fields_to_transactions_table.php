<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('shipping_courier')->nullable()->after('payment_status');
            $table->string('tracking_number')->nullable()->after('shipping_courier');
            $table->string('shipping_status')->default('menunggu_diproses')->after('tracking_number');
            $table->date('estimated_delivery_start')->nullable()->after('shipping_status');
            $table->date('estimated_delivery_end')->nullable()->after('estimated_delivery_start');
            $table->timestamp('shipped_at')->nullable()->after('estimated_delivery_end');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            $table->timestamp('shipping_updated_at')->nullable()->after('delivered_at');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_courier',
                'tracking_number',
                'shipping_status',
                'estimated_delivery_start',
                'estimated_delivery_end',
                'shipped_at',
                'delivered_at',
                'shipping_updated_at',
            ]);
        });
    }
};
