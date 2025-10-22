<?php
$all_data = get_data($products_file_path);
$all_data = array_values(array_filter($all_data, fn($cat) => $cat['name'] !== $_POST['name']));
save_data($products_file_path, $all_data);
$redirect_url = 'admin/admin.php?view=categories';
