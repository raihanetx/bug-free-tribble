<!-- Modals -->
<div x-show="isModalOpen" x-cloak @keydown.escape.window="closeModal()" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
    <!-- Manual Email Modal -->
    <div x-show="modalType === 'email'" @click.away="closeModal()" class="bg-white rounded-lg shadow-xl w-full max-w-lg">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold text-gray-800">Send Access Details for Order #<span x-text="currentOrderId"></span></h3>
        </div>
        <form action="../api.php" method="POST" onsubmit="return confirm('Are you sure you want to send this email?');">
            <div class="p-6 space-y-4">
                <input type="hidden" name="action" value="send_manual_email">
                <input type="hidden" name="order_id" :value="currentOrderId">
                <input type="hidden" name="customer_email" :value="currentCustomerEmail">
                <div>
                    <label class="block mb-1.5 font-medium text-gray-700 text-sm">Access Details & Information</label>
                    <textarea name="access_details" class="form-textarea" rows="6" placeholder="Enter login details, product keys, download links, instructions, etc." required></textarea>
                    <p class="text-xs text-gray-500 mt-1">The customer will receive this text in their confirmation email.</p>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 rounded-b-lg">
                <button type="button" @click="closeModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Send Email</button>
            </div>
        </form>
    </div>

    <!-- Edit Payment Method Modal -->
    <div x-show="modalType === 'payment'" @click.away="closeModal()" class="bg-white rounded-lg shadow-xl w-full max-w-lg">
        <div class="p-6 border-b">
            <h3 class="text-xl font-bold text-gray-800">Edit Payment Method: <span x-text="editMethod.name"></span></h3>
        </div>
        <form action="../api.php" method="POST" enctype="multipart/form-data">
            <div class="p-6 space-y-4">
                <input type="hidden" name="action" value="edit_payment_method">
                <input type="hidden" name="original_method_name" :value="editMethod.name">
                <div>
                    <label class="block mb-1.5 font-medium text-gray-700 text-sm">Method Name</label>
                    <input type="text" name="new_method_name" class="form-input" required :value="editMethod.name" :readonly="editMethod.is_default">
                    <template x-if="editMethod.is_default"><p class="text-xs text-gray-500 mt-1">Default method names cannot be changed.</p></template>
                </div>
                <div x-show="editMethod.hasOwnProperty('number')"><label class="block mb-1.5 font-medium text-gray-700 text-sm">Number</label><input type="text" name="number" class="form-input" :value="editMethod.number"></div>
                <div x-show="editMethod.hasOwnProperty('pay_id')"><label class="block mb-1.5 font-medium text-gray-700 text-sm">ID</label><input type="text" name="pay_id" class="form-input" :value="editMethod.pay_id"></div>
                <div x-show="editMethod.hasOwnProperty('account_number')"><label class="block mb-1.5 font-medium text-gray-700 text-sm">Account Number</label><input type="text" name="account_number" class="form-input" :value="editMethod.account_number"></div>
                <div>
                    <label class="block mb-1.5 font-medium text-gray-700 text-sm">Logo</label>
                    <template x-if="editMethod.logo_url">
                        <div class="mb-2">
                            <img :src="editMethod.logo_url" class="h-16 w-16 border bg-white rounded-md object-contain">
                            <div class="flex items-center gap-2 mt-2"><input type="checkbox" name="delete_logo" id="delete_logo" value="true" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500"><label for="delete_logo" class="text-sm text-red-600 font-medium">Delete current logo</label></div>
                        </div>
                    </template>
                    <input type="file" name="logo" class="form-input text-sm" accept="image/*">
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 rounded-b-lg">
                <button type="button" @click="closeModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>

</div>
