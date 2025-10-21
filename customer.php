<h1 class="text-3xl font-bold text-gray-800 mb-6">Customers</h1>

<!-- NEW: Filter and Search Section -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div>
        <input type="text" x-model.debounce.300ms="customerSearchQuery" class="form-input" placeholder="Search by Customer Name or Phone Number...">
    </div>
    <div class="flex items-center gap-2">
        <button @click="setExpiryFilter('all')" :class="{'bg-purple-600 text-white': customerExpiryFilter === 'all', 'bg-white': customerExpiryFilter !== 'all'}" class="btn btn-secondary btn-sm flex-1">All Customers</button>
        <button @click="setExpiryFilter('7')" :class="{'bg-purple-600 text-white': customerExpiryFilter === '7', 'bg-white': customerExpiryFilter !== '7'}" class="btn btn-secondary btn-sm flex-1">Expires in 7 Days</button>
        <button @click="setExpiryFilter('3')" :class="{'bg-purple-600 text-white': customerExpiryFilter === '3', 'bg-white': customerExpiryFilter !== '3'}" class="btn btn-secondary btn-sm flex-1">Expires in 3 Days</button>
        <button @click="setExpiryFilter('1')" :class="{'bg-purple-600 text-white': customerExpiryFilter === '1', 'bg-white': customerExpiryFilter !== '1'}" class="btn btn-secondary btn-sm flex-1">Expires in 1 Day</button>
    </div>
</div>


<template x-if="paginatedCustomers.length === 0">
    <p class="text-gray-500 text-center py-10" x-text="customerSearchQuery || customerExpiryFilter !== 'all' ? 'No customers match your criteria.' : 'No customers have been added yet.'"></p>
</template>

<template x-if="paginatedCustomers.length > 0">
    <div class="space-y-4">
        <template x-for="customer in paginatedCustomers" :key="customer.phone">
            <div class="card overflow-hidden" x-data="{ expanded: false }">
                <!-- Customer Header (Always Visible) -->
                <div class="p-4 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500 text-xl" x-text="customer.name.charAt(0)"></div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-800" x-text="customer.name"></h3>
                            <p class="text-sm text-gray-600 font-medium" x-text="'+880' + customer.phone"></p>
                        </div>
                    </div>
                    <div class="flex-shrink-0 flex items-center gap-2">
                         <div class="hidden sm:flex items-center text-xs font-semibold gap-3">
                            <span class="inline-flex items-center gap-1.5 py-1 px-2 rounded-full text-green-700 bg-green-50" title="Active Subscriptions">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
                                <span x-text="(customer.products || []).filter(p => p.renewal_date !== 'N/A' && new Date(p.renewal_date) >= new Date().setHours(0,0,0,0)).length"></span>
                            </span>
                             <span class="inline-flex items-center gap-1.5 py-1 px-2 rounded-full text-red-700 bg-red-50" title="Expired Subscriptions">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span>
                                <span x-text="(customer.products || []).filter(p => p.renewal_date === 'N/A' || new Date(p.renewal_date) < new Date().setHours(0,0,0,0)).length"></span>
                            </span>
                        </div>
                        <a :href="'https://wa.me/880' + customer.phone" target="_blank" class="btn btn-secondary !p-2 h-9 w-9 !bg-green-100 !text-green-700 hover:!bg-green-200" title="Send WhatsApp Message">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <button @click="expanded = !expanded" class="btn btn-secondary !p-2 h-9 w-9" title="View Details">
                            <i class="fa-solid transition-transform" :class="expanded ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </button>
                    </div>
                </div>

                <!-- Collapsible Details Section -->
                <div x-show="expanded" x-transition class="bg-gray-50/75 p-4 border-t">
                    <h4 class="font-semibold text-sm text-gray-500 mb-3">Purchased Products</h4>
                    <template x-if="!customer.products || customer.products.length === 0">
                        <p class="text-sm text-gray-500 pl-1">No products purchased by this customer yet.</p>
                    </template>
                    <template x-if="customer.products && customer.products.length > 0">
                        <div class="space-y-3">
                            <template x-for="product in customer.products">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center text-sm p-3 bg-white rounded-lg border">
                                    <div class="font-semibold text-gray-800" x-text="product.name"></div>
                                    <div class="text-gray-600">
                                        <p>Purchased: <span class="font-medium" x-text="product.purchase_date"></span></p>
                                        <p>Renews: <span class="font-medium" x-text="product.renewal_date"></span></p>
                                    </div>
                                    <div class="text-left sm:text-right">
                                        <span class="inline-flex items-center gap-2 font-bold text-xs py-1 px-2.5 rounded-full" 
                                            :class="(product.renewal_date !== 'N/A' && new Date(product.renewal_date) >= new Date().setHours(0,0,0,0)) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                                            <span class="h-2 w-2 rounded-full" :class="(product.renewal_date !== 'N/A' && new Date(product.renewal_date) >= new Date().setHours(0,0,0,0)) ? 'bg-green-500' : 'bg-red-500'"></span>
                                            <span x-text="(product.renewal_date !== 'N/A' && new Date(product.renewal_date) >= new Date().setHours(0,0,0,0)) ? 'Active' : 'Expired'"></span>
                                        </span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>
</template>

<!-- Pagination for Customers -->
<div class="mt-6 flex justify-between items-center" x-show="totalCustomerPages > 1">
    <button @click="prevCustomerPage" :disabled="currentCustomerPage === 1" class="btn btn-secondary" :class="{'opacity-50 cursor-not-allowed': currentCustomerPage === 1}"><i class="fa-solid fa-chevron-left"></i> Previous</button>
    <span class="text-sm font-medium text-gray-700">Page <span x-text="currentCustomerPage"></span> of <span x-text="totalCustomerPages"></span></span>
    <button @click="nextCustomerPage" :disabled="currentCustomerPage === totalCustomerPages" class="btn btn-secondary" :class="{'opacity-50 cursor-not-allowed': currentCustomerPage === totalCustomerPages}">Next <i class="fa-solid fa-chevron-right"></i></button>
</div>