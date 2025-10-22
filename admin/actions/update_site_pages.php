<?php
$config = get_data($config_file_path);
if (isset($_POST['site_pages']) && is_array($_POST['site_pages'])) {
    $config['site_pages']['about_us'] = $_POST['site_pages']['about_us'] ?? '';
    $config['site_pages']['privacy_policy'] = $_POST['site_pages']['privacy_policy'] ?? '';
    $config['site_pages']['terms_and_conditions'] = $_POST['site_pages']['terms_and_conditions'] ?? '';
    $config['site_pages']['refund_policy'] = $_POST['site_pages']['refund_policy'] ?? '';
}
save_data($config_file_path, $config);
$redirect_url = 'admin/admin.php?view=pages';
