<?php
include 'partials/header.php';
include 'partials/sidebar.php';
?>

<!-- Main Content Area -->
<main class="flex-1 overflow-y-auto">
    <div class="container mx-auto p-6">
        <?php
        if ($category_to_manage !== null) {
            include 'views/products.php';
        } else {
            include 'views/orders.php';
            include 'views/categories.php';
            include 'views/hotdeals.php';
            include 'views/customers.php';
            include 'views/reviews.php';
            include 'views/coupons.php';
            include 'views/pages.php';
            include 'views/settings_general.php';
            include 'views/settings_homepage.php';
            include 'views/settings_payment.php';
            include 'views/settings_contact.php';
            include 'views/settings_security.php';
        }
        ?>
    </div>
</main>

<?php include 'partials/footer.php'; ?>
