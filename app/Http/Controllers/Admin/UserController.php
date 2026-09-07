<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Show all registered users (admins & customers).
     */
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->string('search').'%')
                ->orWhere('email', 'like', '%'.$request->string('search').'%')
                ->orWhere('username', 'like', '%'.$request->string('search').'%');
        }

        if ($request->filled('role') && $request->string('role') !== 'all') {
            $query->where('role', $request->string('role'));
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the create user form.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user account.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => ['required', Rule::in(['admin', 'user'])],
            'status'   => ['required', Rule::in(['active', 'suspended'])],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'username' => $data['username'] ?? null,
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
            'status'   => $data['status'],
        ]);

        return redirect()->route('admin.users.index')->with('success', "Akun {$user->name} berhasil dibuat.");
    }

    /**
     * Update the role of a user.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(['admin', 'user'])],
        ]);

        // Prevent demoting the last active admin.
        if ($data['role'] === 'user' && $user->isLastActiveAdmin()) {
            return back()->with('error', 'Tidak dapat menghapus admin terakhir.');
        }

        $user->update($data);

        return back()->with('success', "Role {$user->name} diperbarui menjadi {$data['role']}.");
    }

    /**
     * Toggle the account status (active / suspended).
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        // Prevent disabling yourself.
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        // Prevent disabling the last active admin.
        if ($user->isActive() && $user->isLastActiveAdmin()) {
            return back()->with('error', 'Tidak dapat menonaktifkan admin terakhir.');
        }

        $user->update([
            'status' => $user->isActive() ? 'suspended' : 'active',
        ]);

        return back()->with('success', "Status {$user->name} diperbarui.");
    }
}

