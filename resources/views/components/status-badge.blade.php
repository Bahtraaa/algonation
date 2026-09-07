@props(['variant' => 'badge-neutral', 'small' => false])

<span class="badge {{ $small ? 'text-[10px]' : '' }} {{ $variant }}">{{ $slot }}</span>