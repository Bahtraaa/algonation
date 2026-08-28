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
                this.items = res.data.items || [];
                this.count = res.data.count || 0;
                this.subtotal = res.data.subtotal || 0;
                this.total = res.data.total || 0;
            } catch (e) {
                console.error('Cart refresh failed', e);
            }
        },

        async add(productId, variantId = null, quantity = 1) {
            this.loading = true;
            try {
                const res = await axios.post('/cart/add', {
                    product_id: productId,
                    variant_id: variantId,
                    quantity: quantity,
                });
                if (res.data.success) {
                    this.items = res.data.cart.items;
                    this.count = res.data.cart.count;
                    this.subtotal = res.data.cart.subtotal;
                    this.total = res.data.cart.total;
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
                this.items = res.data.cart.items;
                this.count = res.data.cart.count;
                this.subtotal = res.data.cart.subtotal;
                this.total = res.data.cart.total;
            } catch (e) {
                window.toast('Gagal memperbarui keranjang', 'error');
            }
        },

        async remove(key) {
            try {
                const res = await axios.post('/cart/remove', { key });
                this.items = res.data.cart.items;
                this.count = res.data.cart.count;
                this.subtotal = res.data.cart.subtotal;
                this.total = res.data.cart.total;
                window.toast('Item dihapus', 'info');
            } catch (e) {
                window.toast('Gagal menghapus item', 'error');
            }
        },

        async clear() {
            try {
                const res = await axios.post('/cart/clear');
                this.items = [];
                this.count = 0;
                this.subtotal = 0;
                this.total = 0;
                window.toast('Keranjang dikosongkan', 'info');
            } catch (e) {
                window.toast('Gagal mengosongkan keranjang', 'error');
            }
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
});

// Re-hydrate cart count on load
document.addEventListener('DOMContentLoaded', () => {
    window.Alpine.store('cart').refresh();
});

Alpine.start();

