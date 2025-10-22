</div>
</main>

<?php include 'modals.php'; ?>
</div>

<script>
    const allStats = {
        today: <?= json_encode($stats_today) ?>,
        '7days': <?= json_encode($stats_7_days) ?>,
        '30days': <?= json_encode($stats_30_days) ?>,
        '6months': <?= json_encode($stats_6_months) ?>,
        'all': <?= json_encode($stats_all_time) ?>
    };
    const allPaymentMethods = <?= json_encode($site_config['payment_methods'] ?? []) ?>;
    const adminManagerData = {
        allOrders: <?= json_encode($all_orders_data_raw) ?>,
        existingCustomerPhones: <?= json_encode($customer_phones) ?>,
        allCustomers: <?= json_encode($all_customers_data) ?>
    };

    document.addEventListener('alpine:init', () => {
        Alpine.data('adminManager', () => adminManager(adminManagerData))
    })
</script>
<script src="../assets/js/admin.js"></script>
</body>
</html>
