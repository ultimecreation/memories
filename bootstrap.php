<?php

// Load utils
$utilFiles = array_diff(scandir('../utils'), array(".", ".."));
foreach ($utilFiles as $utilFile) {
    require_once("../utils/{$utilFile}");
}

// Load config
require_once('../config/config.php');


// Load Core classes
// require_once('core/Router.php');
// require_once('core/Model.php');
// require_once('core/Controller.php');
spl_autoload_register(function ($className) {
    require_once "core/{$className}.php";
});
