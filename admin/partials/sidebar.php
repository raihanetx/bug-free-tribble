<!-- Vertical Sidebar -->
<aside class="w-64 flex-shrink-0 bg-white border-r border-gray-200 flex flex-col transition-all duration-300" :class="sidebarMinimized ? 'w-20' : 'w-64'">
    <div class="p-4 border-b flex items-center justify-between">
        <span class="admin-panel-text text-xl font-bold text-[var(--primary-color)]">Admin Panel</span>
        <button @click="sidebarMinimized = !sidebarMinimized" class="btn btn-secondary btn-sm !p-2">
            <i class="fa-solid" :class="sidebarMinimized ? 'fa-chevron-right' : 'fa-chevron-left'"></i>
        </button>
    </div>
    <nav class="flex-grow p-4 space-y-2">
        <a href="admin.php?view=orders" class="sidebar-link <?= $current_view === 'orders' ? 'active' : '' ?>"><i class="fa-solid fa-bag-shopping"></i><span class="nav-text">Orders</span> <?php if ($pending_orders_count > 0): ?><span class="nav-text ml-auto bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full px-2 py-0.5"><?= $pending_orders_count ?></span><?php endif; ?></a>
        <a href="admin.php?view=categories" class="sidebar-link <?= $current_view === 'categories' ? 'active' : '' ?>"><i class="fa-solid fa-list"></i><span class="nav-text">Categories</span></a>
        <a href="admin.php?view=hotdeals" class="sidebar-link <?= $current_view === 'hotdeals' ? 'active' : '' ?>"><i class="fa-solid fa-fire"></i><span class="nav-text">Hot Deals</span></a>
        <a href="admin.php?view=customers" class="sidebar-link <?= $current_view === 'customers' ? 'active' : '' ?>"><i class="fa-solid fa-users"></i><span class="nav-text">Customers</span></a>
        <a href="admin.php?view=reviews" class="sidebar-link <?= $current_view === 'reviews' ? 'active' : '' ?>"><i class="fa-solid fa-star"></i><span class="nav-text">Reviews</span> <span class="nav-text ml-auto bg-purple-100 text-purple-700 text-xs font-bold rounded-full px-2 py-0.5"><?= count($all_reviews) ?></span></a>
        <a href="admin.php?view=coupons" class="sidebar-link <?= $current_view === 'coupons' ? 'active' : '' ?>"><i class="fa-solid fa-ticket"></i><span class="nav-text">Coupons</span></a>
        <a href="admin.php?view=pages" class="sidebar-link <?= $current_view === 'pages' ? 'active' : '' ?>"><i class="fa-solid fa-file-alt"></i><span class="nav-text">Pages</span></a>

        <div class="pt-4 mt-4 border-t border-gray-200">
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase settings-text">Settings</p>
            <div class="mt-2 space-y-2">
                <a href="admin.php?view=settings_general" class="sidebar-link <?= $current_view === 'settings_general' ? 'active' : '' ?>"><i class="fa-solid fa-sliders"></i><span class="nav-text">General</span></a>
                <a href="admin.php?view=settings_homepage" class="sidebar-link <?= $current_view === 'settings_homepage' ? 'active' : '' ?>"><i class="fa-solid fa-house"></i><span class="nav-text">Homepage</span></a>
                <a href="admin.php?view=settings_payment" class="sidebar-link <?= $current_view === 'settings_payment' ? 'active' : '' ?>"><i class="fa-solid fa-credit-card"></i><span class="nav-text">Payment</span></a>
                <a href="admin.php?view=settings_contact" class="sidebar-link <?= $current_view === 'settings_contact' ? 'active' : '' ?>"><i class="fa-solid fa-address-book"></i><span class="nav-text">Contact & Email</span></a>
                <a href="admin.php?view=settings_security" class="sidebar-link <?= $current_view === 'settings_security' ? 'active' : '' ?>"><i class="fa-solid fa-shield-halved"></i><span class="nav-text">Security</span></a>
            </div>
        </div>
    </nav>
    <div class="p-4 border-t">
        <a href="../logout.php" class="btn btn-secondary w-full"><i class="fa-solid fa-right-from-bracket"></i> <span class="logout-text">Logout</span></a>
    </div>
</aside>
