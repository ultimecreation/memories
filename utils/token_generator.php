<?php
declare(strict_types=1);

/**
 * generate_token
 *
 * @param  int $length can be null
 * @return string
 */
function generate_token(?int $length = null) :string
{
    if ($length === null) {
        $token = bin2hex(random_bytes(128));
        return $token;
    } else {
        $token = bin2hex(random_bytes($length));
        return $token;
    }
}
