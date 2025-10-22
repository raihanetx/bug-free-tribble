<!-- =================================================================== -->
<!-- START: ORDERS VIEW -->
<!-- =================================================================== -->
<div id="view-orders" style="<?= $current_view === 'orders' ? '' : 'display:none;' ?>">
    <h1 class="text-3xl font-bold text-gray-800">Orders</h1>

    <!-- Dashboard Stats -->
    <div class="my-8">
        <div class="flex flex-wrap gap-2 mb-4" id="stats-filter-container">
            <button class="stats-filter-btn" data-period="today">Today</button><button class="stats-filter-btn" data-period="7days">7 Days</button><button class="stats-filter-btn" data-period="30days">30 Days</button><button class="stats-filter-btn" data-period="6months">6 Months</button><button class="stats-filter-btn active" data-period="all">All Time</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4" id="stats-display-container"></div>
    </div>

    <hr class="mb-8 border-gray-200">

    <div class="mb-4"><input type="text" x-model.debounce.300ms="searchQuery" class="form-input" placeholder="Search by Order ID, Customer Name, Phone, Email, or Product Name..."></div>
    <template x-if="paginatedOrders.length === 0">
        <p class="text-gray-500 text-center py-10" x-text="searchQuery ? 'No orders match your search.' : 'No orders have been placed yet.'"></p>
    </template>
    <template x-if="paginatedOrders.length > 0">
        <div class="space-y-5">
            <template x-for="order in paginatedOrders" :key="order.order_id">

                <!-- START: Final Order Card Design -->
                <div class="card transition-all duration-200 hover:shadow-md hover:border-purple-200 overflow-hidden">
                    <!-- Card Header -->
                    <div class="p-4 bg-gray-50/75 border-b flex justify-between items-start flex-wrap gap-y-3">
                        <div>
                            <h3 class="font-bold text-gray-800">Order #<span x-text="order.order_id"></span></h3>
                            <p class="text-xs text-gray-500" x-text="new Date(order.order_date).toLocaleString()"></p>
                        </div>
                        <!-- Action Buttons & Status -->
                        <div class="flex items-center flex-wrap gap-3">
                                <span class="font-semibold py-1 px-3 rounded-md text-xs border" x-text="order.status"
                                :class="{
                                    'border-green-500 text-green-600': order.status === 'Confirmed',
                                    'border-red-500 text-red-600': order.status === 'Cancelled',
                                    'border-yellow-500 text-yellow-600': order.status === 'Pending'
                                }">
                            </span>

                                <template x-if="order.status === 'Pending'">
                                <div class="flex items-center gap-2">
                                    <form action="../api.php" method="POST" class="m-0"><input type="hidden" name="action" value="update_order_status"><input type="hidden" name="order_id" :value="order.order_id"><input type="hidden" name="new_status" value="Confirmed"><button title="Confirm Order" type="submit" class="btn btn-success !p-2 h-9 w-9"><i class="fa-solid fa-check"></i></button></form>
                                    <form action="../api.php" method="POST" class="m-0"><input type="hidden" name="action" value="update_order_status"><input type="hidden" name="order_id" :value="order.order_id"><input type="hidden" name="new_status" value="Cancelled"><button title="Cancel Order" type="submit" class="btn btn-danger !p-2 h-9 w-9"><i class="fa-solid fa-ban"></i></button></form>
                                </div>
                            </template>

                            <template x-if="order.status === 'Confirmed'">
                                <div class="flex items-center gap-2">
                                    <template x-if="order.access_email_sent">
                                        <div class="flex items-center justify-center h-9 px-3 bg-green-100 text-green-700 rounded-md text-xs font-semibold" title="Access email has been sent."><i class="fa-solid fa-check-circle mr-2"></i>Access Sent</div>
                                    </template>
                                    <template x-if="!order.access_email_sent">
                                        <button @click="openModal(order.order_id, order.customer.email)" class="btn btn-primary btn-sm !py-1.5" title="Send Access Details"><i class="fa-solid fa-paper-plane mr-2"></i>Send Mail</button>
                                    </template>

                                    <template x-if="existingCustomerPhones.includes(order.customer.phone)">
                                        <div class="flex items-center justify-center h-9 px-3 bg-gray-200 text-gray-600 rounded-md text-xs font-semibold" title="This customer is already in your customer list."><i class="fa-solid fa-user-check mr-2"></i>Added</div>
                                    </template>
                                    <template x-if="!existingCustomerPhones.includes(order.customer.phone)">
                                        <form action="../api.php" method="POST" class="m-0">
                                            <input type="hidden" name="action" value="add_customer_from_order"><input type="hidden" name="order_id" :value="order.order_id">
                                            <button type="submit" class="btn btn-secondary btn-sm !py-1.5" title="Add to Customers"><i class="fa-solid fa-user-plus mr-2"></i>Add Profile</button>
                                        </form>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 grid grid-cols-1 lg:grid-cols-2 gap-x-6 gap-y-5 text-sm">
                        <!-- Column 1: Customer & Items -->
                        <div class="space-y-4">
                            <div>
                                <h4 class="font-semibold text-gray-500 mb-1 flex items-center gap-2 text-xs uppercase tracking-wider"><i class="fa-solid fa-user w-4 text-center"></i> Customer Details</h4>
                                <div class="pl-6">
                                    <p class="font-semibold text-gray-800" x-text="order.customer.name"></p>
                                    <p class="text-gray-600" x-text="order.customer.phone"></p>
                                    <p class="text-gray-600 truncate" :title="order.customer.email" x-text="order.customer.email"></p>
                                </div>
                            </div>
                            <div class="border-t pt-4">
                                <h4 class="font-semibold text-gray-500 mb-2 flex items-center gap-2 text-xs uppercase tracking-wider"><i class="fa-solid fa-list-ul w-4 text-center"></i> Items Ordered</h4>
                                <ul class="space-y-1 text-gray-800 pl-6">
                                    <template x-for="item in order.items" :key="item.id + item.pricing.duration">
                                        <li><strong x-text="item.quantity + 'x'"></strong> <span x-text="item.name"></span> (<span x-text="item.pricing.duration"></span>)</li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        <!-- Column 2: Payment & Summary -->
                        <div class="space-y-4 border-t lg:border-t-0 lg:border-l lg:pl-6 pt-4 lg:pt-0">
                            <div>
                                    <h4 class="font-semibold text-gray-500 mb-1 flex items-center gap-2 text-xs uppercase tracking-wider"><i class="fa-solid fa-credit-card w-4 text-center"></i> Payment</h4>
                                    <div class="pl-6">
                                    <p class="text-gray-800 font-medium" x-text="order.payment.method"></p>
                                    <p class="text-gray-600" x-text="'TrxID: ' + order.payment.trx_id"></p>
                                </div>
                            </div>
                            <div class="border-t pt-4">
                                <h4 class="font-semibold text-gray-500 mb-2 flex items-center gap-2 text-xs uppercase tracking-wider"><i class="fa-solid fa-receipt w-4 text-center"></i> Summary</h4>
                                <div class="space-y-1 pl-6">
                                    <div class="flex justify-between text-gray-600"><span>Subtotal</span><span x-text="'৳' + order.totals.subtotal.toFixed(2)"></span></div>
                                    <template x-if="order.totals.discount > 0"><div class="flex justify-between text-green-600"><span>Discount (<span x-text="order.coupon.code || 'N/A'"></span>)</span><span x-text="'-৳' + order.totals.discount.toFixed(2)"></span></div></template>
                                    <div class="flex justify-between items-baseline font-bold text-gray-900 pt-1 border-t mt-1"><span>Total</span><span class="text-lg" x-text="'৳' + order.totals.total.toFixed(2)"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Final Order Card Design -->

            </template>
        </div>
    </template>
        <div class="mt-6 flex justify-between items-center" x-show="totalPages > 1">
        <button @click="prevPage" :disabled="currentPage === 1" class="btn btn-secondary" :class="{'opacity-50 cursor-not-allowed': currentPage === 1}"><i class="fa-solid fa-chevron-left"></i> Previous</button>
        <span class="text-sm font-medium text-gray-700">Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span></span>
        <button @click="nextPage" :disabled="currentPage === totalPages" class="btn btn-secondary" :class="{'opacity-50 cursor-not-allowed': currentPage === totalPages}">Next <i class="fa-solid fa-chevron-right"></i></button>
    </div>
</div>
