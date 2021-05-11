<?php
declare(strict_types=1);

/**
 * setCsrfToken
 *
 * @param  string $referer
 * @return string
 */
function setCsrfToken(string $referer) :string
{
    $csrf_token = generate_token();
    $_SESSION['csrf_token'] = $csrf_token;
    $_SESSION['token_expiration'] = time() + 6000;
    $_SESSION['referer'] = $referer;
    return $csrf_token;
}
/**
 * validateCsrf
 *
 * @param  array $data
 * @return bool
 */
function validateCsrf(array $data) :bool
{
    if ($_SESSION['csrf_token'] == $data['csrf_token'] && $_SESSION['referer'] == $data['referer'] && $_SESSION['token_expiration'] > time()) {
        return true;
    } else {
        return false;
    }
}
/**
 * clearCsrf
 *
 * @return void
 */
function clearCsrf() :void
{
    unset($_SESSION['token_expiration']);
    unset($_SESSION['referer']);
    unset($_SESSION['csrf_token']);
}
