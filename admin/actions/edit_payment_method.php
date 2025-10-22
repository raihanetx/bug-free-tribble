<?php
$config = get_data($config_file_path);
$original_name = $_POST['original_method_name'];
$new_name = htmlspecialchars(trim($_POST['new_method_name']));

if (isset($config['payment_methods'][$original_name])) {
    $method_data = $config['payment_methods'][$original_name];

    if (isset($_POST['delete_logo']) && !empty($method_data['logo_url']) && file_exists($method_data['logo_url'])) {
        unlink($method_data['logo_url']);
        $method_data['logo_url'] = '';
    }

    $new_logo = handle_image_upload($_FILES['logo'] ?? null, $upload_dir, 'payment-');
    if ($new_logo) {
        if (!empty($method_data['logo_url']) && file_exists($method_data['logo_url'])) {
            unlink($method_data['logo_url']);
        }
        $method_data['logo_url'] = $new_logo;
    }

    unset($method_data['number'], $method_data['pay_id'], $method_data['account_number']);

    if (isset($_POST['number'])) {
        $method_data['number'] = htmlspecialchars(trim($_POST['number']));
    } elseif (isset($_POST['pay_id'])) {
        $method_data['pay_id'] = htmlspecialchars(trim($_POST['pay_id']));
    } elseif (isset($_POST['account_number'])) {
        $method_data['account_number'] = htmlspecialchars(trim($_POST['account_number']));
    }

    unset($config['payment_methods'][$original_name]);
    $config['payment_methods'][$new_name] = $method_data;
    save_data($config_file_path, $config);
}
$redirect_url = 'admin/admin.php?view=settings_payment';
