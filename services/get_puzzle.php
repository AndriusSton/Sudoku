<?php
/**
 * Service for getting a SUDOKU puzzle. A JSON object is sent back.
 * 
 */


header('Access-Control-Allow-Origin: *');

include_once '../classes/Puzzle.php';
include_once '../classes/Backtracking.php';
include_once '../config/sudoku_defines.php';

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
    $puzzleArray = $puzzle->getPuzzle($request[3]);
    echo json_encode(array('grid' => $puzzleArray));
} else {
    http_response_code(400);
}




