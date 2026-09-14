<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Alamat yang dipilih saat checkout (relasi, boleh null agar histori tetap aman).
            $table->foreignId('address_id')->nullable()->after('user_id')
                ->constrained('addresses')->nullOnDelete();

            // Snapshot alamat — immutable, tidak berubah walau user mengedit alamat akun.
            $table->string('shipping_name', 255)->nullable()->after('shipping_address');
            $table->string('shipping_phone', 20)->nullable()->after('shipping_name');
            $table->string('shipping_country', 120)->nullable()->after('shipping_phone');
            $table->string('shipping_province', 120)->nullable()->after('shipping_country');
            $table->string('shipping_city', 120)->nullable()->after('shipping_province');
            $table->string('shipping_district', 120)->nullable()->after('shipping_city');
            $table->string('shipping_postal_code', 10)->nullable()->after('shipping_district');
            $table->string('shipping_note', 500)->nullable()->after('shipping_postal_code');
            $table->string('shipping_label', 50)->nullable()->after('shipping_note');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('address_id');
            $table->dropColumn([
                'shipping_name',
                'shipping_phone',
                'shipping_country',
                'shipping_province',
                'shipping_city',
                'shipping_district',
                'shipping_postal_code',
                'shipping_note',
                'shipping_label',
            ]);
        });
    }
};
