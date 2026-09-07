<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('transactions', 'payment_due_at')) {
                $table->timestamp('payment_due_at')->nullable()->after('paid_at');
            }
        });

        DB::table('transactions')
            ->whereNull('payment_due_at')
            ->where('payment_status', 'pending')
            ->orderBy('id')
            ->chunkById(500, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('transactions')->where('id', $row->id)->update([
                        'payment_due_at' => Carbon::parse($row->created_at)->addMinutes(15),
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('payment_due_at');
        });
    }
};