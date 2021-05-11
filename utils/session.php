<?php
declare(strict_types=1);
/**
 * setUserData
 *
 * @param  object $data
 * @return void
 */
function setUserData(object $data) :void
{
    foreach ($data as $key => $value) {
      
        $_SESSION['user_logged'][$key] = $value;
    }
}

/**
 * getUserData
 *
 * @param  string $data can be null
 * @return 
 */
function getUserData(?string $data = null) 
{
    if (!$data) return $_SESSION['user_logged'];
    if ($data) return $_SESSION['user_logged'][$data];
}

/**
 * isUserLogged
 *
 * @return bool
 */
function isUserLogged() :bool
{
    if (!empty($_SESSION['user_logged'])) return true;
    return false;
}
/**
 * userLogoutRequest
 *
 * @return void
 */
function userLogoutRequest() :void
{
    unset($_SESSION['user_logged']);
}

/**
 * startSession
 *
 * @return void
 */
function startSession() :void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * setFlashMessage
 *
 * @param  string $type
 * @param  string $message
 * @return void
 */
function setFlashMessage(string $type,string $message) :void
{
    $_SESSION['flash'][$type]=$message;
}
