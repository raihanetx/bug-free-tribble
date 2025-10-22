<?php
$all_data = get_data($products_file_path);
$name = htmlspecialchars(trim($_POST['name']));
$all_data[] = [
    'name' => $name,
    'slug' => slugify($name),
    'icon' => htmlspecialchars(trim($_POST['icon'])),
    'products' => []
];
save_data($products_file_path, $all_data);
$redirect_url = 'admin/admin.php?view=categories';
