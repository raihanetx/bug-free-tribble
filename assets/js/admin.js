
function updateStatsDisplay(period) { const stats = allStats[period]; const container = document.getElementById('stats-display-container'); container.innerHTML = `<div class="bg-white p-4 rounded-lg flex items-center gap-4"><div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center"><i class="fa-solid fa-dollar-sign text-2xl text-blue-600"></i></div><div><p class="text-gray-500 text-sm font-medium">Revenue</p><p class="text-xl font-bold text-gray-800">৳${stats.total_revenue.toFixed(2)}</p></div></div><div class="bg-white p-4 rounded-lg flex items-center gap-4"><div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center"><i class="fa-solid fa-box-archive text-2xl text-purple-600"></i></div><div><p class="text-gray-500 text-sm font-medium">Orders</p><p class="text-xl font-bold text-gray-800">${stats.total_orders}</p></div></div><div class="bg-white p-4 rounded-lg flex items-center gap-4"><div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center"><i class="fa-solid fa-circle-check text-2xl text-green-600"></i></div><div><p class="text-gray-500 text-sm font-medium">Confirmed</p><p class="text-xl font-bold text-gray-800">${stats.confirmed_orders}</p></div></div><div class="bg-white p-4 rounded-lg flex items-center gap-4"><div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center"><i class="fa-solid fa-clock-rotate-left text-2xl text-yellow-600"></i></div><div><p class="text-gray-500 text-sm font-medium">Pending</p><p class="text-xl font-bold text-gray-800">${stats.pending_orders}</p></div></div><div class="bg-white p-4 rounded-lg flex items-center gap-4"><div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center"><i class="fa-solid fa-ban text-2xl text-red-600"></i></div><div><p class="text-gray-500 text-sm font-medium">Cancelled</p><p class="text-xl font-bold text-gray-800">${stats.cancelled_orders}</p></div></div>`; }
document.addEventListener('DOMContentLoaded', function() {
    const pricingType = document.getElementById('pricing-type'); if (pricingType) { const singlePriceContainer = document.getElementById('single-price-container'); const multiplePricingContainer = document.getElementById('multiple-pricing-container'); const addDurationBtn = document.getElementById('add-duration-btn'); const durationFields = document.getElementById('duration-fields'); pricingType.addEventListener('change', function() { if (this.value === 'single') { singlePriceContainer.classList.remove('hidden'); multiplePricingContainer.classList.add('hidden'); } else { singlePriceContainer.classList.add('hidden'); multiplePricingContainer.classList.remove('hidden'); if (durationFields.children.length === 0) addDurationField(); } }); addDurationBtn.addEventListener('click', addDurationField); function addDurationField() { const fieldGroup = document.createElement('div'); fieldGroup.className = 'flex items-center gap-2 mb-2'; fieldGroup.innerHTML = `<input type="text" name="durations[]" class="form-input" placeholder="Duration (e.g., 1 Year)" required><input type="number" name="duration_prices[]" step="0.01" class="form-input" placeholder="Price" required><button type="button" class="btn btn-danger btn-sm remove-duration-btn"><i class="fa-solid fa-trash-can"></i></button>`; durationFields.appendChild(fieldGroup); } durationFields.addEventListener('click', function(e) { if (e.target && e.target.closest('.remove-duration-btn')) { e.target.closest('.flex').remove(); } }); }
    const statsFilterContainer = document.getElementById('stats-filter-container'); if (statsFilterContainer) { updateStatsDisplay('all'); statsFilterContainer.addEventListener('click', function(e) { if (e.target.matches('.stats-filter-btn')) { this.querySelectorAll('.stats-filter-btn').forEach(btn => btn.classList.remove('active')); e.target.classList.add('active'); const period = e.target.dataset.period; updateStatsDisplay(period); } }); }
    const couponScope = document.getElementById('coupon_scope'); if (couponScope) { const categoryContainer = document.getElementById('scope_category_container'); const productContainer = document.getElementById('scope_product_container'); couponScope.addEventListener('change', function() { categoryContainer.classList.add('hidden'); productContainer.classList.add('hidden'); if (this.value === 'category') categoryContainer.classList.remove('hidden'); if (this.value === 'single_product') productContainer.classList.remove('hidden'); }); }
    const toggleBtn = document.getElementById('toggle_password_btn');
    const passwordField = document.getElementById('new_password_field');
    if (toggleBtn && passwordField) {
        toggleBtn.addEventListener('click', function() {
            const isPassword = passwordField.type === 'password';
            passwordField.type = isPassword ? 'text' : 'password';
            this.textContent = isPassword ? 'Hide' : 'Show';
        });
    }
});

