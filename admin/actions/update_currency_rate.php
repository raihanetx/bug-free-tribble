<?php
$config = get_data($config_file_path);
if (isset($_POST['usd_to_bdt_rate'])) {
    $config['usd_to_bdt_rate'] = (float)$_POST['usd_to_bdt_rate'];
}
save_data($config_file_path, $config);
$redirect_url = 'admin/admin.php?view=settings_payment';
