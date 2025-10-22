<!-- Product Management View -->
<a href="admin.php?view=categories" class="inline-flex items-center gap-2 mb-6 text-gray-600 font-semibold hover:text-[var(--primary-color)] transition-colors">
    <i class="fa-solid fa-arrow-left"></i> Back to Categories
</a>
<h1 class="text-3xl font-bold text-gray-800 mb-6">Manage Products: <span class="text-[var(--primary-color)]"><?= htmlspecialchars($category_to_manage['name']) ?></span></h1>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    <div class="lg:col-span-1 card p-6">
            <h2 class="text-xl font-bold text-gray-700 mb-4">Add New Product</h2>
        <form action="../api.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="action" value="add_product">
            <input type="hidden" name="category_name" value="<?= htmlspecialchars($category_to_manage['name']) ?>">
            <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Product Name</label><input type="text" name="name" class="form-input" required></div>
            <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Short Description</label><textarea name="description" class="form-textarea" rows="3" required></textarea></div>
            <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Long Description</label><textarea name="long_description" class="form-textarea" rows="5"></textarea><p class="text-xs text-gray-500 mt-1">Use **text** to make text bold.</p></div>
            <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Pricing Type</label><select id="pricing-type" class="form-select"><option value="single">Single Price</option><option value="multiple">Multiple Durations</option></select></div>
            <div id="single-price-container"><label class="block mb-1.5 font-medium text-gray-700 text-sm">Price (৳)</label><input type="number" name="price" step="0.01" class="form-input" value="0.00"></div>
            <div id="multiple-pricing-container" class="space-y-3 hidden"><label class="block font-medium text-gray-700 text-sm">Durations & Prices</label><div id="duration-fields"></div><button type="button" id="add-duration-btn" class="btn btn-secondary btn-sm"><i class="fa-solid fa-plus"></i> Add Duration</button></div>
            <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Product Image</label><input type="file" name="image" class="form-input" accept="image/*"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block mb-1.5 font-medium text-gray-700 text-sm">Stock Status</label><select name="stock_out" class="form-select"><option value="false">In Stock</option><option value="true">Out of Stock</option></select></div>
                <div class="pt-7"><label class="flex items-center gap-2"><input type="checkbox" name="featured" id="featured" value="true" class="h-4 w-4 rounded border-gray-300 text-[var(--primary-color)] focus:ring-[var(--primary-color)]"> Featured?</label></div>
            </div>
            <button type="submit" class="btn btn-primary w-full mt-2"><i class="fa-solid fa-circle-plus"></i>Add Product</button>
        </form>
    </div>
    <div class="lg:col-span-2 card p-6">
        <h2 class="text-xl font-bold text-gray-700 mb-4">Existing Products</h2>
        <div class="space-y-3">
            <?php if (empty($category_to_manage['products'])): ?>
                <p class="text-gray-500 text-center py-10">No products found in this category.</p>
            <?php else: ?>
                <?php foreach ($category_to_manage['products'] as $product): ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border flex-wrap gap-4">
                    <div class="flex items-center gap-4 flex-grow">
                        <img src="../<?= htmlspecialchars($product['image'] ? $product['image'] : 'https://via.placeholder.com/64/E9D5FF/5B21B6?text=N/A') ?>" class="w-16 h-16 object-cover rounded-md bg-gray-200">
                        <div>
                            <p class="font-semibold text-gray-800"><?= htmlspecialchars($product['name']) ?></p>
                            <p class="text-sm text-gray-600 font-semibold text-[var(--primary-color)]">৳<?= number_format($product['pricing'][0]['price'], 2) ?></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <a href="edit_product.php?category=<?= urlencode($category_to_manage['name']) ?>&id=<?= $product['id'] ?>" class="btn btn-secondary btn-sm"><i class="fa-solid fa-pencil"></i> Edit</a>
                        <form action="../api.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                            <input type="hidden" name="action" value="delete_product">
                            <input type="hidden" name="category_name" value="<?= htmlspecialchars($category_to_manage['name']) ?>">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i></button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
