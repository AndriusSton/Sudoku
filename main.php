<?php

require_once 'vendor/autoload.php';

use Core\Router;
use Symfony\Component\HttpFoundation\Request;
/**
 * Routing
 */

$request = new Request(
    $_GET,
    $_POST,
    [],
    $_COOKIE,
    $_FILES,
    $_SERVER
);
$router = new Router();
$router->route($request);