<?php
function siteUrl($partial = null)
{
    return BASE_URL.$partial;
}
function publicUrl($partial = null)
{
    return PUBLIC_URL.$partial;
}
function redirectTo($endPath=null)
{
    $path = siteUrl($endPath);

    header("Location: {$path}");
}
function getUriParts($num = null)
{
    $uriParts = explode('/', $_GET['url']);
    if ($num === null) return $uriParts;
    return $uriParts[$num];
}
function currentUrl()
{

    return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}
