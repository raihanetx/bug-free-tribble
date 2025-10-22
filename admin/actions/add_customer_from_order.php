<?php
$order_id = $_POST['order_id'];
$all_orders = get_data($orders_file_path);
$order_to_add = null;
foreach ($all_orders as $order) {
    if ($order['order_id'] == $order_id) {
        $order_to_add = $order;
        break;
    }
}

if ($order_to_add) {
    $customers = get_data($customers_file_path);
    $customer_phone = $order_to_add['customer']['phone'];
    $customer_name = $order_to_add['customer']['name'];
    $customer_index = -1;

    foreach ($customers as $index => $customer) {
        if ($customer['phone'] === $customer_phone) {
            $customer_index = $index;
            break;
        }
    }

    if ($customer_index === -1) {
        $customers[] = [
            'name' => $customer_name,
            'phone' => $customer_phone,
            'products' => []
        ];
        $customer_index = count($customers) - 1;
    }

    $order_already_processed = false;
    if (isset($customers[$customer_index]['products']) && is_array($customers[$customer_index]['products'])) {
        foreach ($customers[$customer_index]['products'] as $product) {
            if (isset($product['order_id']) && $product['order_id'] == $order_id) {
                $order_already_processed = true;
                break;
            }
        }
    }

    if (!$order_already_processed) {
        if (!isset($customers[$customer_index]['products']) || !is_array($customers[$customer_index]['products'])) {
            $customers[$customer_index]['products'] = [];
        }

        foreach ($order_to_add['items'] as $item) {
            $renewal_date = 'N/A';
            $duration = $item['pricing']['duration'];
            if (preg_match('/(\d+)\s+(day|week|month|year)s?/i', $duration, $matches)) {
                $value = (int)$matches[1];
                $unit = strtolower($matches[2]);
                try {
                    $date = new DateTime($order_to_add['order_date']);
                    $date->modify("+$value $unit");
                    $renewal_date = $date->format('Y-m-d');
                } catch (Exception $e) {
                    $renewal_date = 'N/A';
                }
            }

            $customers[$customer_index]['products'][] = [
                'name' => $item['name'],
                'purchase_date' => date('Y-m-d', strtotime($order_to_add['order_date'])),
                'renewal_date' => $renewal_date,
                'order_id' => $order_id
            ];
        }
        save_data($customers_file_path, $customers);
    }
}
$redirect_url = 'admin/admin.php?view=orders';
