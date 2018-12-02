<?php

/**
 * Created by PhpStorm.
 * User: altosh
 * Date: 5/21/18
 * Time: 2:08 PM
 */
require 'application/core/Router.php';

use application\core\Router;

spl_autoload_register(function ($class) {
    $path = str_replace('\\','/',$class.'.php');
    if (file_exists($path)) {
        require $path;
    }
});

session_start();

$route = new Router();
$route->run();
