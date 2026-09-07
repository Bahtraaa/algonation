<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Store origin + global shipping configuration (single row).
        Schema::create('shipping_settings', function (Blueprint $table) {
            $table->id();
            $table->string('origin_country')->default('Indonesia');
            $table->string('origin_city')->nullable();
            $table->decimal('origin_latitude', 10, 7)->nullable();
            $table->decimal('origin_longitude', 10, 7)->nullable();
            $table->enum('routing_provider', ['none', 'osrm', 'google', 'here', 'mapbox'])->default('none');
            $table->boolean('enable_routing')->default(false);
            $table->timestamps();
        });

        // Shipping zones used for domestic distance-based pricing.
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('min_distance_km')->default(0);
            $table->unsignedInteger('max_distance_km')->nullable();
            $table->decimal('rate_per_kg', 12, 2)->default(0);
            $table->decimal('min_charge', 12, 2)->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // International regions grouping (Asia, Europe, ...).
        Schema::create('international_regions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('rate_per_kg', 12, 2)->default(0);
            $table->decimal('min_charge', 12, 2)->default(0);
            $table->timestamps();
        });

        // Countries mapped to an international region with rates.
        Schema::create('shipping_countries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained('international_regions')->cascadeOnDelete();
            $table->string('country')->unique();
            $table->decimal('rate_per_kg', 12, 2)->nullable();
            $table->decimal('min_charge', 12, 2)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Shipping couriers / services.
        Schema::create('shipping_couriers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['domestic', 'international']);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_couriers');
        Schema::dropIfExists('shipping_countries');
        Schema::dropIfExists('international_regions');
        Schema::dropIfExists('shipping_zones');
        Schema::dropIfExists('shipping_settings');
    }
};
