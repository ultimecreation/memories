<?php
declare(strict_types=1);
/**
 * truncate
 *
 * @param  string $text
 * @param  int $words
 * @return string
 */
function truncate(string $text,int $words) :string
{
    return implode(' ', array_slice(explode(' ', $text), 0, $words));
}
