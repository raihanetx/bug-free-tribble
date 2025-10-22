<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// --- Helper Functions ---
function get_data($file_path) {
    if (!file_exists($file_path)) file_put_contents($file_path, '[]');
    return json_decode(file_get_contents($file_path), true);
}

function save_data($file_path, $data) {
    file_put_contents($file_path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
}

function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    if (function_exists('iconv')) {
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    }
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    if (empty($text)) {
        return 'n-a-' . rand(100, 999);
    }
    return $text;
}

function handle_image_upload($file_input, $upload_dir, $prefix = '') {
    if (isset($file_input) && $file_input['error'] === UPLOAD_ERR_OK) {
        $original_filename = basename($file_input['name']);
        $safe_filename = preg_replace("/[^a-zA-Z0-9-_\.]/", "", $original_filename);
        $destination = $upload_dir . $prefix . time() . '-' . uniqid() . '-' . $safe_filename;
        if (move_uploaded_file($file_input['tmp_name'], $destination)) {
            return $destination;
        }
    }
    return null;
}

function send_email($to, $subject, $body, $config) {
    $mail = new PHPMailer(true);
    $smtp_settings = $config['smtp_settings'] ?? [];
    $admin_email = $smtp_settings['admin_email'] ?? '';
    $app_password = $smtp_settings['app_password'] ?? '';

    if (empty($admin_email) || empty($app_password)) {
        return false;
    }

    try {
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $admin_email;
        $mail->Password   = $app_password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->setFrom($admin_email, 'Submonth');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
