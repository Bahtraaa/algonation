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
        'payment_method' => 'COD',
        'shipping_address' => 'Jl. Contoh No. 1',
        'status' => 'pending',
    ]);

    expect(fn () => (new CheckoutController())->receipt($transaction))
        ->toThrow(HttpException::class);
});
