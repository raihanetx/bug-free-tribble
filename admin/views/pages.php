<div id="view-pages" style="<?= $current_view === 'pages' ? '' : 'display:none;' ?>">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Site Pages</h1>
    <form action="../api.php" method="POST" class="space-y-8">
        <input type="hidden" name="action" value="update_site_pages">
        <div class="card p-6"><h3 class="text-lg font-semibold mb-4 text-gray-800">About Us Page</h3><div><label for="about_us_content" class="block mb-1.5 font-medium text-gray-700 text-sm">Content</label><textarea id="about_us_content" name="site_pages[about_us]" class="form-textarea" rows="10"><?= htmlspecialchars($site_config['site_pages']['about_us'] ?? '') ?></textarea></div></div>
        <div class="card p-6"><h3 class="text-lg font-semibold mb-4 text-gray-800">Privacy Policy Page</h3><div><label for="privacy_policy_content" class="block mb-1.5 font-medium text-gray-700 text-sm">Content</label><textarea id="privacy_policy_content" name="site_pages[privacy_policy]" class="form-textarea" rows="10"><?= htmlspecialchars($site_config['site_pages']['privacy_policy'] ?? '') ?></textarea></div></div>
        <div class="card p-6"><h3 class="text-lg font-semibold mb-4 text-gray-800">Terms & Conditions Page</h3><div><label for="terms_content" class="block mb-1.5 font-medium text-gray-700 text-sm">Content</label><textarea id="terms_content" name="site_pages[terms_and_conditions]" class="form-textarea" rows="10"><?= htmlspecialchars($site_config['site_pages']['terms_and_conditions'] ?? '') ?></textarea></div></div>
            <div class="card p-6"><h3 class="text-lg font-semibold mb-4 text-gray-800">Refund Policy Page</h3><div><label for="refund_content" class="block mb-1.5 font-medium text-gray-700 text-sm">Content</label><textarea id="refund_content" name="site_pages[refund_policy]" class="form-textarea" rows="10"><?= htmlspecialchars($site_config['site_pages']['refund_policy'] ?? '') ?></textarea></div></div>
        <p class="text-sm text-gray-600">আপনি প্লেইন টেক্সট লিখতে পারেন। কোনো লেখাকে বোল্ড করতে চাইলে সেটির দুই পাশে দুটি করে স্টার দিন, যেমন: `**এই লেখাটি বোল্ড হবে**`। আপনার লেখা এবং লাইন ব্রেক ওয়েবসাইটে ঠিক সেভাবেই দেখাবে।</p>
        <div class="mt-6"><button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Save All Pages</button></div>
    </form>
</div>
