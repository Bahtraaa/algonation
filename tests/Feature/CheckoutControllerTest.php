<?php

use App\Http\Controllers\CheckoutController;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpKernel\Exception\HttpException;

uses(RefreshDatabase::class);

it('denies receipt access when no user is authenticated', function () {
    $user = User::factory()->create();
    $transaction = Transaction::create([
        'user_id' => $user->id,
        'total_price' => 100000,
        'shipping_cost' => 10000,
        'payment_method' => 'midtrans',
        'shipping_address' => 'Jl. Contoh No. 1',
        'status' => 'pending',
    ]);

    expect(fn () => app(CheckoutController::class)->receipt($transaction))
        ->toThrow(HttpException::class);
});

it('only allows cancellation before the package is handed to the courier', function () {
    $user = User::factory()->create();
    $statuses = [
        'menunggu_diproses' => true,
        'pesanan_diproses' => true,
        'dikemas' => true,
        'diserahkan_ke_kurir' => false,
        'dalam_perjalanan' => false,
        'tiba_di_kota_tujuan' => false,
        'sedang_diantar' => false,
        'pesanan_diterima' => false,
        'pengiriman_gagal' => false,
        'dibatalkan' => false,
    ];

    foreach ($statuses as $shippingStatus => $canCancel) {
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'total_price' => 100000,
            'shipping_cost' => 10000,
            'payment_method' => 'midtrans',
            'shipping_address' => 'Jl. Contoh No. 1',
            'status' => $canCancel ? 'pending' : 'processing',
            'shipping_status' => $shippingStatus,
        ]);

        expect($transaction->can_be_cancelled)->toBe($canCancel);
    }
});

it('rejects a cancellation request after the package is handed to the courier', function () {
    /** @var \Tests\TestCase $this */
    $user = User::factory()->create();
    $transaction = Transaction::create([
        'user_id' => $user->id,
        'total_price' => 100000,
        'shipping_cost' => 10000,
        'payment_method' => 'midtrans',
        'shipping_address' => 'Jl. Contoh No. 1',
        'status' => 'processing',
        'shipping_status' => 'diserahkan_ke_kurir',
    ]);

    $this->actingAs($user)
        ->deleteJson(route('orders.cancel', $transaction))
        ->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'Pesanan tidak dapat dibatalkan karena paket sudah diserahkan kepada kurir.',
        ]);

    expect($transaction->refresh()->status)->toBe('processing');
});

it('returns true for isPayable on a pending order within the 15 minute deadline', function () {
    $user = User::factory()->create();
    $transaction = Transaction::create([
        'user_id' => $user->id,
        'total_price' => 100000,
        'shipping_cost' => 10000,
        'payment_method' => 'midtrans',
        'shipping_address' => 'Jl. Contoh No. 1',
        'status' => 'pending_payment',
        'payment_status' => 'pending',
        'payment_due_at' => now()->addMinutes(10),
    ]);

    expect($transaction->isPayable())->toBeTrue();
});

it('returns false for isPayable when payment is cancelled', function () {
    $user = User::factory()->create();
    $transaction = Transaction::create([
        'user_id' => $user->id,
        'total_price' => 100000,
        'shipping_cost' => 10000,
        'payment_method' => 'midtrans',
        'shipping_address' => 'Jl. Contoh No. 1',
        'status' => 'cancelled',
        'payment_status' => 'cancelled',
        'payment_due_at' => now()->addMinutes(10),
    ]);

    expect($transaction->isPayable())->toBeFalse();
});

it('returns false for isPayable when payment has expired', function () {
    $user = User::factory()->create();
    $transaction = Transaction::create([
        'user_id' => $user->id,
        'total_price' => 100000,
        'shipping_cost' => 10000,
        'payment_method' => 'midtrans',
        'shipping_address' => 'Jl. Contoh No. 1',
        'status' => 'cancelled',
        'payment_status' => 'expired',
        'payment_due_at' => now()->subMinutes(1),
    ]);

    expect($transaction->isPayable())->toBeFalse();
});

it('returns false for isPayable when the 15 minute deadline has passed', function () {
    $user = User::factory()->create();
    $transaction = Transaction::create([
        'user_id' => $user->id,
        'total_price' => 100000,
        'shipping_cost' => 10000,
        'payment_method' => 'midtrans',
        'shipping_address' => 'Jl. Contoh No. 1',
        'status' => 'pending_payment',
        'payment_status' => 'pending',
        'payment_due_at' => now()->subMinutes(1),
    ]);

    expect($transaction->isPayable())->toBeFalse();
});

it('marks a pending order as expired past its 15 minute deadline', function () {
    $user = User::factory()->create();
    $transaction = Transaction::create([
        'user_id' => $user->id,
        'total_price' => 100000,
        'shipping_cost' => 10000,
        'payment_method' => 'midtrans',
        'shipping_address' => 'Jl. Contoh No. 1',
        'status' => 'pending_payment',
        'payment_status' => 'pending',
        'payment_due_at' => now()->subMinutes(1),
    ]);

    expect($transaction->markExpiredIfPastDue())->toBeTrue();
    expect($transaction->refresh()->payment_status)->toBe('expired');
    expect($transaction->refresh()->status)->toBe('cancelled');
});
