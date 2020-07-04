<?php

function truncate($text, $words)
{
    return implode(' ', array_slice(explode(' ', $text), 0, $words));
}
