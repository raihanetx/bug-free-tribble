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
for ($i = 0; $i < count($all_data); $i++) {
    if ($all_data[$i]['name'] === $_POST['category_name']) {
        for ($j = 0; $j < count($all_data[$i]['products']); $j++) {
            if ($all_data[$i]['products'][$j]['id'] == $_POST['product_id']) {
                $cp = &$all_data[$i]['products'][$j];
                if (isset($_POST['delete_image']) && !empty($cp['image']) && file_exists($cp['image'])) {
                    unlink($cp['image']);
                    $cp['image'] = '';
                }
                $nip = handle_image_upload($_FILES['image'] ?? null, 'uploads/', 'product-');
                if ($nip) {
                    if (!empty($cp['image']) && file_exists($cp['image'])) {
                        unlink($cp['image']);
                    }
                    $cp['image'] = $nip;
                }
                $name = htmlspecialchars(trim($_POST['name']));
                $cp['name'] = $name;
                $cp['slug'] = slugify($name);
                $cp['description'] = htmlspecialchars(trim($_POST['description']));
                $cp['long_description'] = sanitize_description(trim($_POST['long_description'] ?? ''));
                $cp['pricing'] = parse_pricing_data();
                $cp['stock_out'] = $_POST['stock_out'] === 'true';
                $cp['featured'] = isset($_POST['featured']);
                break 2;
            }
        }
    }
}

save_data($products_file_path, $all_data);
$redirect_url = 'admin/admin.php?category=' . urlencode($_POST['category_name']);
