<?php
$config = get_data($config_file_path);
if (isset($_POST['admin_email'])) {
    $config['smtp_settings']['admin_email'] = htmlspecialchars(trim($_POST['admin_email']));
}
if (!empty(trim($_POST['app_password']))) {
    $config['smtp_settings']['app_password'] = trim($_POST['app_password']);
}
save_data($config_file_path, $config);
$redirect_url = 'admin/admin.php?view=settings_contact';
