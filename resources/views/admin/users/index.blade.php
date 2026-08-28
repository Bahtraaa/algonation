@extends('layouts.admin')

@section('title', 'Kelola Pengguna')
@section('page-title', 'Pengguna')

@section('content')
    {{-- Filters --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-1 flex-col gap-3 sm:flex-row">
            <form action="{{ route('admin.users.index') }}" method="GET" class="relative flex-1 sm:max-w-xs">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, username..."
                    class="input pl-10">
                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </form>
            <form action="{{ route('admin.users.index') }}" method="GET">
                <select name="role" onchange="this.form.submit()" class="input sm:w-44">
                    <option value="all" {{ request('role') === 'all' || !request('role') ? 'selected' : '' }}>Semua Role</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Customer</option>
                </select>
            </form>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Tambah Pengguna
        </a>
    </div>

    {{-- Table --}}
    <div class="table-wrap animate-fade-up">
        <table class="table-base">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Terdaftar</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary/20 font-bold text-ink">{{ $user->initial }}</span>
                                <div class="min-w-0">
                                    <p class="truncate font-semibold">{{ $user->name }}</p>
                                    <p class="truncate text-xs text-slate-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.users.role', $user) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="role" onchange="this.form.submit()"
                                    class="rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-xs font-semibold dark:border-white/10 dark:bg-slate-900">
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Customer</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <span class="badge {{ $user->status_class }}">{{ ucfirst($user->status) }}</span>
                        </td>
                        <td class="text-slate-500">{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="flex justify-end">
                                <form method="POST" action="{{ route('admin.users.status', $user) }}"
                                    id="status-form-{{ $user->id }}"
                                    onsubmit="event.preventDefault(); window.confirmAction('{{ $user->isActive() ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ $user->name }}?', () => { document.getElementById('status-form-{{ $user->id }}').submit(); })">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="btn-sm rounded-xl px-3 py-1.5 text-xs font-semibold transition-colors {{ $user->isActive() ? 'bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-400' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400' }}">
                                        {{ $user->isActive() ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center">
                            <p class="font-semibold">Tidak ada pengguna.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($users->hasPages())
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif
@endsection

