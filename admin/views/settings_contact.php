<div id="view-settings_contact" style="<?= $current_view === 'settings_contact' ? '' : 'display:none;' ?>">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Contact & Email Settings</h1>
    <div class="space-y-8 max-w-5xl">
        <form action="../api.php" method="POST" class="card p-6">
            <input type="hidden" name="action" value="update_smtp_settings">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Email & SMTP Settings</h3>
            <div class="space-y-4">
                <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Admin Email Address</label><input type="email" name="admin_email" class="form-input" value="<?= htmlspecialchars($site_config['smtp_settings']['admin_email'] ?? '') ?>" placeholder="e.g., admin@yourdomain.com"><p class="text-xs text-gray-500 mt-1">This email receives new order notifications and is used to send emails to customers.</p></div>
                <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Gmail App Password</label><input type="password" name="app_password" class="form-input" placeholder="Leave blank to keep current password"><p class="text-xs text-gray-500 mt-1">Enter the 16-character App Password from your Google Account settings.</p></div>
            </div>
            <button type="submit" class="btn btn-primary mt-6"><i class="fa-solid fa-floppy-disk"></i> Save SMTP Settings</button>
        </form>
        <form action="../api.php" method="POST" class="card p-6">
            <input type="hidden" name="action" value="update_contact_info">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Help Center Contacts</h3>
            <div class="space-y-4">
                <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Phone Number</label><input type="text" name="phone_number" class="form-input" value="<?= htmlspecialchars($site_config['contact_info']['phone'] ?? '') ?>" placeholder="+8801234567890"></div>
                <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">WhatsApp Number</label><input type="text" name="whatsapp_number" class="form-input" value="<?= htmlspecialchars($site_config['contact_info']['whatsapp'] ?? '') ?>" placeholder="8801234567890 (without +)"></div>
                <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Email Address</label><input type="email" name="email_address" class="form-input" value="<?= htmlspecialchars($site_config['contact_info']['email'] ?? '') ?>" placeholder="contact@example.com"></div>
            </div>
                <button type="submit" class="btn btn-primary mt-4"><i class="fa-solid fa-floppy-disk"></i> Save Contacts</button>
        </form>
    </div>
</div>
