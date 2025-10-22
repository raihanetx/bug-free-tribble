<?php
$review_data = $json_data['review'];
$product_id = $review_data['productId'];
$all_products = get_data($products_file_path);
$product_found = false;
for ($i = 0; $i < count($all_products); $i++) {
    if (empty($all_products[$i]['products'])) continue;
    for ($j = 0; $j < count($all_products[$i]['products']); $j++) {
        if ($all_products[$i]['products'][$j]['id'] == $product_id) {
            if (!isset($all_products[$i]['products'][$j]['reviews'])) {
                $all_products[$i]['products'][$j]['reviews'] = [];
            }
            $new_review = [
                'id' => time() . '-' . rand(100, 999),
                'name' => htmlspecialchars($review_data['name']),
                'rating' => (int)$review_data['rating'],
                'comment' => htmlspecialchars($review_data['comment']),
            ];
            array_unshift($all_products[$i]['products'][$j]['reviews'], $new_review);
            $product_found = true;
            break 2;
        }
    }
}
if ($product_found) {
    save_data($products_file_path, $all_products);
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Review added successfully!']);
} else {
    header('Content-Type: application/json', true, 404);
    echo json_encode(['success' => false, 'message' => 'Product not found.']);
}
exit;
