<div id="view-settings_payment" style="<?= $current_view === 'settings_payment' ? '' : 'display:none;' ?>">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Payment Settings</h1>
    <div class="space-y-8 max-w-5xl">
            <div class="card p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Payment Gateway Settings</h3>
            <div class="space-y-6">
                <?php
                $payment_methods_config = $site_config['payment_methods'] ?? [];
                foreach ($payment_methods_config as $method_name => $method_details):
                    $is_active = $method_details['is_active'] ?? false;
                    $is_default = $method_details['is_default'] ?? false;
                ?>
                <div class="p-4 border rounded-md bg-gray-50 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <?php if (!empty($method_details['logo_url']) && file_exists('../' . $method_details['logo_url'])): ?><img src="../<?= htmlspecialchars($method_details['logo_url']) ?>" class="h-16 w-16 border bg-white rounded-md object-contain"><?php endif; ?>
                        <div>
                            <h4 class="font-semibold text-gray-700"><?= htmlspecialchars($method_name) ?> <?php if($is_default): ?><span class="text-xs bg-gray-200 text-gray-600 font-bold px-2 py-0.5 rounded-full ml-2">Default</span><?php endif; ?></h4>
                            <p class="text-sm text-gray-500"><?= htmlspecialchars($method_details['number'] ?? $method_details['pay_id'] ?? $method_details['account_number'] ?? '') ?></p>
                            <span class="mt-2 inline-block text-sm font-bold py-0.5 px-2 rounded-full <?= $is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>"><?= $is_active ? 'Active' : 'Inactive' ?></span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <form action="../api.php" method="POST"><input type="hidden" name="action" value="toggle_payment_method_status"><input type="hidden" name="method_name" value="<?= htmlspecialchars($method_name) ?>"><button type="submit" class="btn btn-secondary btn-sm"><?= $is_active ? 'Deactivate' : 'Activate' ?></button></form>
                        <button type="button" @click="openPaymentModal('<?= htmlspecialchars($method_name, ENT_QUOTES) ?>')" class="btn btn-secondary btn-sm"><i class="fa-solid fa-pencil"></i> Edit</button>
                        <?php if(!$is_default): ?><form action="../api.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this payment method?');"><input type="hidden" name="action" value="delete_payment_method"><input type="hidden" name="method_name" value="<?= htmlspecialchars($method_name) ?>"><button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i></button></form><?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <hr class="my-6">
            <div class="bg-gray-50 p-6 rounded-lg border" x-data="{ type: 'number' }">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Add New Payment Method</h3>
                <form action="../api.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="action" value="add_payment_method">
                    <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Method Name</label><input type="text" name="method_name" class="form-input" required placeholder="e.g., Rocket"></div>
                    <div>
                        <label class="block mb-1.5 font-medium text-gray-700 text-sm">Method Type</label>
                        <div class="flex gap-4"><label class="flex items-center gap-2"><input type="radio" name="method_type" value="number" x-model="type" class="form-radio"> Number</label><label class="flex items-center gap-2"><input type="radio" name="method_type" value="pay_id" x-model="type" class="form-radio"> ID</label><label class="flex items-center gap-2"><input type="radio" name="method_type" value="account_number" x-model="type" class="form-radio"> Bank Account</label></div>
                    </div>
                    <div><label class="block mb-1.5 font-medium text-gray-700 text-sm" x-text="type === 'number' ? 'Number' : (type === 'pay_id' ? 'ID' : 'Account Number')">Number</label><input type="text" name="number_or_id" class="form-input" required></div>
                    <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Logo</label><input type="file" name="logo" class="form-input" accept="image/*"></div>
                        <div class="flex items-center gap-2 pt-2"><input type="checkbox" name="is_active" id="add_is_active" value="true" checked class="h-4 w-4 rounded border-gray-300 text-[var(--primary-color)] focus:ring-[var(--primary-color)]"><label for="add_is_active" class="text-sm font-medium text-gray-700">Activate on creation</label></div>
                    <button type="submit" class="btn btn-primary mt-4">Add Method</button>
                </form>
            </div>
        </div>
        <form action="../api.php" method="POST" class="card p-6">
            <input type="hidden" name="action" value="update_currency_rate">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Currency Settings</h3>
            <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">1 USD = ? BDT</label><input type="number" step="0.01" name="usd_to_bdt_rate" class="form-input" value="<?= htmlspecialchars($site_config['usd_to_bdt_rate'] ?? '110') ?>" placeholder="e.g., 110.50"></div>
            <button type="submit" class="btn btn-primary mt-4"><i class="fa-solid fa-floppy-disk"></i> Save Rate</button>
        </form>
    </div>
</div>
