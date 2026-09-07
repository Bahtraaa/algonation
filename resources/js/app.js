import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Chart = Chart;

// ---------------- Toast notifications ----------------
window.toast = (message, type = 'success') => {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const icons = {
        success: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
        error: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>`,
        info: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
    };

    const colors = {
        success: 'border-primary text-primary dark:text-primary-soft',
        error: 'border-rose-500 text-rose-700 dark:text-rose-300',
        info: 'border-blue-500 text-blue-700 dark:text-blue-300',
    };

    const el = document.createElement('div');
    el.className = `toast-item flex items-center gap-3 rounded-xl border-l-4 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl shadow-xl shadow-slate-900/10 dark:shadow-black/30 px-4 py-3 animate-scale-in ${colors[type] || colors.success}`;
    el.innerHTML = `
        <span class="shrink-0">${icons[type] || icons.success}</span>
        <span class="text-sm font-medium text-slate-800 dark:text-slate-100">${message}</span>
        <button class="ml-auto shrink-0 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200" onclick="this.parentElement.remove()">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>`;
    container.appendChild(el);

    setTimeout(() => {
        el.style.transition = 'opacity .3s, transform .3s';
        el.style.opacity = '0';
        el.style.transform = 'translateY(-10px)';
        setTimeout(() => el.remove(), 300);
    }, 3500);
};

// ---------------- Format currency helper (IDR) ----------------
window.formatRupiah = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(value || 0);
};

// ---------------- Global confirm helper (for inline onsubmit) ----------------
window.confirmAction = (message, action) => {
    window.Alpine.store('ui').confirm(message, action);
};

// ---------------- Alpine stores ----------------
document.addEventListener('alpine:init', () => {
    // Dark mode store
    Alpine.store('theme', {
        dark: localStorage.getItem('theme') === 'dark',
        init() {
            if (this.dark) document.documentElement.classList.add('dark');
            else document.documentElement.classList.remove('dark');
        },
        toggle() {
            this.dark = !this.dark;
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', this.dark);
        },
    });

    // Cart store
    Alpine.store('cart', {
        items: [],
        count: 0,
        subtotal: 0,
        total: 0,
        loading: false,
        open: false,

        async refresh() {
            try {
                const res = await axios.get('/cart/state');
                this.syncState(res.data);
            } catch (e) {
                console.error('Cart refresh failed', e);
            }
        },

        async add(productId, variantId = null, quantity = 1, flashSaleId = null) {
            this.loading = true;
            try {
                const res = await axios.post('/cart/add', {
                    product_id: productId,
                    variant_id: variantId,
                    quantity: quantity,
                    flash_sale_id: flashSaleId,
                });
                if (res.data.success) {
                    this.syncState(res.data.cart);
                    window.toast(res.data.message, 'success');
                    this.open = true;
                }
            } catch (e) {
                if (e.response?.status === 401) {
                    window.location.href = e.response.data.redirect || '/login';
                    return;
                }
                window.toast(e.response?.data?.message || 'Gagal menambahkan ke keranjang', 'error');
            } finally {
                this.loading = false;
            }
        },

        async updateQty(key, qty) {
            if (qty < 1) return;
            try {
                const res = await axios.post('/cart/update', { key, quantity: qty });
                this.syncState(res.data.cart);
            } catch (e) {
                window.toast('Gagal memperbarui keranjang', 'error');
            }
        },

        async remove(key) {
            try {
                const res = await axios.post('/cart/remove', { key });
                this.syncState(res.data.cart);
                window.toast('Item dihapus', 'info');
            } catch (e) {
                window.toast('Gagal menghapus item', 'error');
            }
        },

        async clear() {
            try {
                const res = await axios.post('/cart/clear');
                this.syncState(res.data.cart);
                window.toast('Keranjang dikosongkan', 'info');
            } catch (e) {
                window.toast('Gagal mengosongkan keranjang', 'error');
            }
        },

        syncState(payload) {
            this.items = payload?.items || [];
            this.count = payload?.count || 0;
            this.subtotal = payload?.subtotal || 0;
            this.total = payload?.total || 0;
        },
    });

    // Global UI store (toast + modal helper)
    Alpine.store('ui', {
        confirmMessage: '',
        confirmAction: null,
        confirmVisible: false,

        // Product quick-add modal
        productModal: false,
        openProductModal() {
            this.productModal = true;
        },
        closeProductModal() {
            this.productModal = false;
        },

        confirm(message, action) {
            this.confirmMessage = message;
            this.confirmAction = action;
            this.confirmVisible = true;
        },
        accept() {
            if (this.confirmAction) this.confirmAction();
            this.confirmVisible = false;
            this.confirmAction = null;
        },
        cancel() {
            this.confirmVisible = false;
            this.confirmAction = null;
        },
    });

    // Flash-sale countdown component (hh:mm:ss label until the sale ends).
    Alpine.data('flashSaleCountdown', (endAt) => ({
        label: 'Berakhir dalam 00 : 00 : 00',
        timer: null,
        start() {
            const tick = () => {
                const seconds = Math.max(0, Math.floor((Date.parse(endAt) - Date.now()) / 1000));
                const hours = Math.floor(seconds / 3600);
                const minutes = Math.floor((seconds % 3600) / 60);
                const remaining = seconds % 60;
                this.label = seconds > 0
                    ? `Berakhir dalam ${String(hours).padStart(2, '0')} : ${String(minutes).padStart(2, '0')} : ${String(remaining).padStart(2, '0')}`
                    : 'Flash Sale Berakhir';
                if (seconds === 0 && this.timer) { clearInterval(this.timer); window.location.reload(); }
            };
            tick();
            this.timer = setInterval(tick, 1000);
        },
    }));
});

// ---------------- Payment helpers ----------------

// Start the singleton payment deadline countdown shown on receipts and order
// detail pages. Displays mm:ss and reloads the page when the deadline passes so
// the true status from the database is shown.
window.startPaymentCountdown = () => {
    const box = document.querySelector('[data-payment-countdown]');
    if (!box) return;

    const display = box.querySelector('[data-countdown-display]');
    const dueAt = Date.parse(box.getAttribute('data-due-at'));

    const tick = () => {
        if (!dueAt) { display.textContent = '--:--'; return; }

        const diff = dueAt - Date.now();
        if (diff <= 0) { display.textContent = '00:00'; window.location.reload(); return; }

        const totalSec = Math.ceil(diff / 1000);
        const m = String(Math.floor(totalSec / 60)).padStart(2, '0');
        const s = String(totalSec % 60).padStart(2, '0');
        display.textContent = m + ':' + s;
    };

    tick();
    setInterval(tick, 1000);
};

// Start every inline payment deadline timer that carries [data-countdown-due].
window.startDueCountdowns = () => {
    document.querySelectorAll('[data-countdown-due]').forEach((el) => {
        const dueAt = Date.parse(el.getAttribute('data-countdown-due'));

        const tick = () => {
            if (!dueAt) { el.textContent = '--:--'; return; }
            if (dueAt - Date.now() <= 0) { el.textContent = '00:00'; return; }

            const totalSec = Math.ceil((dueAt - Date.now()) / 1000);
            const m = String(Math.floor(totalSec / 60)).padStart(2, '0');
            const s = String(totalSec % 60).padStart(2, '0');
            el.textContent = m + ':' + s;
        };

        tick();
        setInterval(tick, 1000);
    });
};

// Wire every ".pay-now-btn" button to open the Midtrans Snap popup.
// The snap callbacks only drive UI redirection; payment verification is always
// handled server-side by the Midtrans webhook.
window.initPayNowButtons = () => {
    document.querySelectorAll('.pay-now-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            const url = btn.dataset.payUrl;
            const csrf = btn.dataset.csrf;
            btn.disabled = true;

            fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            })
            .then(res => res.json())
            .then(data => {
                if (data.error || data.errors) {
                    alert(data.error || Object.values(data.errors).flat().join('\n'));
                    btn.disabled = false;
                    return;
                }

                snap.pay(data.snap_token, {
                    onSuccess: () => { window.location.href = data.redirect_url; },
                    onPending: () => { window.location.href = data.redirect_url; },
                    onError: () => { alert('Pembayaran gagal. Silakan coba lagi.'); btn.disabled = false; },
                    onClose: () => { window.location.href = data.redirect_url; },
                });
            })
            .catch(() => {
                alert('Terjadi kesalahan. Silakan coba lagi.');
                btn.disabled = false;
            });
        });
    });
};

// Re-hydrate cart count on load
document.addEventListener('DOMContentLoaded', () => {
    window.Alpine.store('cart').refresh();
});

Alpine.start();

