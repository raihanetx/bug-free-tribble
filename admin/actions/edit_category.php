<?php
$all_data = get_data($products_file_path);
$old_name = $_POST['original_name'];
$new_name = htmlspecialchars(trim($_POST['name']));
$new_icon = htmlspecialchars(trim($_POST['icon']));
$new_slug = slugify($new_name);

foreach ($all_data as &$category) {
    if ($category['name'] === $old_name) {
        $category['name'] = $new_name;
        $category['slug'] = $new_slug;
        $category['icon'] = $new_icon;
        break;
    }
}
unset($category);

$all_coupons = get_data($coupons_file_path);
foreach ($all_coupons as &$coupon) {
    if (($coupon['scope'] ?? '') === 'category' && $coupon['scope_value'] === $old_name) {
        $coupon['scope_value'] = $new_name;
    }
}
unset($coupon);

save_data($products_file_path, $all_data);
save_data($coupons_file_path, $all_coupons);

$redirect_url = 'admin/admin.php?view=categories';
