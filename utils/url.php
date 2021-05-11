<?php
//declare(strict_types=1);

/**
 * siteUrl
 *
 * @param  string $partial
 * @return string
 */
function siteUrl(?string $partial = null) :string
{
    return BASE_URL.$partial;
}

/**
 * publicUrl
 *
 * @param  string $partial
 * @return string
 */
function publicUrl(string $partial = null) :string
{
    return PUBLIC_URL.$partial;
}

/**
 * redirectTo
 *
 * @param  string $endPath
 * @return void
 */
function redirectTo(string $endPath=null) :void
{
    $path = siteUrl($endPath);
    header("Location: $path");
    exit;
}

/**
 * getUriParts
 *
 * @param  mixed $num
 * @return string
 */
function getUriParts(?int $num = null) :string
{
    $uriParts = explode('/', $_GET['url']);
    if ($num === null) return $uriParts;
    return $uriParts[$num];
}
/**
 * currentUrl
 *
 * @return string
 */
function currentUrl() :string
{
    return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}
