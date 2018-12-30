<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../classes/Puzzle.php';
include_once '../classes/Algorithm.php';
include_once '../config/sudoku_defines.php';

$request = explode('/', rtrim((isset($_REQUEST['uri']) ? '/' . $_REQUEST['uri'] : '/'), '/'));

if (sizeof($request) === 4) {
    if (in_array($request[3], array_keys(SUDOKU_LEVELS))) {
        $puzzle = new Puzzle(new Algorithm());
        $puzzleArray = $puzzle->getPuzzle($request[3]);
        echo json_encode(array('grid' => $puzzleArray));
    } else {
        echo json_encode(array('error' => 'Incorrect level'));
    }
} else {
    echo json_encode(array('error' => 'Wrong request'));
}




