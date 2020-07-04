<?php
function debug($var)
{
    if (is_array($var)) {
        foreach ($var as $element) {
            echo "<pre>" . print_r($element, true) . "</pre>";
        }
    } else {
        echo "<pre>" . print_r($var, true) . "</pre>";
    }
}
