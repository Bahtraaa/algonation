<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the customer profile & dashboard.
     */
    public function show(): View
    {
        $user = $this->authenticatedUser();

        $transactionQuery = $user->transactions()->with('details.product');

        $totalOrders   = (clone $transactionQuery)->whereNotIn('status', ['cancelled'])->count();
        $totalSpent    = (clone $transactionQuery)->where('payment_status', 'paid')->sum('total_price');
        $pendingOrders = (clone $transactionQuery)->where('payment_status', 'pending')->count();

        $recentOrders = (clone $transactionQuery)->latest()->take(5)->get();

        $addresses = $user->addresses()->get();
        $defaultAddress = $addresses->firstWhere('is_default', true) ?? $addresses->first();

        return view('profile.show', compact('user', 'totalOrders', 'totalSpent', 'pendingOrders', 'recentOrders', 'addresses', 'defaultAddress'));
    }

    /**
     * Update the profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $this->authenticatedUser();

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
        $user = $this->authenticatedUser();

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini salah.',
            ]);
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    private function authenticatedUser(): User
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            abort(403);
        }

        return $user;
    }
}

