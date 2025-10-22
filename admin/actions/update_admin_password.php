<?php
$config = get_data($config_file_path);
if (!empty(trim($_POST['new_password']))) {
    $config['admin_password'] = trim($_POST['new_password']);
}
save_data($config_file_path, $config);
$redirect_url = 'admin/admin.php?view=settings_security';
