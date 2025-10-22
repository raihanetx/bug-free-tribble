<?php
$all_data = get_data($products_file_path);
for ($i = 0; $i < count($all_data); $i++) {
    if ($all_data[$i]['name'] === $_POST['category_name']) {
        foreach($all_data[$i]['products'] as $p) {
            if ($p['id'] == $_POST['product_id'] && !empty($p['image']) && file_exists($p['image'])) {
                unlink($p['image']);
                break;
            }
        }
        $all_data[$i]['products'] = array_values(array_filter($all_data[$i]['products'], fn($prod) => $prod['id'] != $_POST['product_id']));
        break;
    }
}
save_data($products_file_path, $all_data);
$redirect_url = 'admin/admin.php?category=' . urlencode($_POST['category_name']);
