<?php
$order_data = $json_data['order'];
$all_orders = get_data($orders_file_path);

// --- NEW: DUPLICATE TRANSACTION ID CHECK ---
$payment_method = $order_data['paymentInfo']['method'] ?? '';
$trx_id = $order_data['paymentInfo']['trx_id'] ?? '';

foreach ($all_orders as $existing_order) {
    if (isset($existing_order['payment']['method'], $existing_order['payment']['trx_id']) &&
        $existing_order['payment']['method'] === $payment_method &&
        $existing_order['payment']['trx_id'] === $trx_id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'This Transaction ID has already been used.']);
        exit;
    }
}
// --- END: DUPLICATE TRANSACTION ID CHECK ---

$trx_id_patterns = [
    'bKash'       => '/^(?=.{10}$)(?=.*[A-Z])(?=.*\d)[A-Z0-9]+$/',
    'Upay'        => '/^(?=.{10}$)(?=.*[A-Z])(?=.*\d)[A-Z0-9]+$/',
    'Nagad'       => '/^(?=.*\d)(?=.*[A-Z])[A-Z0-9]{8}$/',
    'Rocket'      => '/^\d{10}$/',
    'Binance Pay' => '/^[A-Za-z0-9]{17}$/'
];

$is_valid_trx = true;
if (isset($trx_id_patterns[$payment_method])) {
    if (!preg_match($trx_id_patterns[$payment_method], $trx_id)) {
        $is_valid_trx = false;
    }
} elseif (empty(trim($trx_id))) {
    $is_valid_trx = false;
}

if (!$is_valid_trx) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please enter a valid Transaction ID.']);
    exit;
}

$subtotal = 0;
$all_products_data = get_data($products_file_path);
$all_products_flat = [];
foreach($all_products_data as $cat) {
    if(isset($cat['products'])) {
        foreach($cat['products'] as $p) {
            $p['category'] = $cat['name'];
            $all_products_flat[$p['id']] = $p;
        }
    }
}
foreach($order_data['items'] as $item) {
    $subtotal += $item['pricing']['price'] * $item['quantity'];
}
$discount = 0;
if (!empty($order_data['coupon']) && isset($order_data['coupon']['discount_percentage'])) {
    $coupon = $order_data['coupon'];
    $eligible_subtotal = 0;
    if (!isset($coupon['scope']) || $coupon['scope'] === 'all_products') {
        $eligible_subtotal = $subtotal;
    } else {
        foreach($order_data['items'] as $item) {
            $product_id = $item['id'];
            if (isset($all_products_flat[$product_id])) {
                $product_details = $all_products_flat[$product_id];
                if ($coupon['scope'] === 'category' && $product_details['category'] === $coupon['scope_value']) {
                    $eligible_subtotal += $item['pricing']['price'] * $item['quantity'];
                } elseif ($coupon['scope'] === 'single_product' && $product_id == $coupon['scope_value']) {
                    $eligible_subtotal += $item['pricing']['price'] * $item['quantity'];
                }
            }
        }
    }
    $discount = $eligible_subtotal * ($coupon['discount_percentage'] / 100);
}
$total = $subtotal - $discount;
$new_order = [
    'order_id' => time(),
    'order_date' => date('Y-m-d H:i:s'),
    'customer' => $order_data['customerInfo'],
    'payment' => $order_data['paymentInfo'],
    'items' => $order_data['items'],
    'coupon' => $order_data['coupon'] ?? [],
    'totals' => [
        'subtotal' => $subtotal,
        'discount' => $discount,
        'total' => $total,
    ],
    'status' => 'Pending',
];
$all_orders[] = $new_order;
save_data($orders_file_path, $all_orders);
$config = get_data($config_file_path);
$admin_email = $config['smtp_settings']['admin_email'] ?? '';
$email_subject = "New Order Received: #" . $new_order['order_id'];
$email_body = '<!DOCTYPE html><html><body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; padding: 20px;">';
$email_body .= '<div style="max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 5px;">';
$email_body .= '<h2>New Order Notification</h2>';
$email_body .= '<p>A new order has been placed on your website.</p>';
$email_body .= '<h3>Order Details:</h3>';
$email_body .= '<p><strong>Order ID:</strong> ' . $new_order['order_id'] . '</p>';
$email_body .= '<p><strong>Customer Name:</strong> ' . htmlspecialchars($new_order['customer']['name']) . '</p>';
$email_body .= '<p><strong>Customer Phone:</strong> ' . htmlspecialchars($new_order['customer']['phone']) . '</p>';
$email_body .= '<p><strong>Customer Email:</strong> ' . htmlspecialchars($new_order['customer']['email']) . '</p>';
$email_body .= '<h3>Items Ordered:</h3>';
$email_body .= '<table style="width: 100%; border-collapse: collapse;"><tr style="background-color: #f2f2f2;"><th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Product</th><th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Quantity</th><th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Price</th></tr>';
foreach($new_order['items'] as $item) {
    $email_body .= "<tr><td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($item['name']) . " (" . htmlspecialchars($item['pricing']['duration']) . ")</td><td style='padding: 8px; border: 1px solid #ddd;'>" . $item['quantity'] . "</td><td style='padding: 8px; border: 1px solid #ddd;'>৳" . number_format($item['pricing']['price'], 2) . "</td></tr>";
}
$email_body .= "</table>";
$email_body .= "<p style='text-align: right;'><strong>Subtotal:</strong> ৳" . number_format($new_order['totals']['subtotal'], 2) . "</p>";
if($new_order['totals']['discount'] > 0) {
    $email_body .= "<p style='text-align: right;'><strong>Discount:</strong> -৳" . number_format($new_order['totals']['discount'], 2) . "</p>";
}
$email_body .= "<p style='text-align: right; font-size: 1.1em;'><strong>Total:</strong> ৳" . number_format($new_order['totals']['total'], 2) . "</p>";
$email_body .= '<p>Please log in to the admin panel to review and process this order.</p>';
$email_body .= '</div></body></html>';
if(!empty($admin_email)) send_email($admin_email, $email_subject, $email_body, $config);
header('Content-Type: application/json');
echo json_encode(['success' => true, 'order_id' => $new_order['order_id'], 'message' => 'Order placed successfully!']);
exit;
