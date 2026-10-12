import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";

Alpine.plugin(collapse);

// Global Alpine Data Component Definitions
const searchableRelationSelect = (options = [], initialValue = '') => ({
    open: false,
    search: '',
    selected: String(initialValue || ''),
    options: options || [],

    filteredOptions() {
        const query = (this.search || '').toLowerCase().trim();
        if (!query) return this.options;
        return this.options.filter((option) =>
            option.label && option.label.toLowerCase().includes(query)
        );
    },

    selectedLabel() {
        const found = this.options.find((option) => String(option.id) === this.selected);
        return found ? found.label : '';
    },

    select(option) {
        this.selected = String(option ? (option.id || '') : '');
        this.open = false;
        this.search = '';
    },

    clearSelection() {
        this.selected = '';
        this.open = false;
        this.search = '';
    }
});

const searchableFilterSelect = (options = [], initialValue = '') => ({
    open: false,
    search: '',
    selected: String(initialValue || ''),
    options: options || [],

    filteredOptions() {
        const query = (this.search || '').toLowerCase().trim();
        if (!query) return this.options;
        return this.options.filter((option) =>
            option.label && option.label.toLowerCase().includes(query)
        );
    },

    selectedLabel() {
        if (!this.selected) return 'Semua Kategori';
        const found = this.options.find((option) => String(option.id) === this.selected);
        return found ? found.label : 'Semua Kategori';
    },

    select(optionOrId) {
        const id = typeof optionOrId === 'object' && optionOrId !== null ? optionOrId.id : optionOrId;
        this.selected = String(id || '');
        this.open = false;
        this.search = '';
        this.$nextTick(() => {
            const form = this.$refs.hiddenInput?.form || this.$root?.closest('form');
            if (form) form.submit();
        });
    }
});

const customSimpleSelect = (options = [], initialValue = '', placeholder = '-- Pilih --') => ({
    open: false,
    selected: String(initialValue ?? ''),
    placeholder: placeholder,
    options: options || [],

    selectedOption() {
        return this.options.find((option) => String(option.id) === this.selected);
    },

    selectedLabel() {
        const found = this.selectedOption();
        return found ? found.label : this.placeholder;
    },

    select(option) {
        this.selected = String(option ? (option.id ?? '') : '');
        this.open = false;
    }
});

const customFilterSelect = (options = [], initialValue = '', defaultLabel = 'Semua') => ({
    open: false,
    selected: String(initialValue ?? ''),
    defaultLabel: defaultLabel,
    options: options || [],

    selectedLabel() {
        if (!this.selected) return this.defaultLabel;
        const found = this.options.find((option) => String(option.id) === this.selected);
        return found ? found.label : this.defaultLabel;
    },

    select(optionOrId) {
        const id = typeof optionOrId === 'object' && optionOrId !== null ? optionOrId.id : optionOrId;
        this.selected = String(id ?? '');
        this.open = false;
        this.$nextTick(() => {
            const form = this.$refs.hiddenInput?.form || this.$root?.closest('form');
            if (form) form.submit();
        });
    }
});

const searchablePelangganSelect = (options = [], initialValue = '') => ({
    open: false,
    search: '',
    selected: String(initialValue || ''),
    options: options || [],

    filteredOptions() {
        const query = (this.search || '').toLowerCase().trim();
        if (!query) return this.options;
        return this.options.filter((opt) =>
            (opt.label && opt.label.toLowerCase().includes(query)) ||
            (opt.no_hp && opt.no_hp.toLowerCase().includes(query)) ||
            (opt.nama && opt.nama.toLowerCase().includes(query))
        );
    },

    selectedLabel() {
        const found = this.options.find((opt) => String(opt.id) === this.selected);
        return found ? found.label : '-- Pelanggan Umum (Guest) --';
    },

    select(option) {
        this.selected = String(option ? (option.id || '') : '');
        this.open = false;
        this.search = '';

        const namaInput = document.getElementById('nama_pelanggan');
        const hpInput = document.getElementById('no_hp_pelanggan');
        if (option && option.id) {
            if (namaInput) namaInput.value = option.nama || '';
            if (hpInput) hpInput.value = option.no_hp || '';
        } else {
            if (namaInput) namaInput.value = '';
            if (hpInput) hpInput.value = '';
        }
    }
});

const posSystem = (menus = []) => ({
    tipePesanan: 'Dine-In',
    cart: [],
    menuFilterSearch: '',

    addToCart(id, nama, harga, maxStok) {
        let index = this.cart.findIndex(item => item.id === id);
        if (index !== -1) {
            if (this.cart[index] && this.cart[index].qty < maxStok) {
                this.cart[index].qty++;
            } else {
                alert('Stok tidak mencukupi!');
            }
        } else {
            this.cart.push({ id, nama, harga, qty: 1, maxStok });
        }
    },

    tambahQty(index) {
        if (this.cart[index] && this.cart[index].qty < this.cart[index].maxStok) {
            this.cart[index].qty++;
        }
    },

    kurangiQty(index) {
        if (this.cart[index] && this.cart[index].qty > 1) {
            this.cart[index].qty--;
        } else {
            this.hapusItem(index);
        }
    },

    hapusItem(index) {
        this.cart.splice(index, 1);
    },

    totalBelanja() {
        return this.cart.reduce((total, item) => total + (item.harga * item.qty), 0);
    },

    formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka || 0);
    }
});

// Register with Alpine directly before start
Alpine.data('searchableRelationSelect', searchableRelationSelect);
Alpine.data('searchableFilterSelect', searchableFilterSelect);
Alpine.data('searchablePelangganSelect', searchablePelangganSelect);
Alpine.data('posSystem', posSystem);
Alpine.data('customSimpleSelect', customSimpleSelect);
Alpine.data('customFilterSelect', customFilterSelect);

// Export to window for global access
window.Alpine = Alpine;
window.searchableRelationSelect = searchableRelationSelect;
window.searchableFilterSelect = searchableFilterSelect;
window.searchablePelangganSelect = searchablePelangganSelect;
window.posSystem = posSystem;
window.customSimpleSelect = customSimpleSelect;
window.customFilterSelect = customFilterSelect;

Alpine.start();
