<?php
$config = get_data($config_file_path);
if (isset($_POST['delete_site_logo']) && !empty($config['site_logo'])) {
    if (file_exists($config['site_logo']) && is_file($config['site_logo'])) unlink($config['site_logo']);
    $config['site_logo'] = '';
}
if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
    if (!empty($config['site_logo']) && file_exists($config['site_logo']) && is_file($config['site_logo'])) unlink($config['site_logo']);
    $destination = handle_image_upload($_FILES['site_logo'], $upload_dir, 'logo-');
    if($destination) $config['site_logo'] = $destination;
}
save_data($config_file_path, $config);
$redirect_url = 'admin/admin.php?view=settings_general';
