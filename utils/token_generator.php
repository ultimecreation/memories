<?php
function generate_token($length = null)
{
    if ($length === null) {
        $token = bin2hex(random_bytes(128));
        return $token;
    } else {
        $token = bin2hex(random_bytes($length));
        return $token;
    }
}
