<?php
$phone_to_delete = $_POST['customer_phone'];
$all_customers = get_data($customers_file_path);

$updated_customers = array_values(array_filter($all_customers, function($customer) use ($phone_to_delete) {
    return $customer['phone'] !== $phone_to_delete;
}));

save_data($customers_file_path, $updated_customers);
$redirect_url = 'admin/admin.php?view=customers';
