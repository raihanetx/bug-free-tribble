<?php
$order_id = $_POST['order_id'];
$customer_email = $_POST['customer_email'];
$access_details = $_POST['access_details'];
$all_orders = get_data($orders_file_path);
$order_to_email = null;
$config = get_data($config_file_path);

foreach ($all_orders as &$order) {
    if ($order['order_id'] == $order_id) {
        $order_to_email = $order;
        break;
    }
}
unset($order);

if ($order_to_email) {
    $email_subject = "Your Submonth Order #" . $order_to_email['order_id'] . " is Confirmed!";
    $email_body = '<!DOCTYPE html><html><body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; padding: 20px;">';
    $email_body .= '<div style="max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 5px;">';
    $email_body .= '<h2>Your Order is Confirmed!</h2>';
    $email_body .= '<p>Dear ' . htmlspecialchars($order_to_email['customer']['name']) . ',</p>';
    $email_body .= '<p>Thank you for your purchase. Your order #' . $order_to_email['order_id'] . ' has been confirmed and your access details are below.</p>';
    $email_body .= '<h3>Your Access Details:</h3>';
    $email_body .= '<div style="padding: 15px; background-color: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 5px; margin: 15px 0; white-space: pre-wrap; font-family: monospace;">' . nl2br(htmlspecialchars($access_details)) . '</div>';
    $email_body .= '<h3>Order Summary:</h3>';
    $email_body .= '<table style="width: 100%; border-collapse: collapse;"><tr style="background-color: #f2f2f2;"><th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Product</th><th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Quantity</th><th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Price</th></tr>';
    foreach($order_to_email['items'] as $item) {
        $email_body .= "<tr><td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($item['name']) . " (" . htmlspecialchars($item['pricing']['duration']) . ")</td><td style='padding: 8px; border: 1px solid #ddd;'>" . $item['quantity'] . "</td><td style='padding: 8px; border: 1px solid #ddd;'>৳" . number_format($item['pricing']['price'], 2) . "</td></tr>";
    }
    $email_body .= "</table>";
    $email_body .= "<p style='text-align: right; font-size: 1.1em;'><strong>Total Paid:</strong> ৳" . number_format($order_to_email['totals']['total'], 2) . "</p>";
    $email_body .= '<p>If you have any questions, feel free to contact our support.</p>';
    $email_body .= '<p>Thank you for choosing Submonth!</p>';
    $email_body .= '</div></body></html>';

    if (send_email($customer_email, $email_subject, $email_body, $config)) {
        foreach ($all_orders as &$order_to_update) {
            if ($order_to_update['order_id'] == $order_id) {
                $order_to_update['access_email_sent'] = true;
                break;
            }
        }
        save_data($orders_file_path, $all_orders);
    }
}
$redirect_url = 'admin/admin.php?view=orders';
