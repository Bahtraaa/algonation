@extends('layouts.app')

@section('title', 'Edit Alamat - ALGO NATION')

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="animate-fade-up">
            <a href="{{ $returnTo === 'checkout' ? route('checkout') : route('addresses.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-700">Kembali</a>
            <h1 class="mt-2 font-display text-3xl font-extrabold">Edit Alamat</h1>
        </div>

        <form method="POST" action="{{ route('addresses.update', $address) }}" class="card-flat animate-fade-up mt-6 p-6">
            @csrf
            @method('PUT')
            @include('addresses._form')
            <div class="mt-6 flex gap-3">
                <a href="{{ $returnTo === 'checkout' ? route('checkout') : route('addresses.index') }}" class="btn-outline flex-1 justify-center">Batal</a>
                <button type="submit" class="btn-primary flex-1 justify-center">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
