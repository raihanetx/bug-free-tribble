<?php
$config = get_data($config_file_path);
$method_name = htmlspecialchars(trim($_POST['method_name']));
if (!empty($method_name) && !isset($config['payment_methods'][$method_name])) {
    $logo_path = handle_image_upload($_FILES['logo'] ?? null, $upload_dir, 'payment-');
    $new_method = [
        'logo_url' => $logo_path ?? '',
        'is_active' => isset($_POST['is_active']),
        'is_default' => false
    ];

    if ($_POST['method_type'] === 'number') {
        $new_method['number'] = htmlspecialchars(trim($_POST['number_or_id']));
    } elseif ($_POST['method_type'] === 'pay_id') {
        $new_method['pay_id'] = htmlspecialchars(trim($_POST['number_or_id']));
    } elseif ($_POST['method_type'] === 'account_number') {
        $new_method['account_number'] = htmlspecialchars(trim($_POST['number_or_id']));
    }

    $config['payment_methods'][$method_name] = $new_method;
    save_data($config_file_path, $config);
}
$redirect_url = 'admin/admin.php?view=settings_payment';
