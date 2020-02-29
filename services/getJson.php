<?php

if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    // you want to allow, and if so:
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: GET");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require('../vendor/autoload.php');

use App\Classes\Backtracking;
use App\Classes\Puzzle;

// split the request url 
$request = explode('/', rtrim((isset($_REQUEST['uri']) ? '/' . $_REQUEST['uri'] : '/'), '/'));

// check if all parameters are in the url
if (sizeof($request) === 4) {
    // check if level is chosen
    if (!in_array($request[3], array_keys(SUDOKU_LEVELS))) {
        http_response_code(400);
    }

    header('Content-Type: application/json');
    $puzzle = new Puzzle(new Backtracking());
    $array = $puzzle->getPuzzle($request[3]);

    $json = array();

    for ($i = 0; $i < count($array) / 9; $i++) {
        $row = array();
        for ($j = 0; $j < count($array) / 9; $j++) {
            $row[$j] = $array[$i * 9 + $j];
        }
        $json[$i] = $row;
    }
    echo json_encode(array('grid' => $json));
} else {
    http_response_code(400);
}

