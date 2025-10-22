<div id="view-settings_general" style="<?= $current_view === 'settings_general' ? '' : 'display:none;' ?>">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">General Settings</h1>
    <div class="space-y-8 max-w-5xl">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <form action="../api.php" method="POST" enctype="multipart/form-data" class="card p-6">
                <input type="hidden" name="action" value="update_site_logo">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Site Logo</h3>
                <?php if (!empty($site_config['site_logo']) && file_exists('../' . $site_config['site_logo'])): ?>
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-600 mb-2">Current Logo:</p>
                        <img src="../<?= htmlspecialchars($site_config['site_logo']) ?>" class="h-10 bg-gray-200 p-1 rounded-md border shadow-sm">
                        <div class="flex items-center gap-2 mt-3"><input type="checkbox" name="delete_site_logo" id="delete_site_logo" value="true" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500"><label for="delete_site_logo" class="text-sm text-red-600 font-medium">Delete current logo</label></div>
                    </div>
                <?php endif; ?>
                <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Upload New Logo</label><input type="file" name="site_logo" class="form-input" accept="image/*"></div>
                <button type="submit" class="btn btn-primary mt-4"><i class="fa-solid fa-floppy-disk"></i> Save Logo</button>
            </form>
            <form action="../api.php" method="POST" enctype="multipart/form-data" class="card p-6">
                <input type="hidden" name="action" value="update_favicon">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Site Favicon</h3>
                    <?php if (!empty($site_config['favicon']) && file_exists('../' . $site_config['favicon'])): ?>
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-600 mb-2">Current Favicon:</p>
                        <img src="../<?= htmlspecialchars($site_config['favicon']) ?>" class="h-10 w-10 rounded-md border shadow-sm">
                        <div class="flex items-center gap-2 mt-3"><input type="checkbox" name="delete_favicon" id="delete_favicon" value="true" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500"><label for="delete_favicon" class="text-sm text-red-600 font-medium">Delete current favicon</label></div>
                    </div>
                <?php endif; ?>
                <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Upload New Favicon (.png, .ico)</label><input type="file" name="favicon" class="form-input" accept="image/png, image/x-icon"></div>
                <button type="submit" class="btn btn-primary mt-4"><i class="fa-solid fa-floppy-disk"></i> Save Favicon</button>
            </form>
        </div>
    </div>
</div>
