<?php
$config = get_data($config_file_path);
if (isset($_POST['hero_slider_interval'])) {
    $config['hero_slider_interval'] = (int)$_POST['hero_slider_interval'] * 1000;
}
$current_banners = $config['hero_banner'] ?? [];
if (!is_array($current_banners)) {
    $current_banners = $current_banners ? [$current_banners] : [];
}
if (isset($_POST['delete_hero_banners']) && is_array($_POST['delete_hero_banners'])) {
    foreach ($_POST['delete_hero_banners'] as $index => $value) {
        if ($value === 'true' && isset($current_banners[$index])) {
            if (file_exists($current_banners[$index]) && is_file($current_banners[$index])) {
                unlink($current_banners[$index]);
            }
            $current_banners[$index] = null;
        }
    }
}
$max_banners = 10;
for ($i = 0; $i < $max_banners; $i++) {
    if (isset($_FILES['hero_banners']['tmp_name'][$i]) && is_uploaded_file($_FILES['hero_banners']['tmp_name'][$i])) {
        if (isset($current_banners[$i]) && file_exists($current_banners[$i]) && is_file($current_banners[$i])) {
            unlink($current_banners[$i]);
        }
        $file_to_upload = [
            'name' => $_FILES['hero_banners']['name'][$i],
            'type' => $_FILES['hero_banners']['type'][$i],
            'tmp_name' => $_FILES['hero_banners']['tmp_name'][$i],
            'error' => $_FILES['hero_banners']['error'][$i],
            'size' => $_FILES['hero_banners']['size'][$i]
        ];
        $destination = handle_image_upload($file_to_upload, $upload_dir, 'hero-');
        if ($destination) {
            $current_banners[$i] = $destination;
        }
    }
}
$config['hero_banner'] = array_values(array_filter($current_banners));
save_data($config_file_path, $config);
$redirect_url = 'admin/admin.php?view=settings_homepage';
