<div id="toast-container" class="pointer-events-none fixed right-4 top-4 z-100 flex w-full max-w-sm flex-col gap-2"></div>

<script>
    // Render server-side flash messages as toasts
    document.addEventListener('DOMContentLoaded', () => {
        @if (session('success'))
            window.toast('{{ session('success') }}', 'success');
        @endif
        @if (session('error'))
            window.toast('{{ session('error') }}', 'error');
        @endif
        @if (session('info'))
            window.toast('{{ session('info') }}', 'info');
        @endif

        // Validation errors
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                window.toast('{{ $error }}', 'error');
            @endforeach
        @endif
    });
</script>

