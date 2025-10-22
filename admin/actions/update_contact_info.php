<?php
$config = get_data($config_file_path);
$config['contact_info']['phone'] = htmlspecialchars(trim($_POST['phone_number']));
$config['contact_info']['whatsapp'] = htmlspecialchars(trim($_POST['whatsapp_number']));
$config['contact_info']['email'] = htmlspecialchars(trim($_POST['email_address']));
save_data($config_file_path, $config);
$redirect_url = 'admin/admin.php?view=settings_contact';
