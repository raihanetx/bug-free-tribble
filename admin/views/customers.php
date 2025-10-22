<!-- Customer Management View -->
<div id="view-customers" style="<?= $current_view === 'customers' ? '' : 'display:none;' ?>" class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-700">Manage Customers</h2>
    </div>

    <div class="mb-4">
        <input type="text" x-model.debounce.300ms="customerSearchQuery" class="form-input" placeholder="Search by name or phone...">
    </div>

    <div class="bg-white border rounded-lg overflow-hidden">
        <div class="space-y-4 p-4">
            <template x-for="customer in paginatedCustomers" :key="customer.phone">
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-gray-800" x-text="customer.name"></h3>
                            <p class="text-sm text-gray-500" x-text="customer.phone"></p>
                        </div>
                        <a :href="'https://wa.me/' + customer.phone" target="_blank" class="btn btn-success btn-sm"><i class="fab fa-whatsapp"></i> Message</a>
                    </div>
                    <div class="mt-4">
                        <h4 class="font-semibold mb-2 text-gray-500 uppercase text-xs tracking-wider">Purchased Products</h4>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Renewal Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-for="product in customer.products" :key="product.name + product.purchase_date">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap" x-text="product.name"></td>
                                        <td class="px-6 py-4 whitespace-nowrap" x-text="product.purchase_date"></td>
                                        <td class="px-6 py-4 whitespace-nowrap" x-text="product.renewal_date"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                     <div class="flex justify-end mt-4">
                        <form action="../api.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                            <input type="hidden" name="action" value="delete_customer">
                            <input type="hidden" name="customer_phone" :value="customer.phone">
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i> Delete Customer</button>
                        </form>
                    </div>
                </div>
            </template>
        </div>
    </div>
        <div class="mt-6 flex justify-between items-center" x-show="totalCustomerPages > 1">
        <button @click="prevCustomerPage" :disabled="currentCustomerPage === 1" class="btn btn-secondary" :class="{'opacity-50 cursor-not-allowed': currentCustomerPage === 1}"><i class="fa-solid fa-chevron-left"></i> Previous</button>
        <span class="text-sm font-medium text-gray-700">Page <span x-text="currentCustomerPage"></span> of <span x-text="totalCustomerPages"></span></span>
        <button @click="nextCustomerPage" :disabled="currentCustomerPage === totalCustomerPages" class="btn btn-secondary" :class="{'opacity-50 cursor-not-allowed': currentCustomerPage === totalCustomerPages}">Next <i class="fa-solid fa-chevron-right"></i></button>
    </div>
</div>
