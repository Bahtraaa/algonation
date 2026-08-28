<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the customer profile & dashboard.
     */
    public function show(): View
    {
        $user = auth()->user();

        $allTransactions = $user->transactions()->with('details.product');

        $validTransactions = (clone $allTransactions)
            ->whereNotIn('status', ['cancelled']);

        $stats = [
            'orders'      => $validTransactions->count(),
            'total_spent' => $validTransactions->sum('total_price'),
            'pending'     => $validTransactions->where('status', 'pending')->count(),
        ];

        $recentOrders = $allTransactions->latest()->take(5)->get();

        return view('profile.show', compact('user', 'stats', 'recentOrders'));
    }

    /**
     * Update the profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update the account password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($data['current_password'], auth()->user()->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini salah.',
            ]);
        }

        auth()->user()->update([
            'password' => Hash::make($data['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}

