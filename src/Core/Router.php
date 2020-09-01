<?php

namespace Core;

use App\Controller\GetController;
use App\Controller\PostController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of Router
 *
 * @author Andrius
 */
class Router {

    public function route(Request $request) {

        $uri = $request->getPathInfo();
        $uri = trim($uri, '/');
        // explode uri
        $uri = explode('/', $uri);

        if (empty($uri)) {
            die($uri . ' does not exist');
        }

        if ($uri[0] != 'get' && $uri[0] != 'post') {
            die($uri[0] . ' controller does not exist');
        }

        $controller = 'App\\Controller\\' . ucfirst($uri[0]) . 'Controller';
        $controllerObject = new $controller();

        // 2 part - check for action
        if (!isset($uri[1])) {
            die($uri[0] . ' method does not exist');
        }

        // if no action - not found exception
        $methodName = $uri[1] . 'Action';
        if (!method_exists($controller, $methodName)) {
            die($uri[0] . ' method does not exist');
        }

        if ($controllerObject instanceof GetController) {
            $arguments = $uri[2] ?? null;
            $controllerObject->$methodName($arguments);
        } elseif ($controllerObject instanceof PostController) {
            $controllerObject->$methodName($request);
        } else {
            die('Something have gone wrong');
        }
    }

}
