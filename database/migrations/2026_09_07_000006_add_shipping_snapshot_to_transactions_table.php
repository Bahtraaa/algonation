<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('shipping_type')->nullable()->after('shipping_address');
            $table->string('origin_country')->nullable()->after('shipping_type');
            $table->string('destination_country')->nullable()->after('origin_country');
            $table->string('destination_city')->nullable()->after('destination_country');
            $table->string('destination_state')->nullable()->after('destination_city');
            $table->string('destination_postal_code')->nullable()->after('destination_state');
            $table->decimal('shipping_distance', 12, 2)->nullable()->after('destination_postal_code');
            $table->decimal('actual_weight', 12, 2)->nullable()->after('shipping_distance');
            $table->decimal('volumetric_weight', 12, 2)->nullable()->after('actual_weight');
            $table->decimal('billable_weight', 12, 2)->nullable()->after('volumetric_weight');
            $table->string('shipping_zone')->nullable()->after('billable_weight');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_type',
                'origin_country',
                'destination_country',
                'destination_city',
                'destination_state',
                'destination_postal_code',
                'shipping_distance',
                'actual_weight',
                'volumetric_weight',
                'billable_weight',
                'shipping_zone',
            ]);
        });
    }
};
