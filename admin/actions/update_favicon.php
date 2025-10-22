<?php
$config = get_data($config_file_path);
if (isset($_POST['delete_favicon']) && !empty($config['favicon'])) {
    if (file_exists($config['favicon']) && is_file($config['favicon'])) unlink($config['favicon']);
    $config['favicon'] = '';
}
if (isset($_FILES['favicon']) && $_FILES['favicon']['error'] === UPLOAD_ERR_OK) {
    if (!empty($config['favicon']) && file_exists($config['favicon']) && is_file($config['favicon'])) unlink($config['favicon']);
    $destination = handle_image_upload($_FILES['favicon'], $upload_dir, 'favicon-');
    if($destination) $config['favicon'] = $destination;
}
save_data($config_file_path, $config);
$redirect_url = 'admin/admin.php?view=settings_general';
