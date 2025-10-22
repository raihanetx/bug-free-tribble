<?php
$config = get_data($config_file_path);
$method_name = $_POST['method_name'];
if (isset($config['payment_methods'][$method_name])) {
    $method_data = $config['payment_methods'][$method_name];
    if (isset($method_data['is_default']) && $method_data['is_default']) {
    } else {
        if (!empty($method_data['logo_url']) && file_exists($method_data['logo_url'])) {
            unlink($method_data['logo_url']);
        }
        unset($config['payment_methods'][$method_name]);
        save_data($config_file_path, $config);
    }
}
$redirect_url = 'admin/admin.php?view=settings_payment';
