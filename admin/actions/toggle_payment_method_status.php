<?php
$config = get_data($config_file_path);
$method_name = $_POST['method_name'];
if (isset($config['payment_methods'][$method_name])) {
    $config['payment_methods'][$method_name]['is_active'] = !$config['payment_methods'][$method_name]['is_active'];
    save_data($config_file_path, $config);
}
$redirect_url = 'admin/admin.php?view=settings_payment';
