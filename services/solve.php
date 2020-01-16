<?php
/**
 * Service for getting the SUDOKU grid solution. A JSON object is sent back.
 */


// Headers

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
        header("Access-Control-Allow-Methods: POST");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require('../vendor/autoload.php');

use App\Classes\Backtracking;

if (!isset($_POST)) {
    http_response_code(400);
}
if (!is_array($_POST)) {
    http_response_code(400);
}

$grid = $_POST;

$sudokuSolver = new Backtracking();
try {
    $solution = $sudokuSolver->solve($grid);
    echo json_encode(array('grid' => $solution));
} catch (Exception $ex) {
    echo json_encode(array('error' => $ex->getMessage()));
}