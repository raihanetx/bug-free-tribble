<?php
$all_coupons = get_data($coupons_file_path);
$all_coupons = array_values(array_filter($all_coupons, fn($c) => $c['id'] != $_POST['coupon_id']));
save_data($coupons_file_path, $all_coupons);
$redirect_url = 'admin/admin.php?view=coupons';
