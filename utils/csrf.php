<?php
function setCsrfToken($referer)
{
    $csrf_token = generate_token();
    $_SESSION['csrf_token'] = $csrf_token;
    $_SESSION['token_expiration'] = time() + 6000;
    $_SESSION['referer'] = $referer;
    return $csrf_token;
}
function validateCsrf($data)
{
    if ($_SESSION['csrf_token'] == $_POST['csrf_token'] && $_SESSION['referer'] == $_POST['referer'] && $_SESSION['token_expiration'] > time()) {
        return true;
    } else {
        return false;
    }
}
function clearCsrf()
{
    unset($_SESSION['token_expiration']);
    unset($_SESSION['referer']);
    unset($_SESSION['csrf_token']);
}
