<?php
$product_id = $_POST['product_id'];
$review_id = $_POST['review_id'];
$new_status = $_POST['new_status'];
$all_products = get_data($products_file_path);
if ($new_status === 'deleted') {
    for ($i = 0; $i < count($all_products); $i++) {
        if (empty($all_products[$i]['products'])) continue;
        for ($j = 0; $j < count($all_products[$i]['products']); $j++) {
            if ($all_products[$i]['products'][$j]['id'] == $product_id) {
                $all_products[$i]['products'][$j]['reviews'] = array_values(
                    array_filter(
                        $all_products[$i]['products'][$j]['reviews'] ?? [],
                        fn($review) => $review['id'] !== $review_id
                    )
                );
                break 2;
            }
        }
    }
    save_data($products_file_path, $all_products);
}
$redirect_url = 'admin/admin.php?view=reviews';