function adminManager(data) {
    return {
        // Modal State
        isModalOpen: false,
        modalType: '', // 'email', 'payment'
        currentOrderId: null,
        currentCustomerEmail: null,
        editMethod: {},

        // Data
        allOrders: data.allOrders,
        existingCustomerPhones: data.existingCustomerPhones,
        allCustomers: data.allCustomers,

        // Order Pagination & Search
        searchQuery: '',
        currentPage: 1,
        ordersPerPage: 10,

        // Customer Pagination & Search
        customerSearchQuery: '',
        customerExpiryFilter: 'all', // 'all', '1', '3', '7'
        currentCustomerPage: 1,
        customersPerPage: 10,

        // Modal Methods
        openModal(orderId, customerEmail) {
            this.modalType = 'email';
            this.currentOrderId = orderId;
            this.currentCustomerEmail = customerEmail;
            this.isModalOpen = true;
        },
        openPaymentModal(methodName) {
            this.modalType = 'payment';
            let methodData = allPaymentMethods[methodName] || {};
            let editData = { name: methodName, logo_url: methodData.logo_url || '', is_default: methodData.is_default || false };
            if (methodData.hasOwnProperty('number')) editData.number = methodData.number;
            else if (methodData.hasOwnProperty('pay_id')) editData.pay_id = methodData.pay_id;
            else if (methodData.hasOwnProperty('account_number')) editData.account_number = methodData.account_number;
            this.editMethod = editData;
            this.isModalOpen = true;
        },
        closeModal() { this.isModalOpen = false; this.modalType = ''; this.currentOrderId = null; this.currentCustomerEmail = null; this.editMethod = {}; },

        // Computed Properties for Orders
        get filteredOrders() {
            if (this.searchQuery.trim() === '') return this.allOrders;
            const query = this.searchQuery.toLowerCase().trim();
            return this.allOrders.filter(order => {
                const productNames = (order.items || []).map(item => item.name).join(' ').toLowerCase();
                return `${order.order_id} ${order.customer.name} ${order.customer.phone} ${order.customer.email} ${productNames}`.toLowerCase().includes(query);
            });
        },
        get totalPages() { return Math.ceil(this.filteredOrders.length / this.ordersPerPage); },
        get paginatedOrders() {
            const start = (this.currentPage - 1) * this.ordersPerPage;
            return this.filteredOrders.slice(start, start + this.ordersPerPage);
        },

        // Computed Properties for Customers
        get filteredCustomers() {
            let customers = this.allCustomers;

            // Expiry filter
            if (this.customerExpiryFilter !== 'all') {
                const days = parseInt(this.customerExpiryFilter);
                const today = new Date();
                const cutoffDate = new Date();
                cutoffDate.setDate(today.getDate() + days);

                customers = customers.filter(c => {
                    return c.products && c.products.some(p => {
                        if (p.renewal_date === 'N/A') return false;
                        const renewalDate = new Date(p.renewal_date);
                        return renewalDate >= today && renewalDate <= cutoffDate;
                    });
                });
            }

            // Search filter
            if (this.customerSearchQuery.trim() !== '') {
                const query = this.customerSearchQuery.toLowerCase().trim();
                customers = customers.filter(c => `${c.name} ${c.phone}`.toLowerCase().includes(query));
            }

            return customers;
        },
        get totalCustomerPages() { return Math.ceil(this.filteredCustomers.length / this.customersPerPage); },
        get paginatedCustomers() {
            const start = (this.currentCustomerPage - 1) * this.customersPerPage;
            return this.filteredCustomers.slice(start, start + this.customersPerPage);
        },

        // Methods for Pagination & Filters
        nextPage() { if (this.currentPage < this.totalPages) this.currentPage++; },
        prevPage() { if (this.currentPage > 1) this.currentPage--; },
        nextCustomerPage() { if (this.currentCustomerPage < this.totalCustomerPages) this.currentCustomerPage++; },
        prevCustomerPage() { if (this.currentCustomerPage > 1) this.currentCustomerPage--; },
        setExpiryFilter(days) {
            this.customerExpiryFilter = days;
            this.currentCustomerPage = 1; // Reset to first page
        },

        // Initialization
        init() {
            this.$watch('searchQuery', () => { this.currentPage = 1; });
            this.$watch('customerSearchQuery', () => { this.currentCustomerPage = 1; });
        }
    }
}
