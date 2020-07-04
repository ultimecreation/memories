<?php
function siteUrl($partial = null)
{
    if ($_SERVER['HTTP_HOST'] === 'localhost') {
        if ($partial) {
            return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . $partial;
        } else {
            return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }
    } else {
        if ($partial) {
            return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . $partial;
        } else {
            return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
        }
    }
}

function redirectTo($endPath)
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
