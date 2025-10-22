<div id="view-settings_homepage" style="<?= $current_view === 'settings_homepage' ? '' : 'display:none;' ?>">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Homepage Settings</h1>
    <div class="space-y-8 max-w-5xl">
            <div class="card p-6">
            <form action="../api.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_hero_banner">
                <h3 class="text-lg font-semibold mb-2 text-gray-800">Hero Section Banners (Slider)</h3>
                <p class="text-sm text-gray-600 mb-4">You can upload up to 10 images for the homepage slider.</p>
                    <div class="mb-6"><label for="hero_slider_interval" class="block mb-1.5 font-medium text-gray-700 text-sm">Slider Interval (in seconds)</label><input type="number" id="hero_slider_interval" name="hero_slider_interval" class="form-input max-w-xs" value="<?= htmlspecialchars(($site_config['hero_slider_interval'] ?? 5000) / 1000) ?>" placeholder="e.g., 5"></div>
                <div class="space-y-6">
                    <?php
                    $current_banners = $site_config['hero_banner'] ?? [];
                    for ($i = 0; $i < 10; $i++):
                        $banner_path = $current_banners[$i] ?? null;
                    ?>
                    <div class="p-4 border rounded-md bg-gray-50">
                        <label class="block font-medium text-gray-700 text-sm mb-2">Slider Image #<?= $i + 1 ?></label>
                        <?php if ($banner_path && file_exists('../' . $banner_path)): ?>
                            <div class="mb-2">
                                <img src="../<?= htmlspecialchars($banner_path) ?>" class="max-h-24 rounded border">
                                <div class="flex items-center gap-2 mt-2"><input type="checkbox" name="delete_hero_banners[<?= $i ?>]" id="delete_banner_<?= $i ?>" value="true" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500"><label for="delete_banner_<?= $i ?>" class="text-sm text-red-600 font-medium">Delete this image</label></div>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="hero_banners[<?= $i ?>]" class="form-input text-sm" accept="image/*">
                        <p class="text-xs text-gray-500 mt-1">Uploading an image here will replace the existing one for this slot.</p>
                    </div>
                    <?php endfor; ?>
                </div>
                <button type="submit" class="btn btn-primary mt-6"><i class="fa-solid fa-floppy-disk"></i> Save Banner Settings</button>
            </form>
        </div>
    </div>
</div>
