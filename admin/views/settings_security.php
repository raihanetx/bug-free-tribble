<div id="view-settings_security" style="<?= $current_view === 'settings_security' ? '' : 'display:none;' ?>">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Security Settings</h1>
    <div class="space-y-8 max-w-5xl">
            <form action="../api.php" method="POST" class="card p-6">
            <input type="hidden" name="action" value="update_admin_password">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Change Admin Password</h3>
            <div>
                <label for="new_password_field" class="block mb-1.5 font-medium text-gray-700 text-sm">New Password</label>
                <div class="relative"><input type="password" id="new_password_field" name="new_password" class="form-input pr-16" placeholder="Leave blank to keep current password"><button type="button" id="toggle_password_btn" class="absolute top-1/2 right-2 -translate-y-1/2 text-xs font-semibold text-gray-600 bg-gray-200 hover:bg-gray-300 px-2 py-1 rounded-md transition-colors">Show</button></div>
            </div>
            <button type="submit" class="btn btn-primary mt-4"><i class="fa-solid fa-floppy-disk"></i> Save Password</button>
        </form>
    </div>
</div>
