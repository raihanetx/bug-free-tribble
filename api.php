<?php
// api.php - Handles all backend logic for categories, products, coupons, and orders

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require_once 'admin/actions/functions.php';

session_start();

// --- File Paths ---
$products_file_path = 'products.json';
$coupons_file_path = 'coupons.json';
$orders_file_path = 'orders.json';
$config_file_path = 'config.json';
$hotdeals_file_path = 'hotdeals.json';
$customers_file_path = 'customers.json';
$upload_dir = 'uploads/';

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] === 'get_orders_by_ids' && isset($_GET['ids'])) {
        $order_ids_to_find = json_decode($_GET['ids'], true);
        if (is_array($order_ids_to_find)) {
            $all_orders = get_data($orders_file_path);
            $found_orders = array_filter($all_orders, fn($order) => in_array($order['order_id'], $order_ids_to_find));
            header('Content-Type: application/json');
            echo json_encode(array_values($found_orders));
        } else {
            header('Content-Type: application/json', true, 400);
            echo json_encode([]);
        }
        exit;
    }
    if ($_GET['action'] === 'get_customers') {
        header('Content-Type: application/json');
        echo json_encode(get_data($customers_file_path));
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    $json_data = null;
    
    if (!$action) {
        $json_data = json_decode(file_get_contents('php://input'), true);
        $action = $json_data['action'] ?? null;
    }
    
    if (!$action) {
        http_response_code(400);
        echo "Action not specified.";
        exit;
    }

    $admin_actions = [
        'add_category', 'delete_category', 'edit_category', 
        'add_product', 'delete_product', 'edit_product', 
        'add_coupon', 'delete_coupon', 
        'update_review_status', 'update_order_status',
        'update_hero_banner', 'update_favicon', 'update_currency_rate', 
        'update_contact_info', 'update_admin_password', 'update_site_logo',
        'update_hot_deals', 'update_smtp_settings', 'send_manual_email', 'update_site_pages',
        'add_payment_method', 'edit_payment_method', 'delete_payment_method', 'toggle_payment_method_status',
        'add_customer_from_order', 'delete_customer'
    ];

    $public_actions = ['place_order', 'add_review'];

    if (in_array($action, $admin_actions)) {
        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
            http_response_code(403);
            echo "Forbidden: You must be logged in to perform this action.";
            exit;
        }
    } elseif (!in_array($action, $public_actions)) {
        http_response_code(400);
        echo "Invalid action.";
        exit;
    }

    $action_file = "admin/actions/{$action}.php";
    if (file_exists($action_file)) {
        require $action_file;
    } else {
        http_response_code(400);
        echo "Invalid action.";
        exit;
    }

    if (in_array($action, $admin_actions)) {
        header('Location: ' . $redirect_url);
    }
    exit;
}

http_response_code(403);
echo "Invalid Access";
