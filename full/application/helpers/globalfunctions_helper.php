<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
function createEmailHtml($data = [])
{
    ob_start();
    extract($data);
    require_once(APPPATH . 'views/templates/respond_template.php');
    $html = ob_get_clean();
    return $html;
}

function recaptcha()
{
    $secret_key = '6LdnFPsUAAAAALWjF-jG7yOAgU4I9Y3RZiBXuPIU';
    $captcha_response = $_POST['g-recaptcha-response'];
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $captcha_response . '&remoteip=' . $user_ip);
    $responseData = json_decode($verifyResponse);
    if ($responseData->success == true && $responseData->score >= 0.8) {
        return true;
    } else {
        return false;
    }
}
