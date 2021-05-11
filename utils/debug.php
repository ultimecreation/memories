<?php
declare(strict_types=1);
/**
 * debug
 *
 * @param  mixed $var can be a string or array
 * @return void
 */
function debug( $var) :void
{
    if (is_array($var)) {
        foreach ($var as $element) {
            echo "<pre>" . print_r($element, true) . "</pre>";
        }
    } else {
        echo "<pre>" . print_r($var, true) . "</pre>";
    }
}
