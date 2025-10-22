<?php
function parse_pricing_data() {
    $p = [];
    if (!empty($_POST['durations'])) {
        for ($i = 0; $i < count($_POST['durations']); $i++) {
            $p[] = ['duration' => htmlspecialchars(trim($_POST['durations'][$i])), 'price' => (float)$_POST['duration_prices'][$i]];
        }
    } else {
        $p[] = ['duration' => 'Default', 'price' => (float)$_POST['price']];
    }
    return $p;
}

function sanitize_description($desc) {
    return str_replace(['<', '>'], ['&lt;', '&gt;'], $desc);
}

$all_data = get_data($products_file_path);
$image_path = handle_image_upload($_FILES['image'] ?? null, $upload_dir, 'product-');
$name = htmlspecialchars(trim($_POST['name']));
$long_description_safe = sanitize_description(trim($_POST['long_description'] ?? ''));

$new_product = [
    'id' => time() . rand(100, 999),
    'name' => $name,
    'slug' => slugify($name),
    'description' => htmlspecialchars(trim($_POST['description'])),
    'long_description' => $long_description_safe,
    'image' => $image_path ?? '',
    'pricing' => parse_pricing_data(),
    'stock_out' => ($_POST['stock_out'] ?? 'false') === 'true',
    'featured' => isset($_POST['featured']),
    'reviews' => []
];

foreach ($all_data as &$category) {
    if ($category['name'] === $_POST['category_name']) {
        if (!isset($category['products']) || !is_array($category['products'])) {
            $category['products'] = [];
        }
        $category['products'][] = $new_product;
        break;
    }
}
unset($category);

save_data($products_file_path, $all_data);
$redirect_url = 'admin/admin.php?category=' . urlencode($_POST['category_name']);
