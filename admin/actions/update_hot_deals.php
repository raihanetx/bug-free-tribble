<?php
$config = get_data($config_file_path);
if (isset($_POST['hot_deals_speed'])) {
    $config['hot_deals_speed'] = (int)$_POST['hot_deals_speed'];
}
save_data($config_file_path, $config);

$new_deals_data = [];
$selected_product_ids = $_POST['selected_deals'] ?? [];
foreach($selected_product_ids as $productId) {
    $custom_title = htmlspecialchars(trim($_POST['custom_titles'][$productId] ?? ''));
    $new_deals_data[] = [
        'productId' => $productId,
        'customTitle' => $custom_title
    ];
}
save_data($hotdeals_file_path, $new_deals_data);
$redirect_url = 'admin/admin.php?view=hotdeals';
