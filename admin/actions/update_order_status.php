<?php
$order_id = $_POST['order_id'];
$new_status = $_POST['new_status'];
$all_orders = get_data($orders_file_path);
foreach ($all_orders as &$order) {
    if ($order['order_id'] == $order_id) {
        $order['status'] = $new_status;
        break;
    }
}
save_data($orders_file_path, $all_orders);
$redirect_url = 'admin/admin.php?view=orders';
