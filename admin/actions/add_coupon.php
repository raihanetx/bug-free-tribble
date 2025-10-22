<?php
$all_coupons = get_data($coupons_file_path);
$scope = $_POST['scope'] ?? 'all_products';
$scope_value = null;
if ($scope === 'category') {
    $scope_value = $_POST['scope_value_category'] ?? null;
} elseif ($scope === 'single_product') {
    $scope_value = $_POST['scope_value_product'] ?? null;
}
$all_coupons[] = [
    'id' => time() . rand(100, 999),
    'code' => strtoupper(htmlspecialchars(trim($_POST['code']))),
    'discount_percentage' => (int)$_POST['discount_percentage'],
    'is_active' => isset($_POST['is_active']),
    'scope' => $scope,
    'scope_value' => $scope_value
];
save_data($coupons_file_path, $all_coupons);
$redirect_url = 'admin/admin.php?view=coupons';
