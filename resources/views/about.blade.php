@extends('layouts.app')

@section('title', 'About Us - ALGO NATION')

@section('content')
    <div class="overflow-hidden">
        {{-- Hero --}}
        <section class="relative border-b border-slate-200 bg-[#efe5d7] dark:border-white/10 dark:bg-[#241812]">
            <div class="mx-auto grid max-w-7xl gap-10 px-4 py-16 sm:px-6 lg:grid-cols-[1fr_0.72fr] lg:items-end lg:px-8 lg:py-24">
                <div class="animate-fade-up">
                    <p class="mb-5 text-xs font-bold uppercase tracking-[0.3em] text-primary dark:text-primary-soft">About Us</p>
                    <h1 class="max-w-3xl font-display text-5xl font-extrabold uppercase leading-[0.9] tracking-tight sm:text-7xl lg:text-8xl">About<br>Algo Nation</h1>
                    <p class="mt-8 max-w-xl font-display text-xl font-bold uppercase leading-tight sm:text-2xl">Wear your identity.</p>
                    <p class="mt-4 max-w-xl text-base leading-relaxed text-slate-700 dark:text-slate-300">ALGO NATION hadir untuk mereka yang percaya bahwa pakaian bukan hanya tentang apa yang kamu kenakan, tetapi bagaimana kamu mengekspresikan diri.</p>
                    <a href="{{ route('shop') }}" class="btn-dark mt-8">Explore Our Collection <span aria-hidden="true">→</span></a>
                </div>
                <div class="animate-fade-up relative min-h-64 overflow-hidden bg-ink sm:min-h-80 lg:min-h-[28rem]">
                    <img src="{{ asset('images/logo-an.png') }}" alt="Logo ALGO NATION" class="absolute inset-0 h-full w-full object-contain p-10 opacity-90" onerror="this.style.display='none';">
                    <div class="absolute inset-0 bg-gradient-to-t from-ink/70 via-transparent to-transparent"></div>
                    <p class="absolute bottom-5 left-5 text-xs font-bold uppercase tracking-[0.25em] text-white">Est. 2026 / Jakarta</p>
                </div>
            </div>
        </section>

        {{-- Our Story --}}
        <section class="mx-auto grid max-w-7xl gap-12 px-4 py-20 sm:px-6 lg:grid-cols-[0.7fr_1fr] lg:items-center lg:px-8 lg:py-28">
            <div class="relative aspect-[4/5] overflow-hidden bg-[#d7c5b0]">
                 <img src="{{ asset('images/sepatu-nike.jpg') }}" alt="Sneaker fashion untuk cerita ALGO NATION" class="h-full w-full object-cover" onerror="this.style.display='none';">
                <div class="absolute bottom-0 left-0 bg-primary px-5 py-4 text-xs font-bold uppercase tracking-[0.2em] text-white">The making of a statement</div>
            </div>
            <div class="max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-[0.3em] text-primary dark:text-primary-soft">02 / Our Story</p>
                <h2 class="mt-4 font-display text-4xl font-extrabold uppercase leading-none sm:text-6xl">Our<br>Story</h2>
                <div class="mt-8 space-y-5 text-base leading-relaxed text-slate-600 dark:text-slate-300">
                    <p>ALGO NATION lahir dari sebuah ide sederhana: menciptakan pakaian yang nyaman, modern, dan mampu merepresentasikan karakter setiap orang.</p>
                    <p>Kami percaya bahwa setiap individu memiliki gaya mereka sendiri. Karena itu, ALGO NATION menghadirkan berbagai pilihan fashion yang mudah dipadukan dan cocok untuk kehidupan sehari-hari.</p>
                </div>
            </div>
        </section>

        {{-- Philosophy --}}
        <section class="bg-[#171412] text-white">
            <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
                <p class="text-xs font-bold uppercase tracking-[0.3em] text-[#d4a574]">03 / Our Philosophy</p>
                <div class="mt-16 grid gap-10 lg:grid-cols-[1fr_0.8fr] lg:items-end">
                    <h2 class="font-display text-5xl font-extrabold uppercase leading-[0.88] sm:text-7xl lg:text-8xl">Style is<br><span class="text-[#d4a574]">personal.</span></h2>
                    <div class="max-w-md text-base leading-relaxed text-stone-300">
                        <p>Kami tidak ingin menentukan bagaimana kamu harus berpakaian.</p>
                        <p class="mt-4">Kami ingin memberikan kamu ruang untuk menemukan dan menciptakan gaya sendiri.</p>
                        <p class="mt-8 font-display text-lg font-bold uppercase tracking-wider text-white">Simple. Confident. Authentic.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- What We Stand For --}}
        <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
            <div class="flex flex-col justify-between gap-5 border-b border-slate-200 pb-8 sm:flex-row sm:items-end dark:border-white/10">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.3em] text-primary dark:text-primary-soft">04 / Our Values</p>
                    <h2 class="mt-3 font-display text-4xl font-extrabold uppercase sm:text-6xl">What We Stand For</h2>
                </div>
                <p class="max-w-xs text-sm leading-relaxed text-slate-500">Empat prinsip yang menjadi bagian dari setiap pilihan ALGO NATION.</p>
            </div>
            <div class="grid gap-px bg-slate-200 sm:grid-cols-2 lg:grid-cols-4 dark:bg-white/10">
                @foreach ([['01', 'Quality', 'Kami mengutamakan material dan kualitas produk agar nyaman digunakan setiap hari.'], ['02', 'Style', 'Desain modern yang mudah dipadukan dengan berbagai gaya.'], ['03', 'Comfort', 'Fashion harus terlihat bagus sekaligus terasa nyaman.'], ['04', 'Individuality', 'Setiap orang punya cerita dan karakter yang berbeda. Fashion adalah salah satu cara untuk menunjukkannya.']] as $value)
                    <article class="bg-paper p-6 dark:bg-[#1a120d] sm:p-7">
                        <p class="font-display text-sm font-bold text-primary dark:text-primary-soft">{{ $value[0] }}</p>
                        <h3 class="mt-16 font-display text-2xl font-extrabold uppercase">{{ $value[1] }}</h3>
                        <p class="mt-4 text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ $value[2] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        {{-- Brand statement --}}
        <section class="border-t border-slate-200 bg-[#e9ddce] dark:border-white/10 dark:bg-[#2a1b16]">
            <div class="mx-auto max-w-6xl px-4 py-24 text-center sm:px-6 lg:px-8 lg:py-36">
                <p class="text-xs font-bold uppercase tracking-[0.3em] text-primary dark:text-primary-soft">05 / Brand Statement</p>
                <blockquote class="mx-auto mt-10 max-w-5xl font-display text-5xl font-extrabold uppercase leading-[0.9] sm:text-7xl lg:text-8xl">“Don't follow the style.<br><span class="text-primary dark:text-primary-soft">Create your own.”</span></blockquote>
                <p class="mt-12 text-xs font-bold uppercase tracking-[0.35em] text-slate-600 dark:text-slate-300">ALGO NATION - Est. 2026</p>
            </div>
        </section>
    </div>
@endsection
