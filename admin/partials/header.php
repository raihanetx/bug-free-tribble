<?php
session_start();

// --- Security Check ---
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../login.php');
    exit;
}

// --- Load All Data ---
$products_file_path = '../products.json';
if (!file_exists($products_file_path)) file_put_contents($products_file_path, '[]');
$all_products_data = json_decode(file_get_contents($products_file_path), true);

$coupons_file_path = '../coupons.json';
if (!file_exists($coupons_file_path)) file_put_contents($coupons_file_path, '[]');
$all_coupons_data = json_decode(file_get_contents($coupons_file_path), true);

$orders_file_path = '../orders.json';
if (!file_exists($orders_file_path)) file_put_contents($orders_file_path, '[]');
$all_orders_data_raw = json_decode(file_get_contents($orders_file_path), true);
if(!empty($all_orders_data_raw)) {
    usort($all_orders_data_raw, fn($a, $b) => $b['order_id'] <=> $a['order_id']);
}

$customers_file_path = '../customers.json';
if (!file_exists($customers_file_path)) file_put_contents($customers_file_path, '[]');
$all_customers_data = json_decode(file_get_contents($customers_file_path), true);
$customer_phones = !empty($all_customers_data) ? array_column($all_customers_data, 'phone') : [];

$config_file_path = '../config.json';
if (!file_exists($config_file_path)) file_put_contents($config_file_path, '{"hero_banner":[],"favicon":"","contact_info":{"phone":"","whatsapp":"","email":""},"admin_password":"password123", "usd_to_bdt_rate": 110, "site_logo":"", "hero_slider_interval": 5000, "hot_deals_speed": 40, "payment_methods":{}, "smtp_settings": {}}');
$site_config = json_decode(file_get_contents($config_file_path), true);

$hotdeals_file_path = '../hotdeals.json';
if (!file_exists($hotdeals_file_path)) file_put_contents($hotdeals_file_path, '[]');
$all_hotdeals_data = json_decode(file_get_contents($hotdeals_file_path), true);

function calculate_stats($orders, $days = null) {
    $filtered_orders = $orders;
    if ($days !== null) {
        $cutoff_date = new DateTime();
        if ($days == 0) {
             $cutoff_date->setTime(0, 0, 0);
        } else {
             $cutoff_date->modify("-{$days} days");
        }
        $filtered_orders = array_filter($orders, function ($order) use ($cutoff_date) {
            $order_date = new DateTime($order['order_date']);
            return $order_date >= $cutoff_date;
        });
    }

    $stats = [
        'total_revenue' => 0,
        'total_orders' => count($filtered_orders),
        'pending_orders' => 0,
        'confirmed_orders' => 0,
        'cancelled_orders' => 0,
    ];

    foreach ($filtered_orders as $order) {
        if ($order['status'] === 'Confirmed') {
            $stats['total_revenue'] += $order['totals']['total'];
            $stats['confirmed_orders']++;
        } elseif ($order['status'] === 'Pending') {
            $stats['pending_orders']++;
        } elseif ($order['status'] === 'Cancelled') {
            $stats['cancelled_orders']++;
        }
    }
    return $stats;
}

$stats_today = calculate_stats($all_orders_data_raw, 0);
$stats_7_days = calculate_stats($all_orders_data_raw, 7);
$stats_30_days = calculate_stats($all_orders_data_raw, 30);
$stats_6_months = calculate_stats($all_orders_data_raw, 180);
$stats_all_time = calculate_stats($all_orders_data_raw);

$pending_orders_count = count(array_filter($all_orders_data_raw, fn($o) => $o['status'] === 'Pending'));

$category_to_manage = null;
if (isset($_GET['category'])) {
    foreach ($all_products_data as $category) {
        if ($category['name'] === $_GET['category']) {
            $category_to_manage = $category;
            break;
        }
    }
}

$all_reviews = [];
$all_products_for_js = [];
if (!empty($all_products_data)) {
    foreach ($all_products_data as $category) {
        if (isset($category['products']) && is_array($category['products'])) {
            foreach ($category['products'] as $product) {
                $all_products_for_js[] = ['id' => $product['id'], 'name' => $product['name'], 'category' => $category['name']];
                if (isset($product['reviews']) && is_array($product['reviews'])) {
                    foreach ($product['reviews'] as $review) {
                        $review['product_id'] = $product['id'];
                        $review['product_name'] = $product['name'];
                        $all_reviews[] = $review;
                    }
                }
            }
        }
    }
}
if (!empty($all_reviews)) {
    usort($all_reviews, fn($a, $b) => strcmp($b['id'], $a['id']));
}

$current_view = $_GET['view'] ?? 'orders';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root { --primary-color: #6D28D9; --primary-color-darker: #5B21B6; }
        body { font-family: 'Inter', sans-serif; }
        .form-input, .form-select, .form-textarea { width: 100%; border-radius: 0.5rem; border: 1px solid #d1d5db; padding: 0.6rem 0.8rem; transition: all 0.2s ease-in-out; background-color: #F9FAFB; }
        .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--primary-color); box-shadow: 0 0 0 2px #E9D5FF; outline: none; background-color: white; }
        .btn { padding: 0.6rem 1.2rem; border-radius: 0.5rem; font-weight: 600; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; }
        .btn-primary { background-color: var(--primary-color); color: white; } .btn-primary:hover { background-color: var(--primary-color-darker); }
        .btn-secondary { background-color: #f3f4f6; color: #374151; border: 1px solid #d1d5db; } .btn-secondary:hover { background-color: #e5e7eb; }
        .btn-danger { background-color: #fee2e2; color: #b91c1c; } .btn-danger:hover { background-color: #fecaca; color: #991b1b; }
        .btn-success { background-color: #dcfce7; color: #166534; } .btn-success:hover { background-color: #bbf7d0; }
        .btn-sm { padding: 0.4rem 0.8rem; font-size: 0.875rem; }
        .stats-filter-btn { padding: 0.5rem 1rem; border-radius: 9999px; font-weight: 500; transition: all 0.2s; border: 1px solid transparent; }
        .stats-filter-btn.active { background-color: var(--primary-color); color: white; }
        .stats-filter-btn:not(.active) { background-color: #f3f4f6; color: #374151; }
        .stats-filter-btn:not(.active):hover { background-color: #e5e7eb; }
        .card { background-color: white; border-radius: 0.75rem; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05), 0 1px 2px -1px rgb(0 0 0 / 0.05); border: 1px solid #e5e7eb; }
        .hidden { display: none; }
        [x-cloak] { display: none !important; }

        /* Sidebar Styles */
        .sidebar-link { display: flex; align-items: center; padding: 0.75rem 1rem; border-radius: 0.5rem; font-weight: 500; color: #4b5563; transition: all 0.2s; }
        .sidebar-link:hover { background-color: #f3f4f6; color: #1f2937; }
        .sidebar-link.active { color: var(--primary-color); font-weight: 600; }
        .sidebar-link i { width: 1.5rem; margin-right: 0.75rem; text-align: center; }
        .sidebar-minimized .sidebar-link { justify-content: center; }
        .sidebar-minimized .sidebar-link i { margin-right: 0; }
        .sidebar-minimized .nav-text, .sidebar-minimized .settings-text, .sidebar-minimized .logout-text, .sidebar-minimized .admin-panel-text { display: none; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen" x-data="{ sidebarMinimized: false, ...adminManager() }" :class="{'sidebar-minimized': sidebarMinimized}">
