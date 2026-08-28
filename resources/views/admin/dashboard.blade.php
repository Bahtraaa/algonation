@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
    {{-- Stats cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="card-flat animate-fade-up p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500">Total Pendapatan</p>
                    <p class="mt-1 font-display text-2xl font-extrabold text-primary dark:text-primary-soft">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary-soft">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <p class="mt-2 text-xs text-slate-400">Seluruh transaksi berhasil</p>
        </div>

        <div class="card-flat animate-fade-up delay-75ms p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500">Total Pesanan</p>
                    <p class="mt-1 font-display text-2xl font-extrabold">{{ $totalOrders }}</p>
                </div>
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary-soft">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </span>
            </div>
            <p class="mt-2 text-xs text-slate-400">Semua status</p>
        </div>

        <div class="card-flat animate-fade-up delay-150ms p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500">Total Produk</p>
                    <p class="mt-1 font-display text-2xl font-extrabold">{{ $totalProducts }}</p>
                </div>
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary-soft">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </span>
            </div>
            <p class="mt-2 text-xs text-slate-400">Terdaftar di katalog</p>
        </div>

        <div class="card-flat animate-fade-up delay-300ms p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500">Total Pelanggan</p>
                    <p class="mt-1 font-display text-2xl font-extrabold">{{ $totalUsers }}</p>
                </div>
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary-soft">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </span>
            </div>
            <p class="mt-2 text-xs text-slate-400">Akun terdaftar</p>
        </div>
    </div>

    {{-- Charts --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="card-flat animate-fade-up p-5 lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-display font-bold">Pendapatan</h3>
                <div class="flex gap-1 rounded-lg bg-slate-100 p-1 dark:bg-white/5">
                    @foreach ([7, 30, 90] as $d)
                        <a href="{{ route('admin.dashboard', ['days' => $d]) }}"
                            class="rounded-md px-3 py-1 text-xs font-semibold {{ $days === $d ? 'bg-primary text-ink' : 'text-slate-500 hover:text-slate-800 dark:hover:text-slate-200' }}">
                            {{ $d }}H
                        </a>
                    @endforeach
                </div>
            </div>
            <canvas id="revenueChart" class="max-h-72"></canvas>
        </div>

        <div class="card-flat animate-fade-up delay-150ms p-5">
            <h3 class="mb-4 font-display font-bold">Status Pesanan</h3>
            <canvas id="ordersChart" class="max-h-56"></canvas>
            <div class="mt-4 space-y-2">
                @foreach ($ordersByStatus as $status => $count)
                    <div class="flex items-center justify-between text-sm">
                        <span class="capitalize text-slate-500">{{ $status }}</span>
                        <span class="font-bold">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Bottom grid --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        {{-- Low stock --}}
        <div class="card-flat animate-fade-up">
            <div class="flex items-center justify-between border-b border-slate-100 p-5 dark:border-white/10">
                <h3 class="font-display font-bold">⚠️ Stok Menipis</h3>
                <a href="{{ route('admin.stock.index') }}" class="text-xs font-semibold text-primary hover:underline dark:text-primary-soft">Lihat semua</a>
            </div>
            <div class="divide-y divide-slate-50 dark:divide-white/5">
                @forelse ($lowStockProducts as $product)
                    <div class="flex items-center gap-3 p-4">
                        <img src="{{ $product->image_url }}" alt="" class="h-11 w-11 rounded-xl object-cover">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold">{{ $product->name }}</p>
                            <p class="text-xs text-slate-500">{{ $product->category }}</p>
                        </div>
                        @if ($product->is_out_of_stock)
                            <span class="badge-danger">Habis</span>
                        @else
                            <span class="badge-warning">Sisa {{ $product->total_stock }}</span>
                        @endif
                    </div>
                @empty
                    <p class="p-5 text-center text-sm text-slate-500">Semua stok aman 🎉</p>
                @endforelse
            </div>
        </div>

        {{-- Recent users --}}
        <div class="card-flat animate-fade-up delay-300ms">
            <div class="flex items-center justify-between border-b border-slate-100 p-5 dark:border-white/10">
                <h3 class="font-display font-bold">Aktivitas Pengguna</h3>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-primary hover:underline dark:text-primary-soft">Kelola</a>
            </div>
            <div class="divide-y divide-slate-50 dark:divide-white/5">
                @forelse ($recentUsers as $user)
                    <div class="flex items-center gap-3 p-4">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary/20 font-bold text-ink">{{ $user->initial }}</span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold">{{ $user->name }}</p>
                            <p class="truncate text-xs text-slate-500">{{ $user->email }}</p>
                        </div>
                        <span class="badge {{ $user->role_class }}">{{ $user->role }}</span>
                    </div>
                @empty
                    <p class="p-5 text-center text-sm text-slate-500">Belum ada pengguna.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Revenue chart
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: @json($chart['labels']),
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: @json($chart['revenue']),
                        borderColor: '#8d5e42',
                        backgroundColor: 'rgba(141, 94, 66, 0.15)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#6b432c',
                        pointRadius: 3,
                        pointHoverRadius: 6,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.parsed.y),
                            },
                        },
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (v) => 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(v),
                            },
                        },
                    },
                },
            });
        }

        // Orders status doughnut
        const ordersCtx = document.getElementById('ordersChart');
        if (ordersCtx) {
            new Chart(ordersCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(@json($ordersByStatus)),
                    datasets: [{
                        data: Object.values(@json($ordersByStatus)),
                        backgroundColor: ['#8b5e3c', '#3b82f6', '#10b981', '#f43f5e'],
                        borderWidth: 0,
                        hoverOffset: 6,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                    },
                },
            });
        }
    });
</script>
@endpush

